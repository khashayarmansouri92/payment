<?php

namespace App\Services\ShebaRequest;

use App\Enums\ShebaRequestStatus;
use App\Events\ShebaRequestCanceled;
use App\Events\ShebaRequestConfirmed;
use App\Exceptions\DuplicateRequestException;
use App\Exceptions\RequestAlreadyProcessedException;
use App\Models\ShebaRequests\ShebaRequest;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\IdempotencyKey\IdempotencyKeyRepositoryInterface;
use App\Repositories\ShebaRequest\ShebaRequestRepositoryInterface;
use App\Services\Account\AccountServiceInterface;
use App\Services\Transaction\TransactionServiceInterface;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

readonly class ShebaRequestService implements ShebaRequestServiceInterface
{
    public function __construct(
        private AccountServiceInterface $accountService,
        private TransactionServiceInterface $transactionService,
        private ShebaRequestRepositoryInterface $shebaRequestRepository,
        private IdempotencyKeyRepositoryInterface $idempotencyKeyRepository,
        private AccountRepositoryInterface $accountRepository
    )
    {
    }

    /**
     * @throws \Illuminate\Contracts\Cache\LockTimeoutException
     */
    public function create( array $data, string $idempotencyKey ): array
    {
        $cached = $this->getCachedResponse( $idempotencyKey );

        if ( $cached )
        {
            return [];
        }

        Cache::put(
            "idempotency:{$idempotencyKey}",
            $idempotencyKey
        );

        $lock = Cache::lock( "idem:{$idempotencyKey}", config( 'sheba.lock_timeout' ) );

        return $lock->block( config( 'sheba.block_timeout' ), function () use ( $data, $idempotencyKey )
        {
            $response = $this->processCreate( $data );
            $this->saveResponse( $idempotencyKey, $response );
            $this->addToPendingList( $response );

            return $response;
        } );
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $status = ShebaRequestStatus::PENDING->value;
        $statusEnum = ShebaRequestStatus::from( $status );
        $cacheKey = config( 'sheba.pending_list_cache_key' );
        $cached = Redis::zrange( $cacheKey, 0, -1 );

        if ( !empty( $cached ) )
        {
            return $this->getPendingList();
        }

        return $this->getFromDatabase( $statusEnum );
    }

    /**
     * @throws LockTimeoutException
     */
    public function updateStatus( string $id, string $status, ?string $note = null ): array
    {
        $lock = Cache::lock( "sheba:{$id}", config( 'sheba.lock_timeout' ) );

        return $lock->block( config( 'sheba.block_timeout' ), function () use ( $id, $status, $note )
        {
            return DB::transaction( function () use ( $id, $status, $note )
            {
                $shebaRequest = $this->shebaRequestRepository->findByIdWithLock( $id );

                if ( !$shebaRequest )
                {
                    throw new ModelNotFoundException();
                }

                $this->ensurePending( $shebaRequest );

                $statusEnum = ShebaRequestStatus::from( $status );

                match ( $statusEnum )
                {
                    ShebaRequestStatus::CONFIRMED => $this->confirm( $shebaRequest, $note ),
                    ShebaRequestStatus::CANCELED => $this->cancel( $shebaRequest, $note ),
                    default => throw new DuplicateRequestException( 'Unsupported status' ),
                };

                $this->removeFromPendingList( $id );

                Log::info( trans( 'messages.log.sheba_request_updated' ), [
                    'id' => $shebaRequest->id,
                    'status' => $shebaRequest->status,
                ] );

                return $this->mapToResponse( $shebaRequest, true );
            } );
        } );
    }

    /**
     * @param array $data
     * @return array
     */
    private function processCreate( array $data ): array
    {
        return DB::transaction( function () use ( $data )
        {
            $this->accountService->validateSheba( $data[ 'fromShebaNumber' ] );
            $this->accountService->validateSheba( $data[ 'toShebaNumber' ] );

            $from = $this->accountService->getBySheba( $data[ 'fromShebaNumber' ] );
            $this->accountService->ensureSufficientBalance( $from, $data[ 'price' ] );
            $this->accountService->debit( $from, $data[ 'price' ] );

            $request = $this->shebaRequestRepository->create( [
                'price' => $data[ 'price' ],
                'status' => ShebaRequestStatus::PENDING->value,
                'from_sheba_number' => $data[ 'fromShebaNumber' ],
                'to_sheba_number' => $data[ 'toShebaNumber' ],
                'note' => $data[ 'note' ] ?? null,
            ] );

            $this->transactionService->createWithdrawPending( $from, $data[ 'price' ], $request->id );

            Log::info( trans( 'messages.log.sheba_request_created' ), [ 'id' => $request->id ] );

            return $this->mapToResponse( $request );
        } );
    }

    /**
     * @param \App\Models\ShebaRequests\ShebaRequest $request
     * @param string|null $note
     * @return void
     */
    private function confirm( ShebaRequest $request, ?string $note ): void
    {
        $data[ 'status' ] = ShebaRequestStatus::CONFIRMED->value;

        if ( $note !== null )
        {
            $data[ 'note' ] = $note;
        }

        $from = $this->accountRepository->findByShebaWithLock( $request->from_sheba_number );
        $to = $this->accountRepository->findByShebaWithLock( $request->to_sheba_number );

        if ( $from )
        {
            $withdraw = $this->transactionService->getWithdrawByReference( $request->id, $from->id );
            if ( $withdraw )
            {
                $this->transactionService->completeWithdraw( $withdraw );
            }
        }

        if ( $to )
        {
            $this->transactionService->createDeposit( $to, $request->price, $request->id );
        }

        $this->shebaRequestRepository->update( $request, $data );

        event( new ShebaRequestConfirmed( $request ) );
    }

    /**
     * @param \App\Models\ShebaRequests\ShebaRequest $request
     * @param string|null $note
     * @return void
     */
    private function cancel( ShebaRequest $request, ?string $note ): void
    {
        $this->shebaRequestRepository->update( $request, [ 'status' => ShebaRequestStatus::CANCELED->value ] );

        $from = $this->accountRepository->findByShebaWithLock( $request->from_sheba_number );

        if ( $from )
        {
            $withdraw = $this->transactionService->getWithdrawByReference( $request->id, $from->id );
            if ( $withdraw )
            {
                $this->transactionService->completeWithdraw( $withdraw );
            }
            $this->transactionService->createRevert( $from, $request->price, $request->id );
        }

        event( new ShebaRequestCanceled( $request ) );
    }

    /**
     * @param \App\Models\ShebaRequests\ShebaRequest $request
     * @return void
     */
    private function ensurePending( ShebaRequest $request ): void
    {
        if ( ShebaRequestStatus::from( $request->status ) !== ShebaRequestStatus::PENDING )
        {
            throw new RequestAlreadyProcessedException( 'Request already processed' );
        }
    }

    /**
     * @param string $idempotencyKey
     * @return array|null
     */
    private function getCachedResponse( string $idempotencyKey ): ?array
    {
        $cacheKey = "idempotency:{$idempotencyKey}";
        $cached = Cache::get( $cacheKey );

        if ( $cached )
        {
            return $cached;
        }

        return null;
    }

    /**
     * @param string $idempotencyKey
     * @param array $response
     * @return void
     */
    private function saveResponse( string $idempotencyKey, array $response ): void
    {
        $cacheKey = "idempotency:{$idempotencyKey}";
        Cache::put( $cacheKey, $response, config( 'sheba.idempotency_cache_ttl' ) );

        $this->idempotencyKeyRepository->create( [
            'key' => $idempotencyKey,
            'response' => $response,
        ] );
    }

    /**
     * @return array
     */
    private function getPendingList(): array
    {
        if ( !empty( $cached ) )
        {
            return array_map( fn( $item ) => json_decode( $item, true ), $cached );
        }

        $requests = $this->shebaRequestRepository->findByStatusOrdered( ShebaRequestStatus::PENDING );
        $response = $requests->map( fn( $r ) => $this->mapToResponse( $r ) )->toArray();

        if ( !empty( $response ) )
        {
            $this->rebuildPendingList( $response );
        }

        return $response;
    }

    /**
     * @param \App\Enums\ShebaRequestStatus $status
     * @return array
     */
    private function getFromDatabase( ShebaRequestStatus $status ): array
    {
        $requests = $this->shebaRequestRepository->findByStatusOrdered( $status );
        return $requests->map( fn( $r ) => $this->mapToResponse( $r ) )->toArray();
    }

    /**
     * @param array $requestData
     * @return void
     */
    private function addToPendingList( array $requestData ): void
    {
        $cacheKey = config( 'sheba.pending_list_cache_key' );
        $score = strtotime( $requestData[ 'createdAt' ] ?? now()->toDateTimeString() );
        $value = json_encode( $requestData );

        Redis::zadd( $cacheKey, $score, $value );
        Redis::expire( $cacheKey, config( 'sheba.pending_list_cache_ttl' ) );
    }

    /**
     * @param string $requestId
     * @return void
     */
    private function removeFromPendingList( string $requestId ): void
    {
        $cacheKey = config( 'sheba.pending_list_cache_key' );
        $members = Redis::zrange( $cacheKey, 0, -1 );

        foreach ( $members as $member )
        {
            $data = json_decode( $member, true );
            if ( isset( $data[ 'id' ] ) && $data[ 'id' ] === $requestId )
            {
                Redis::zrem( $cacheKey, $member );
                break;
            }
        }
    }

    /**
     * @param array $requests
     * @return void
     */
    private function rebuildPendingList( array $requests ): void
    {
        $cacheKey = config( 'sheba.pending_list_cache_key' );
        Redis::del( $cacheKey );

        foreach ( $requests as $request )
        {
            $this->addToPendingList( $request );
        }
    }

    /**
     * @param \App\Models\ShebaRequests\ShebaRequest $request
     * @param bool $includeUpdatedAt
     * @return array
     */
    private function mapToResponse( ShebaRequest $request, bool $includeUpdatedAt = false ): array
    {
        $response = [
            'id' => $request->id,
            'status' => $request->status,
            'price' => $request->price,
            'fromShebaNumber' => $request->from_sheba_number,
            'toShebaNumber' => $request->to_sheba_number,
            'note' => $request->note,
            'createdAt' => $request->created_at,
        ];

        if ( $includeUpdatedAt )
        {
            $response[ 'updatedAt' ] = $request->updated_at;
        }

        return $response;
    }
}
<?php

namespace App\Http\Controllers\Api\V1\Sheba;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShebaRequest;
use App\Http\Resources\ApiResponse\ApiResponseResource;
use App\Http\Resources\ApiResponse\ResourcesTrait;
use App\Services\ShebaRequest\ShebaRequestServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ShebaStoreController extends Controller
{
    use ResourcesTrait;

    protected ShebaRequestServiceInterface $shebaRequestService;

    public function __construct( ShebaRequestServiceInterface $shebaRequestService )
    {
        $this->shebaRequestService = $shebaRequestService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke( Request $request ): ApiResponseResource
    {
        $storeShebaRequest = new StoreShebaRequest();

        $validator = Validator::make(
            $request->all(),
            $storeShebaRequest->rules(),
            $storeShebaRequest->messages()
        );

        if ($validator->fails()) {
            Log::warning(trans('messages.sheba.validation_failed'), [
                'errors' => $validator->errors()->toArray(),
            ]);

            return $this->error(
                trans('messages.sheba.operation_not_successful'),
                HttpFoundationResponse::HTTP_OK
            );
        }

        $validated = $validator->validated();

        $idempotencyKey = $request->header( 'Idempotency-Key' );

        if ( !$idempotencyKey )
        {
            return $this->error(
                trans( 'messages.sheba.missing_idempotency_key' ),
                HttpFoundationResponse::HTTP_OK
            );
        }

        $data = [
            'price' => $validated[ 'price' ],
            'fromShebaNumber' => $validated[ 'fromShebaNumber' ],
            'toShebaNumber' => $validated[ 'ToShebaNumber' ],
            'note' => $validated[ 'note' ] ?? null,
        ];

        try
        {
            $response = $this->shebaRequestService->create( $data, $idempotencyKey );

            return $this->success(
                $response,
                trans( 'messages.sheba.created' ),
                HttpFoundationResponse::HTTP_CREATED
            );
        }
        catch ( \Throwable $exception )
        {
            Log::error( 'ShebaStoreController create failed', [
                'message' => $exception->getMessage(),
                'data' => $data,
                'idempotency_key' => $idempotencyKey,
                'exception_class' => $exception::class,
            ] );

            return $this->error(
                trans( 'messages.sheba.operation_not_successful' ),
                HttpFoundationResponse::HTTP_OK
            );
        }
    }
}


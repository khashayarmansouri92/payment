<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ShebaTransferException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShebaTransferRequest;
use App\Http\Resources\ApiResponse\ApiResponseResource;
use App\Http\Resources\ApiResponse\ResourcesTrait;
use App\Services\ShebaTransfer\ShebaTransferServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class ShebaTransferController extends Controller
{
    use ResourcesTrait;

    public function __construct(
        private readonly ShebaTransferServiceInterface $shebaTransferService
    )
    {
    }

    /**
     * @param \App\Http\Requests\ShebaTransferRequest $request
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     */
    public function send( ShebaTransferRequest $request ): ApiResponseResource
    {
        try
        {
            $result = $this->processTransfer( $request );
            return $this->buildSuccessResponse( $result, $request->validated() );
        }
        catch ( ShebaTransferException $e )
        {
            return $this->buildErrorResponse( $e );
        }
    }

    /**
     * @param \App\Http\Requests\ShebaTransferRequest $request
     * @return array
     */
    private function processTransfer( ShebaTransferRequest $request ): array
    {
        $validated = $request->validated();

        return $this->shebaTransferService->send(
            $validated[ 'price' ],
            $validated[ 'fromShebaNumber' ],
            $validated[ 'ToShebaNumber' ],
            $validated[ 'note' ] ?? null,
            $request->header( 'Idempotency-Key' )
        );
    }

    /**
     * @param \App\Exceptions\ShebaTransferException $e
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     */
    private function buildErrorResponse( ShebaTransferException $e ): ApiResponseResource
    {
        $errorMessage = $e->getMessage() ?: __( 'sheba.response.failed' );
        $errorCode = (string)$e->getCode();

        return $this->error(
            [
                'message' => $errorMessage,
                'code' => $errorCode,
            ],
            $e->getCode()
        );
    }

    /**
     * @param array $result
     * @param array $validated
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     */
    private function buildSuccessResponse( array $result, array $validated ): ApiResponseResource
    {
        $data = $result[ 'data' ] ?? [];
        $message = $data[ 'message' ] ?? __( 'sheba.response.saved_pending' );
        $statusCode = $this->determineStatusCode( $data );
        $requestData = $this->buildRequestData( $data, $validated, $message );

        return $this->success(
            [ 'request' => $requestData ],
            $message,
            $statusCode
        );
    }

    /**
     * @param array $data
     * @return int
     */
    private function determineStatusCode( array $data ): int
    {
        return ( $data[ 'success' ] ?? false ) === true
            ? Response::HTTP_OK
            : Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    /**
     * @param array $data
     * @param array $validated
     * @param string $message
     * @return array
     */
    private function buildRequestData( array $data, array $validated, string $message ): array
    {
        return [
            'id' => $data[ 'data' ][ 'id' ] ?? null,
            'price' => $data[ 'data' ][ 'price' ] ?? $validated[ 'price' ],
            'status' => $data[ 'data' ][ 'status' ] ?? 'pending',
            'fromShebaNumber' => $data[ 'data' ][ 'fromShebaNumber' ] ?? $validated[ 'fromShebaNumber' ],
            'ToShebaNumber' => $data[ 'data' ][ 'ToShebaNumber' ] ?? $validated[ 'ToShebaNumber' ],
            'createdAt' => $data[ 'data' ][ 'createdAt' ] ?? now()->toIso8601String(),
            'message' => $message,
        ];
    }
}
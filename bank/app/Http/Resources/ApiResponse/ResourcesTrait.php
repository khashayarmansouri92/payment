<?php

namespace App\Http\Resources\ApiResponse;

use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait ResourcesTrait
{
    /**
     * @param $data
     * @param int $status
     * @param string|null $message
     * @return ApiResponseResource
     */
    public function success( $data, string $message = null, int $status = HttpFoundationResponse::HTTP_OK ): ApiResponseResource
    {
        return ( new ApiResponseResource( [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ] ) )->withStatusCode( $status );
    }

    /**
     * @param int $status
     * @param string|array|null $message
     * @return ApiResponseResource
     */
    public function error( string|array $message = null, int $status = HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR ): ApiResponseResource
    {
        return ( new ApiResponseResource( [
            'success' => false,
            'message' => $message ?? null,
        ] ) )->withStatusCode( $status );
    }

    /**
     * @param $data
     * @param $meta
     * @param string|null $message
     * @param int $status
     * @return ApiResponseResource
     */
    public function paginate( $data, $meta, string $message = null, int $status = HttpFoundationResponse::HTTP_OK ): ApiResponseResource
    {
        return ( new ApiResponseResource( [
            'success' => true,
            'data' => $data,
            'meta' => $meta,
            'message' => $message,
        ] ) )->withStatusCode( $status );
    }

    /**
     * @param int $status
     * @param string|null $message
     * @return ApiResponseResource
     */
    public static function exception( string $message = null, int $status = HttpFoundationResponse::HTTP_FORBIDDEN ): ApiResponseResource
    {
        return ( new ApiResponseResource( [
            'success' => false,
            'message' => $message,
        ] ) )->withStatusCode( $status );
    }
}
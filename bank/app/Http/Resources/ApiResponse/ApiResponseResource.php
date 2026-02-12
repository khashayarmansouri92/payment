<?php

namespace App\Http\Resources\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    protected $statusCode;

    /**
     * @param $request
     * @return array
     */
    public function toArray( $request ): array
    {
        $response = [
            'success' => $this->resource[ 'success' ] ?? false,
            'data' => $this->resource[ 'data' ] ?? [],
            'message' => $this->resource[ 'message' ] ?? null,
            'status' => $this->statusCode,
        ];

        if ( isset( $this->resource[ 'meta' ] ) )
        {
            $response[ 'meta' ] = $this->resource[ 'meta' ];
        }

        return $response;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function withStatusCode( $statusCode ): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $request
     * @return array
     */
    public function with( $request ): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @param $response
     * @return void
     */
    public function withResponse( Request $request, $response ): void
    {
        $response->setStatusCode( $this->statusCode );
    }
}
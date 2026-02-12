<?php

namespace App\Services\ShebaTransfer;

use App\Exceptions\ShebaTransferException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShebaTransferService implements ShebaTransferServiceInterface
{
    /**
     * @param int $price
     * @param string $fromShebaNumber
     * @param string $toShebaNumber
     * @param string|null $note
     * @param string|null $idempotencyKey
     * @return array
     * @throws \App\Exceptions\ShebaTransferException
     */
    public function send(
        int $price,
        string $fromShebaNumber,
        string $toShebaNumber,
        ?string $note = null,
        ?string $idempotencyKey = null
    ): array
    {
        $payload = $this->buildPayload( $price, $fromShebaNumber, $toShebaNumber, $note );
        $idempotencyKey = $idempotencyKey ?? Str::uuid()->toString();

        $response = $this->sendRequest( $payload, $idempotencyKey );

        return [
            'idempotency_key' => $idempotencyKey,
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    /**
     * @param int $price
     * @param string $fromShebaNumber
     * @param string $toShebaNumber
     * @param string|null $note
     * @return array
     */
    private function buildPayload(
        int $price,
        string $fromShebaNumber,
        string $toShebaNumber,
        ?string $note
    ): array
    {
        return [
            'price' => $price,
            'fromShebaNumber' => $fromShebaNumber,
            'ToShebaNumber' => $toShebaNumber,
            'note' => $note,
        ];
    }

    /**
     * @param array $payload
     * @param string $idempotencyKey
     * @return \Illuminate\Http\Client\Response
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function sendRequest( array $payload, string $idempotencyKey ): Response
    {
        $url = $this->buildUrl();
        $headers = $this->buildHeaders( $idempotencyKey );

        $this->logRequest( $url, $headers, $payload );

        try
        {
            $response = $this->makeHttpRequest( $url, $headers, $payload );
        }
        catch ( ConnectionException $e )
        {
            $this->handleConnectionException( $url, $e );
        }

        $this->logResponse( $response );

        $this->validateResponse( $response );

        return $response;
    }

    /**
     * @return string
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function buildUrl(): string
    {
        $baseUrl = Config::get( 'bank.base_url' );
        $endpoint = Config::get( 'bank.api_endpoint' );

        if ( !$baseUrl )
        {
            throw new ShebaTransferException( __( 'sheba.errors.bank_url_not_configured' ) );
        }

        return rtrim( $baseUrl, '/' ) . $endpoint;
    }

    /**
     * @param string $idempotencyKey
     * @return string[]
     */
    private function buildHeaders( string $idempotencyKey ): array
    {
        return [
            'Accept' => 'application/json',
            'Idempotency-Key' => $idempotencyKey,
        ];
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $payload
     * @return \Illuminate\Http\Client\Response
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function makeHttpRequest( string $url, array $headers, array $payload ): Response
    {
        $timeout = Config::get( 'bank.timeout' );
        $maxRetries = Config::get( 'bank.max_retries' );
        $retryDelayMs = Config::get( 'bank.retry_delay_ms' );

        return Http::withHeaders( $headers )
            ->acceptJson()
            ->timeout( $timeout )
            ->retry( $maxRetries, $retryDelayMs )
            ->post( $url, $payload );
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function validateResponse( Response $response ): void
    {
        if ( $response->successful() )
        {
            return;
        }

        if ( $response->serverError() )
        {
            $this->handleServerError( $response );
        }

        if ( $response->clientError() )
        {
            $this->handleClientError( $response );
        }

        $this->handleUnknownError( $response );
    }

    /**
     * @param string $url
     * @param \Illuminate\Http\Client\ConnectionException $e
     * @return void
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function handleConnectionException( string $url, ConnectionException $e ): void
    {
        Log::channel( 'sheba' )->warning( __( 'sheba.log.network_error' ), [
            'url' => $url,
            'method' => 'POST',
            'max_retries' => Config::get( 'bank.max_retries' ),
            'exception' => $e->getMessage(),
        ] );

        throw new ShebaTransferException( __( 'sheba.errors.network_failure' ), 0, $e );
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function handleServerError( Response $response ): void
    {
        Log::channel( 'sheba' )->warning( __( 'sheba.log.server_error' ), [
            'status' => $response->status(),
            'body' => $response->body(),
        ] );

        throw new ShebaTransferException(
            __( 'sheba.errors.server_error', [ 'status' => $response->status() ] ),
            $response->status()
        );
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function handleClientError( Response $response ): void
    {
        $body = $response->json();
        $error = is_array( $body ) ? ( $body[ 'error' ] ?? null ) : null;

        [ $message, $code ] = $this->extractErrorDetails( $error, $response );

        Log::channel( 'sheba' )->warning( __( 'sheba.log.client_error' ), [
            'status' => $response->status(),
            'body' => $response->body(),
        ] );

        throw new ShebaTransferException( $message, $code );
    }

    /**
     * @param array|null $error
     * @param \Illuminate\Http\Client\Response $response
     * @return array
     */
    private function extractErrorDetails( ?array $error, Response $response ): array
    {
        if ( !is_array( $error ) )
        {
            return [
                __( 'sheba.errors.generic_failure', [ 'status' => $response->status() ] ),
                $response->status(),
            ];
        }

        $message = $error[ 'message' ] ?? __( 'sheba.errors.generic_failure', [ 'status' => $response->status() ] );
        $code = (int)( $error[ 'code' ] ?? $response->status() );

        return [ $message, $code ];
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     * @throws \App\Exceptions\ShebaTransferException
     */
    private function handleUnknownError( Response $response ): void
    {
        Log::channel( 'sheba' )->error( __( 'sheba.log.unknown_error' ), [
            'status' => $response->status(),
            'body' => $response->body(),
        ] );

        throw new ShebaTransferException(
            __( 'sheba.errors.generic_failure', [ 'status' => $response->status() ] ),
            $response->status()
        );
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $payload
     * @return void
     */
    private function logRequest( string $url, array $headers, array $payload ): void
    {
        Log::channel( 'sheba' )->info( __( 'sheba.log.request' ), [
            'url' => $url,
            'headers' => $headers,
            'payload' => $payload,
            'method' => 'POST',
            'max_retries' => Config::get( 'bank.max_retries' ),
        ] );
    }

    /**
     * @param \Illuminate\Http\Client\Response $response
     * @return void
     */
    private function logResponse( Response $response ): void
    {
        Log::channel( 'sheba' )->info( __( 'sheba.log.response' ), [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->json(),
        ] );
    }
}
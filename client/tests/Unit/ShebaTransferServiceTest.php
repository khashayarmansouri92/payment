<?php

namespace Tests\Unit;

use App\Exceptions\ShebaTransferException;
use App\Services\ShebaTransfer\ShebaTransferService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShebaTransferServiceTest extends TestCase
{
    public function test_send_returns_successful_response_array(): void
    {
        Config::set('bank.base_url', 'https://bank.test');
        Config::set('bank.timeout', 5);
        Config::set('bank.max_retries', 1);
        Config::set('bank.retry_delay_ms', 0);

        Http::fake(function () {
            return Http::response([
                'success' => true,
                'data' => [
                    'id' => 'request-id',
                    'price' => 1000,
                ],
            ], 200);
        });

        $service = new ShebaTransferService();

        $result = $service->send(
            1000,
            'IR000000000000000000000000',
            'IR000000000000000000000001',
            'test',
            'fixed-idempotency-key'
        );

        $this->assertSame('fixed-idempotency-key', $result['idempotency_key']);
        $this->assertSame(200, $result['status']);
        $this->assertSame('request-id', $result['data']['data']['id']);
        $this->assertSame(1000, $result['data']['data']['price']);
    }

    public function test_send_throws_exception_on_server_error(): void
    {
        Config::set('bank.base_url', 'https://bank.test');
        Config::set('bank.timeout', 5);
        Config::set('bank.max_retries', 1);
        Config::set('bank.retry_delay_ms', 0);

        Http::fake(function () {
            return Http::response([], 500);
        });

        $service = new ShebaTransferService();

        $this->expectException(ShebaTransferException::class);

        $service->send(
            1000,
            'IR000000000000000000000000',
            'IR000000000000000000000001',
            null,
            'fixed-idempotency-key'
        );
    }
}


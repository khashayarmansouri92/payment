<?php

return [
    'base_url' => env('BANK_API_BASE_URL'),
    'timeout' => (int) env('BANK_API_TIMEOUT', 10),
    'max_retries' => (int) env('BANK_API_MAX_RETRIES', 3),
    'min_price' => (int) env('BANK_MIN_PRICE', 1),
    'sheba_length' => (int) env('BANK_SHEBA_LENGTH', 26),
    'api_prefix' => env('BANK_API_PREFIX', 'bank'),
    'api_version' => env('BANK_API_VERSION', 'v1'),
    'api_endpoint' => env('BANK_API_ENDPOINT', '/api/v1/sheba'),
    'retry_delay_ms' => (int) env('BANK_RETRY_DELAY_MS', 200),
];


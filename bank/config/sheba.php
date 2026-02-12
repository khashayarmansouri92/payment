<?php

return [
    'lock_timeout' => (int) env('SHEBA_LOCK_TIMEOUT', 10),
    'block_timeout' => (int) env('SHEBA_BLOCK_TIMEOUT', 5),
    'idempotency_cache_ttl' => (int) env('SHEBA_IDEMPOTENCY_CACHE_TTL', 3600),
    'pending_list_cache_key' => env('SHEBA_PENDING_LIST_CACHE_KEY', 'sheba:requests:pending'),
    'pending_list_cache_ttl' => (int) env('SHEBA_PENDING_LIST_CACHE_TTL', 86400),
    'max_price' => (int) env('SHEBA_MAX_PRICE', 2000000),
];


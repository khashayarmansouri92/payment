<?php

namespace App\Repositories\IdempotencyKey;

use App\Models\IdempotencyKeys\IdempotencyKey;

interface IdempotencyKeyRepositoryInterface
{
    /**
     * @param string $key
     * @return \App\Models\IdempotencyKeys\IdempotencyKey|null
     */
    public function findByKey(string $key): ?IdempotencyKey;

    /**
     * @param array $data
     * @return \App\Models\IdempotencyKeys\IdempotencyKey
     */
    public function create(array $data): IdempotencyKey;
}

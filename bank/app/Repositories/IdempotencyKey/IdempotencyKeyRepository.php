<?php

namespace App\Repositories\IdempotencyKey;

use App\Models\IdempotencyKeys\IdempotencyKey;

class IdempotencyKeyRepository implements IdempotencyKeyRepositoryInterface
{
    /**
     * @param string $key
     * @return \App\Models\IdempotencyKeys\IdempotencyKey|null
     */
    public function findByKey( string $key ): ?IdempotencyKey
    {
        return IdempotencyKey::findByKey( $key );
    }

    /**
     * @param array $data
     * @return \App\Models\IdempotencyKeys\IdempotencyKey
     */
    public function create( array $data ): IdempotencyKey
    {
        return IdempotencyKey::create( $data );
    }
}

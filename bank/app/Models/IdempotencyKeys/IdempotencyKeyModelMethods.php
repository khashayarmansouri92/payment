<?php

namespace App\Models\IdempotencyKeys;

trait IdempotencyKeyModelMethods
{
    /**
     * @param string $key
     * @return \App\Models\IdempotencyKeys\IdempotencyKey|\App\Models\IdempotencyKeys\IdempotencyKeyModelMethods|null
     */
    public static function findByKey(string $key): ?self
    {
        return self::where('key', $key)->first();
    }
}


<?php

namespace App\Models\IdempotencyKeys;

use Illuminate\Database\Eloquent\Model;
use App\Models\IdempotencyKeys\IdempotencyKeyModelMethods;
use App\Models\IdempotencyKeys\IdempotencyKeyModelRelations;
use App\Models\IdempotencyKeys\IdempotencyKeyModelFilters;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdempotencyKey extends Model
{
    use IdempotencyKeyModelMethods, IdempotencyKeyModelRelations, IdempotencyKeyModelFilters, SoftDeletes;

    protected $fillable = [
        'key',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];
}


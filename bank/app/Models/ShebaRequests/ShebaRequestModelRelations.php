<?php

namespace App\Models\ShebaRequests;

use App\Models\Transactions\Transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ShebaRequestModelRelations
{
    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'reference_id');
    }
}


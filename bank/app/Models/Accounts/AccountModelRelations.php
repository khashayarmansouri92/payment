<?php

namespace App\Models\Accounts;

use App\Models\Transactions\Transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait AccountModelRelations
{
    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}


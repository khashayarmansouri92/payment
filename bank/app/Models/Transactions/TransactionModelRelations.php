<?php

namespace App\Models\Transactions;

use App\Models\Accounts\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TransactionModelRelations
{
    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo( Account::class );
    }
}


<?php

namespace App\Models\Transactions;

use App\Enums\TransactionType;

trait TransactionModelMethods
{
    public static function findWithdrawByReferenceWithLock(string $referenceId, int $accountId): ?self
    {
        return static::where('reference_id', $referenceId)
            ->where('account_id', $accountId)
            ->where('type', TransactionType::WITHDRAW->value)
            ->lockForUpdate()
            ->first();
    }
}


<?php

namespace App\Repositories\Transaction;

use App\Models\Transactions\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;

    public function findWithdrawByReferenceWithLock(string $referenceId, int $accountId): ?Transaction;
}

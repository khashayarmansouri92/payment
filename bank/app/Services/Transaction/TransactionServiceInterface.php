<?php

namespace App\Services\Transaction;

use App\Models\Accounts\Account;
use App\Models\Transactions\Transaction;

interface TransactionServiceInterface
{
    public function createWithdrawPending( Account $account, int $amount, string $referenceId ): Transaction;

    public function completeWithdraw( Transaction $transaction ): void;

    public function createDeposit( Account $account, int $amount, string $referenceId ): Transaction;

    public function createRevert( Account $account, int $amount, string $referenceId ): Transaction;

    public function getWithdrawByReference( string $referenceId, int $accountId ): ?Transaction;
}


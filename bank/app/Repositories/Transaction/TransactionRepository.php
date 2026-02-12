<?php

namespace App\Repositories\Transaction;

use App\Models\Transactions\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\Transactions\Transaction
     */
    public function create( array $data ): Transaction
    {
        return Transaction::create( $data );
    }

    /**
     * @param string $referenceId
     * @param int $accountId
     * @return \App\Models\Transactions\Transaction|null
     */
    public function findWithdrawByReferenceWithLock( string $referenceId, int $accountId ): ?Transaction
    {
        return Transaction::findWithdrawByReferenceWithLock( $referenceId, $accountId );
    }
}

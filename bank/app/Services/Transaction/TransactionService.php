<?php

namespace App\Services\Transaction;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Accounts\Account;
use App\Models\Transactions\Transaction;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TransactionService implements TransactionServiceInterface
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct( TransactionRepositoryInterface $transactionRepository )
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function createWithdrawPending( Account $account, int $amount, string $referenceId ): Transaction
    {
        $transaction = $this->transactionRepository->create( [
            'account_id' => $account->id,
            'amount' => $amount,
            'type' => TransactionType::WITHDRAW->value,
            'status' => TransactionStatus::PENDING->value,
            'reference_id' => $referenceId,
        ] );
        Log::info( trans( 'messages.log.transaction_withdraw_created' ), [ 'transaction_id' => $transaction->id ] );
        return $transaction;
    }

    public function completeWithdraw( Transaction $transaction ): void
    {
        $transaction->status = TransactionStatus::COMPLETED->value;
        $transaction->save();
        Log::info( trans( 'messages.log.transaction_withdraw_completed' ), [ 'transaction_id' => $transaction->id ] );
    }

    public function createDeposit( Account $account, int $amount, string $referenceId ): Transaction
    {
        $transaction = $this->transactionRepository->create( [
            'account_id' => $account->id,
            'amount' => $amount,
            'type' => TransactionType::DEPOSIT->value,
            'status' => TransactionStatus::COMPLETED->value,
            'reference_id' => $referenceId,
        ] );
        Log::info( trans( 'messages.log.transaction_deposit_created' ), [ 'transaction_id' => $transaction->id ] );
        return $transaction;
    }

    public function createRevert( Account $account, int $amount, string $referenceId ): Transaction
    {
        $transaction = $this->transactionRepository->create( [
            'account_id' => $account->id,
            'amount' => $amount,
            'type' => TransactionType::REVERT->value,
            'status' => TransactionStatus::COMPLETED->value,
            'reference_id' => $referenceId,
        ] );
        Log::info( trans( 'messages.log.transaction_revert_created' ), [ 'transaction_id' => $transaction->id ] );
        return $transaction;
    }

    public function getWithdrawByReference( string $referenceId, int $accountId ): ?Transaction
    {
        return $this->transactionRepository->findWithdrawByReferenceWithLock( $referenceId, $accountId );
    }
}


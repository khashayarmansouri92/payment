<?php

namespace App\Services\Account;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidShebaException;
use App\Models\Accounts\Account;
use App\Repositories\Account\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AccountService implements AccountServiceInterface
{
    protected AccountRepositoryInterface $accountRepository;

    public function __construct( AccountRepositoryInterface $accountRepository )
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param string $sheba
     * @return void
     */
    public function validateSheba( string $sheba ): void
    {
        if ( !preg_match( '/^IR[0-9A-Z]{24}$/', $sheba ) || strlen( $sheba ) !== 26 )
        {
            throw new InvalidShebaException( trans( 'messages.account.invalid_sheba' ) );
        }
    }

    /**
     * @param string $sheba
     * @return \App\Models\Accounts\Account
     */
    public function getBySheba( string $sheba ): Account
    {
        $account = $this->accountRepository->findByShebaWithLock( $sheba );

        if ( !$account )
        {
            throw new ModelNotFoundException();
        }
        return $account;
    }

    /**
     * @param \App\Models\Accounts\Account $account
     * @param int $amount
     * @return void
     */
    public function ensureSufficientBalance( Account $account, int $amount ): void
    {
        if ( $account->balance < $amount )
        {
            throw new InsufficientBalanceException(
                trans( 'messages.account.insufficient_balance', [ 'id' => $account->id ] )
            );
        }
    }

    /**
     * @param \App\Models\Accounts\Account $account
     * @param int $amount
     * @return void
     */
    public function debit( Account $account, int $amount ): void
    {
        $data[ 'balance' ] = $account->balance - $amount;
        $this->accountRepository->update( $account, $data );

        Log::info( trans( 'messages.log.account_debited' ), [ 'account_id' => $account->id, 'amount' => $amount, 'balance' => $account->balance ] );
    }

    public function credit( Account $account, int $amount ): void
    {
        $data[ 'balance' ] = $account->balance + $amount;
        $this->accountRepository->update( $account, $data );

        Log::info( trans( 'messages.log.account_credited' ), [ 'account_id' => $account->id, 'amount' => $amount, 'balance' => $account->balance ] );
    }
}


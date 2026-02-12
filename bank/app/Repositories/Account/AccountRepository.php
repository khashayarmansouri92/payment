<?php

namespace App\Repositories\Account;

use App\Models\Accounts\Account;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @param string $sheba
     * @return \App\Models\Accounts\Account|null
     */
    public function findByShebaWithLock( string $sheba ): ?Account
    {
        return Account::findByShebaWithLock( $sheba );
    }

    /**
     * @param \App\Models\Accounts\Account $request
     * @param array $data
     * @return void
     */
    public function update( Account $request, array $data ): void
    {
        $request->update( $data );
    }
}

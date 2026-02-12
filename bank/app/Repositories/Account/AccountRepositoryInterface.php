<?php

namespace App\Repositories\Account;

use App\Models\Accounts\Account;

interface AccountRepositoryInterface
{
    /**
     * @param string $sheba
     * @return \App\Models\Accounts\Account|null
     */
    public function findByShebaWithLock(string $sheba): ?Account;
    public function update( Account $request, array $data ): void;
}

<?php

namespace App\Services\Account;

use App\Models\Accounts\Account;

interface AccountServiceInterface
{
    public function validateSheba( string $sheba ): void;

    public function getBySheba( string $sheba ): Account;

    public function ensureSufficientBalance( Account $account, int $amount ): void;

    public function debit( Account $account, int $amount ): void;

    public function credit( Account $account, int $amount ): void;
}


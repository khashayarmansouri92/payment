<?php

namespace App\Models\Accounts;

trait AccountModelMethods
{
    /**
     * @param string $sheba
     * @return \App\Models\Accounts\Account|\App\Models\Accounts\AccountModelMethods|null
     */
    public static function findByShebaWithLock( string $sheba ): ?self
    {
        return self::where( 'sheba_number', $sheba )->lockForUpdate()->first();
    }
}


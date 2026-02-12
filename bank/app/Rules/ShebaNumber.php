<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ShebaNumber implements ValidationRule
{
    public function validate( string $attribute, mixed $value, Closure $fail ): void
    {
        if ( !is_string( $value ) )
        {
            $fail( trans( 'messages.account.invalid_sheba' ) );
            return;
        }

        if ( strlen( $value ) !== 26 )
        {
            $fail( trans( 'messages.account.invalid_sheba' ) );
            return;
        }

        if ( !preg_match( '/^IR[0-9A-Z]{24}$/', $value ) )
        {
            $fail( trans( 'messages.account.invalid_sheba' ) );
        }
    }
}


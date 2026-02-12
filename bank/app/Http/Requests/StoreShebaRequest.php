<?php

namespace App\Http\Requests;

use App\Rules\ShebaNumber;

class StoreShebaRequest
{

    public function rules(): array
    {
        return [
            'price' => [ 'required', 'integer', 'min:1', 'max:' . config( 'sheba.max_price' ) ],
            'fromShebaNumber' => [ 'required', new ShebaNumber, 'different:ToShebaNumber' ],
            'ToShebaNumber' => [ 'required', new ShebaNumber ],
            'note' => [ 'nullable', 'string' ],
        ];
    }

    public function messages(): array
    {
        return [
            'price.max' => trans( 'messages.sheba.max_price_exceeded' ),
            'fromShebaNumber.different' => trans( 'messages.sheba.sheba_numbers_must_be_different' ),
        ];
    }
}


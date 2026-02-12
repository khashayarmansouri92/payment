<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class ShebaTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $minPrice = Config::get('bank.min_price');

        return [
            'price' => ['required', 'integer', 'min:' . $minPrice],
            'fromShebaNumber' => ['required', 'string'],
            'ToShebaNumber' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ];
    }
}


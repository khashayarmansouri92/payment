<?php

namespace App\Http\Requests;

use App\Enums\ShebaRequestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShebaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [ 'required', Rule::enum( ShebaRequestStatus::class ) ],
            'note' => [ 'nullable', 'string' ],
        ];
    }
}


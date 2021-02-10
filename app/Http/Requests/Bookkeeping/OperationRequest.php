<?php

namespace App\Http\Requests\Bookkeeping;

use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_id' => [
                'required',
                'exists:accounts,id'
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'incoming' => [
                'boolean',
            ],
            'description' => [
                'string'
            ],
        ];
    }
}
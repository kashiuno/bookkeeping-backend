<?php

namespace App\Http\Requests\Bookkeeping;

use Illuminate\Foundation\Http\FormRequest;

class AccountTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:50',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'name.max'      => 'Поле имя не должно быть больше 50 символов',
        ];
    }
}

<?php

namespace App\Http\Requests\Bookkeeping;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => [
                'required',
                'max:50',
            ],
            'sum'             => [
                'numeric',
                'min:0',
            ],
            'account_type_id' => [
                'required',
                'exists:account_types,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'name.max' => 'Поле имя не должно быть больше 50 символов',
            'sum.numeric' => 'Сумма должна быть числом',
            'sum.min' => 'Сумма не должна быть меньше нуля',
            'account_type_id.required' => 'Тип счета обязателен для заполнения',
            'account_type_id.exists' => 'Данный тип счета не существует',
        ];
    }
}

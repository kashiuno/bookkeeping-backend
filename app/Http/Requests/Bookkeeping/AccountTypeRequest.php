<?php

namespace App\Http\Requests\Bookkeeping;

use Illuminate\Foundation\Http\FormRequest;

class AccountTypeRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize () {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules () {
        return [
            'name' => [
                'required',
                'max:50',
                'unique:account_types',
            ],
        ];
    }

    public function messages () {
        return [
            'name.required' => 'Поле имя обязательно для заполнения.',
            'name.max'      => 'Поле имя не должно быть больше 50 символов.',
            'name.unique'   => 'Данное имя уже существует.',
        ];
    }
}

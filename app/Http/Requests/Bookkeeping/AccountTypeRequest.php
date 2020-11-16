<?php

namespace App\Http\Requests\Bookkeeping;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                Rule::unique('account_types')->where(function (Builder $query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имя обязательно для заполнения',
            'name.max'      => 'Поле имя не должно быть больше 50 символов',
            'name.unique'   => 'Поле имя должно быть уникальным',
        ];
    }
}

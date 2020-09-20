<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'between:8,50', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя не заполнено',
            'name.string' => 'Имя должно быть строкой',
            'name.max' => 'Имя не может превышать 50 символов',
            'name.unique' => 'Имя должно быть уникальным',
            'email.required' => 'E-mail не заполнен',
            'email.string' => 'E-mail должен быть строкой',
            'email.email' => 'E-mail невалидный',
            'email.max' => 'E-mail должен занимать не больше 255 символов',
            'email.unique' => 'Пользователь с данным e-mail уже существует',
            'password.required' => 'Пароль не заполнен',
            'password.string' => 'Пароль должен быть строкой',
            'password.between' => 'Пароль должен быть от 8 до 50 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}

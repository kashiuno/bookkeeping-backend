<?php

namespace App\Http\Requests\Api\Auth;

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
            'name.required' => 'Field name is required',
            'name.string' => 'Field name mst be a string',
            'name.max:50' => 'Field name max size is 50 characters',
            'name.unique' => 'Name must be an unique',
            'email.required' => 'Field email is required',
            'email.string' => 'Field email must be a string',
            'email.email' => 'Field email must be a valid email',
            'email.max:255' => 'Field email max size is 255 characters',
            'email.unique' => 'Field email must be an unique',
            'password.required' => 'Field password is required',
            'password.string' => 'Field password must be a string',
            'password.between:8,50' => 'Field password must have min 8 characters and 50 characters',
            'password.confirmed' => 'Field password must have a valid confirmation',
        ];
    }
}

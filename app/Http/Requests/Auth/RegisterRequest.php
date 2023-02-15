<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'string|required',
            "last_name" => 'string|required',
            'email' => 'string|unique:users,email|required',
            'password' => 'string|confirmed|required',
            'password_confirmation' => 'string|required'
        ];
    }
}

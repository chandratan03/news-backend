<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'password' => 'nullable|confirmed',
            'password_confirmation' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }
}

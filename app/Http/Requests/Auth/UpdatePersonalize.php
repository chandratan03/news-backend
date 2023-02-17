<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalize extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sources.*' => 'nullable|exists:sources,id',
            "categories.*" => 'nullable|exists:news_categories,id',
            'authors.*' => 'nullable|exists:contributors,id',
        ];
    }
}

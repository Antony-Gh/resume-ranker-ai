<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'category_id' => 'nullable|exists:categories,id',
            'website_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'favorite' => 'sometimes|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }
}

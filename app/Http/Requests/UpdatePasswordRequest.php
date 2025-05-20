<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('password'));
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
            'category_id' => 'nullable|exists:categories,id',
            'website_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
            'favorite' => 'sometimes|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }
}

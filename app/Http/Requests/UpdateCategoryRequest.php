<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('category'));
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ];
    }
}

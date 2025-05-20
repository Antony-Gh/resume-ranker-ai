<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules()
    {
        return [
            'username' => 'sometimes|string|max:255|unique:saver_users,username,'.$this->user()->id,
            'email' => 'sometimes|email|max:255|unique:saver_users,email,'.$this->user()->id,
            'password' => ['sometimes', 'confirmed', Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'master_password' => ['sometimes', 'confirmed', Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
        ];
    }
}

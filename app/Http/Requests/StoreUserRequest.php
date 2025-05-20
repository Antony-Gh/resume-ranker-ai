<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:saver_users',
            'email' => 'required|email|max:255|unique:saver_users',
            'password' => ['required', 'confirmed', Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'master_password' => ['required', 'confirmed', Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()],
        ];
    }
}

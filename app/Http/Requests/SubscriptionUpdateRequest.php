<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'plan_id' => 'sometimes|string|exists:plans,id',
            'status' => 'sometimes|string|in:active,paused,cancelled,expired',
            'duration' => 'sometimes|integer|min:1|max:36',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'plan_id' => 'subscription plan',
            'status' => 'subscription status',
            'duration' => 'subscription duration',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plan_id.exists' => 'The selected subscription plan is invalid.',
            'status.in' => 'The selected subscription status is invalid.',
            'duration.min' => 'The subscription duration must be at least 1 month.',
            'duration.max' => 'The subscription duration cannot exceed 36 months.',
        ];
    }
}

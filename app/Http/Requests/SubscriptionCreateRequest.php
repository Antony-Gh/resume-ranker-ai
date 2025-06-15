<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionCreateRequest extends FormRequest
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
            'plan_id' => 'required|string|exists:plans,id',
            'duration' => 'required|integer|min:1|max:36',
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
            'payment_id' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|size:3',
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
            'duration' => 'subscription duration',
            'payment_method' => 'payment method',
            'payment_id' => 'payment ID',
            'amount' => 'payment amount',
            'currency' => 'currency',
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
            'duration.min' => 'The subscription duration must be at least 1 month.',
            'duration.max' => 'The subscription duration cannot exceed 36 months.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'amount.min' => 'The payment amount must be greater than zero.',
        ];
    }
}

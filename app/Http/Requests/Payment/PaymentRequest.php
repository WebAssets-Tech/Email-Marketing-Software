<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'name' => ['bail', 'required', 'string', 'max:100'],
            'email' => ['bail', 'required', 'email', 'max:100'],
            'phone' => ['bail', 'nullable', 'string', 'max:100'],
            'password' => ['sometimes', 'bail', 'required', 'string', 'max:100', 'min:8'],
            'confirm_password' => ['sometimes', 'bail', 'required', 'same:password'],
            'plan_id' => ['bail', 'required', 'integer', 'max:100', 'exists:subscription_plans,id'],
            'method' => ['bail', 'required', 'max:100'],
            'source' => ['bail', 'nullable', 'string'],
        ];
    }
}

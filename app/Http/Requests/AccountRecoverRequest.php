<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRecoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $invalidEmailMessage = 'E-mail inválido.';
        return [
            'email.required' => 'Você precisa informar um e-mail.',
            'email.string' => $invalidEmailMessage,
            'email.email' => $invalidEmailMessage,
            'email.max' => $invalidEmailMessage,
            'email.exists' => $invalidEmailMessage,
        ];
    }
}

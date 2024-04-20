<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
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
            'password' => 'required|string|max:255|confirmed',
            'password_confirmation' => 'required|string|max:255|same:password',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Você deve informar a senha para deletar a conta.',
            'password.string' => 'Senha inválida.',
            'password.max' => 'Senha inválida.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password_confirmation.required' => 'Você deve confirmar a senha para deletar a conta.',
            'password_confirmation.string' => 'Senha inválida.',
            'password_confirmation.max' => 'Senha inválida.',
        ];
    }
}

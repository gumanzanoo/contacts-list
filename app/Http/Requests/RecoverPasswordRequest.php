<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RecoverPasswordRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|exists:users,email',
            'token' => 'required|string|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $errTokenMessage = 'O token informado não é válido.';
        $passMismatchMessage = 'A confirmação da senha não confere.';
        $invalidEmailMessage = 'E-mail inválido.';

        return [
            'token.required' => $errTokenMessage,
            'token.string' => $errTokenMessage,
            'token.max' => $errTokenMessage,
            'token.exists' => $errTokenMessage,
            'email.required' => 'Você precisa informar um e-mail.',
            'email.string' => $invalidEmailMessage,
            'email.email' => $invalidEmailMessage,
            'email.max' => $invalidEmailMessage,
            'email.exists' => $invalidEmailMessage,
            'password.required' => 'Você precisa informar uma senha.',
            'password.string' => 'Senha inválida.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => $passMismatchMessage,
            'password_confirmation.required' => 'A confirmação da senha é obrigatória.',
            'password_confirmation.string' => $passMismatchMessage,
            'password_confirmation.min' => $passMismatchMessage,
        ];
    }
}

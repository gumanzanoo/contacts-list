<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:new_password',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'old_password.required' => 'A senha atual é obrigatória.',
            'old_password.string' => 'A senha atual deve ser uma string.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.string' => 'A nova senha deve ser uma string.',
            'new_password.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
            'confirm_password.required' => 'A confirmação da senha é obrigatória.',
            'confirm_password.string' => 'A confirmação da senha deve ser uma string.',
            'confirm_password.same' => 'A confirmação da senha deve ser igual à nova senha.',
        ];
    }
}

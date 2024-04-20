<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdressRequestByCEP extends FormRequest
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
            'cep' => ['required', 'string', 'min:8', 'max:8']
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
            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma string.',
            'cep.min' => 'O CEP deve ter exatamente 8 caracteres.',
            'cep.max' => 'O CEP deve ter exatamente 8 caracteres.'
        ];
    }
}

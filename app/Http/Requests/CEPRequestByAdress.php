<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CEPRequestByAdress extends FormRequest
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
            'uf' => ['required', 'string', 'max:2'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255']
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
            'uf.required' => 'O estado é obrigatório.',
            'uf.string' => 'O estado deve ser uma string.',
            'uf.max' => 'O estado não pode ter mais de 2 caracteres.',

            'city.required' => 'A cidade é obrigatória.',
            'city.string' => 'A cidade deve ser uma string.',
            'city.max' => 'A cidade não pode ter mais de 100 caracteres.',

            'street.required' => 'A rua é obrigatória.',
            'street.string' => 'A rua deve ser uma string.',
            'street.max' => 'A rua não pode ter mais de 255 caracteres.'
        ];
    }
}

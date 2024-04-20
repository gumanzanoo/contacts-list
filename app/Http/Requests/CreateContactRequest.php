<?php

namespace App\Http\Requests;

use App\Rules\CPFValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'min:11', 'max:11', 'unique:contacts', new CPFValidation()],
            'email' => ['required', 'email', 'max:255', 'unique:contacts'],
            'phone' => ['required', 'string', 'max:20'],
            'cep' => ['required', 'string', 'min:8', 'max:8'],
            'state' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'max:20'],
            'complement' => ['nullable', 'string', 'max:500'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
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
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser uma string.',
            'cpf.min' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.max' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cpf.regex' => 'O formato do CPF está inválido.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'phone.required' => 'O telefone é obrigatório.',
            'phone.string' => 'O telefone deve ser uma string.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.',

            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma string.',
            'cep.min' => 'O CEP deve ter exatamente 8 caracteres.',
            'cep.max' => 'O CEP deve ter exatamente 8 caracteres.',

            'state.required' => 'O estado é obrigatório.',
            'state.string' => 'O estado deve ser uma string.',
            'state.max' => 'O estado não pode ter mais de 100 caracteres.',

            'city.required' => 'A cidade é obrigatória.',
            'city.string' => 'A cidade deve ser uma string.',
            'city.max' => 'A cidade não pode ter mais de 100 caracteres.',

            'street.required' => 'A rua é obrigatória.',
            'street.string' => 'A rua deve ser uma string.',
            'street.max' => 'A rua não pode ter mais de 255 caracteres.',

            'number.required' => 'O número é obrigatório.',
            'number.max' => 'O número não pode ter mais de 20 caracteres.',

            'complement.string' => 'O complemento deve ser uma string.',
            'complement.max' => 'O complemento não pode ter mais de 500 caracteres.',

            'latitude.required' => 'A latitude é obrigatória.',
            'latitude.numeric' => 'A latitude deve ser um número.',

            'longitude.required' => 'A longitude é obrigatória.',
            'longitude.numeric' => 'A longitude deve ser um número.',
        ];
    }
}

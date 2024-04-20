<?php

namespace App\Http\Requests;

use App\Rules\CPFValidation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
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
        $contactId = $this->route('contact');
        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required', 'string', 'min:11', 'max:11',
                Rule::unique('contacts')->ignore($contactId),
                new CPFValidation()
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('contacts')->ignore($contactId)
            ],
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
            'id.required' => 'O id é obrigatório.',
            'id.exists' => 'O id informado não existe.',

            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',

            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser uma string.',
            'cpf.min' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.max' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cpf.regex' => 'O formato do CPF está inválido.',

            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um email válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este email já está cadastrado.',

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

            'number.required' => 'O número é obrigatório e deve ser uma string.',
            'number.max' => 'O número não pode ter mais de 20 caracteres.',
        ];
    }
}

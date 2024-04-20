<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CPFValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) != 11) {
            $fail('O CPF deve ter exatamente 11 caracteres e ser compost apenas por números.');
        }

        if (!ctype_digit($value)) {
            $fail('O CPF deve ser composto apenas por números.');
        }

        if (preg_match('/(\d)\1{10}/', $value)) {
            $fail('O CPF não pode ser composto por uma sequência de números iguais.');
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $value[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($value[$c] != $d) {
                $fail('O CPF é inválido.');
            }
        }
    }
}

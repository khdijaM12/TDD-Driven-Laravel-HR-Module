<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnglishArabicName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!str_contains($value, '/')) {
            $fail('The ' . $attribute . ' must be in the format "English Name / Arabic Name".');
            return;
        }

        [$en, $ar] = explode('/', $value . '/');

        $en = trim($en);
        $ar = trim($ar);

        if ($en === '' || $ar === '') {
            $fail('Both English and Arabic names are required.');
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9\s\-\_]+$/', $en)) {
            $fail('The English name must contain only English letters and numbers.');
            return;
        }

        if (!preg_match('/^[\p{Arabic}\s]+$/u', $ar)) {
            $fail('The Arabic name must contain only Arabic letters.');
            return;
        }
    }
}

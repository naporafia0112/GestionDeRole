<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TexteLisible implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim(preg_replace('/\s+/', ' ', $value)); // Nettoyer

        if (strlen($value) < 10) {
            $fail("Le champ :attribute est trop court pour être considéré comme un texte valide.");
            return;
        }

        if (preg_match('/^(.)\1+$/', $value)) {
            $fail("Le champ :attribute ne doit pas contenir un seul caractère répété.");
            return;
        }

        if (str_word_count($value) < 2) {
            $fail("Le champ :attribute doit contenir au moins deux mots.");
            return;
        }

        if (preg_match('/^[^a-zA-Z0-9]+$/', $value)) {
            $fail("Le champ :attribute ne doit pas contenir uniquement des caractères spéciaux.");
            return;
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;
class DatePublicationValide implements ValidationRule
{
    protected bool $publie;

    public function __construct(bool $publie)
    {
        $this->publie = $publie;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $today = Carbon::today();
        $date = Carbon::parse($value)->startOfDay();

        if ($this->publie) {
            // Si on publie directement → la date doit être aujourd'hui
            if (!$date->equalTo($today)) {
                $fail("La date de publication doit être aujourd’hui si l’offre est publiée maintenant.");
            }
        } else {
            // Sinon → la date doit être aujourd’hui ou plus tard
            if ($date->lt($today)) {
                $fail("La date de publication ne peut pas être antérieure à aujourd’hui.");
            }
        }
    }
}


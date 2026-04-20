<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class EndAfterStart implements ValidationRule
{
    public function __construct(protected mixed $startValue)
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start = Carbon::parse($this->startValue);
        $end = Carbon::parse($value);

        if ($end->lessThanOrEqualTo($start)) {
            $fail('L’heure de fin doit être strictement supérieure à l’heure de début.');
        }
    }
}

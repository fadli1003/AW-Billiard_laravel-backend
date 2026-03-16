<?php

namespace App\Rules;

use App\Models\operationalHours;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsOpenAt implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
    public function passes(string $attribute, mixed $value)
    {
      $day = now()->dayOfWeek();
      $config = operationalHours::where('day', $day)->first();

      if(!$config || !$config->is_open) return false;

      return $value >= $config->open_time && $value <= $config->close_time;
    }
}

<?php

namespace App\Rules;

use App\Models\AwProfile;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JamOperasional implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      // return [
      //   '' => ''
      // ]
    }
}

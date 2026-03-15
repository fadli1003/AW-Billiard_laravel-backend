<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Meja;
use App\Enums\TableStatus;

class TableIsAvailable implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      $table = Meja::find($value);

      if (!$table || $table->status === TableStatus::available) {
        $fail('Sorry, The table you choosed is not available.');
      }
      
    }
}

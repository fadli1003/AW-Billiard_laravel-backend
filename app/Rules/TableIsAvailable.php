<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Enums\TableStatus;
use App\Models\Table;

class TableIsAvailable implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      $table = Table::find($value);

      if (!$table || !$table->status !== TableStatus::available) {
        $fail('Sorry, The table you choose is not available during these hours.');
      }

    }
}

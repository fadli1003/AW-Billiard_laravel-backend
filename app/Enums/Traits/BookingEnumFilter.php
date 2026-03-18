<?php

namespace App\Enums\Traits;

trait BookingEnumFilter
{
  public static function except(array $excluded): array {
    return array_filter(self::cases(), function ($case) use ($excluded) {
      return !in_array($case, $excluded);
    });
  }
  public static function valuesExcept(array $excluded): array {
    return array_map(fn($case) => $case->value, self::except($excluded));
  }

  public static function exceptValues(array $excluded): array
  {
    return collect(self::cases())
        ->reject(fn($case) => in_array($case, $excluded))
        ->map(fn($case) => $case->value)
        ->toArray();
  }
}

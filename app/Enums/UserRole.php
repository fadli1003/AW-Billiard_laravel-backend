<?php

namespace App\Enums;
use App\Enums\Traits\HasValues;

enum UserRole: String
{
  use HasValues;

  case owner = 'owner';
  case admin = 'admin';
  case staff = 'staff';
  case coach = 'coach';
  case manager = 'manager';
  case customer = 'customer';

  // public static function values(): array {
  //   return array_column(self::cases(), 'value');
  // }
}

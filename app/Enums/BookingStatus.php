<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum BookingStatus : String
{
  use HasValues;

  case pending = 'pending';
  case confirmed = 'confirmed';
  case completed = 'completed';
  case rescheduling = 'rescheduling';
  case cancelled = 'cancelled';
}

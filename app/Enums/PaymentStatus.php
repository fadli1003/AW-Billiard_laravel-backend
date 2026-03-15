<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum PaymentStatus: String
{
  use HasValues;

  case pending = 'pending';
  case paid = 'paid';
  case expired = 'expired';
  case failed = 'failed';
}


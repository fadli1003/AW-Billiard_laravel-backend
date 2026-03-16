<?php

namespace App\Enums;

use App\Enums\Traits\HasValues;

enum TableStatus: String
{
  use HasValues;

  case available = 'available';
  case maintenance = 'maintenance';
  case playing = 'playing';
}

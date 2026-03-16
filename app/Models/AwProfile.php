<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class AwProfile extends Model
{
  protected $appends = ['day_name'];

  protected function isOpen(): Attribute {
    return Attribute::make(
      get: function () {
        $now = now()->toTimeString();
        $day = now()->dayOfWeek()->first();

        return AwProfile::where('day', $day)
                        ->where('is_open', true)
                        ->where('open_time', '<=', $now)
                        ->where('close_time', '>=', $now)->exists();
      }
    );
  }

  protected function dayName(): Attribute {
    return Attribute::make(
      get: fn() => Carbon::getDays()[$this->day]
    );
  }
}

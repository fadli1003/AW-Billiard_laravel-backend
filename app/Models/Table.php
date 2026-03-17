<?php

namespace App\Models;

use App\Enums\TableStatus;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  protected $fillable = [
    'table_code',
    'type',
    'price_perhour',
    'status'
  ];

  protected $casts = [
    'status' => TableStatus::class
  ];

  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
}

<?php

namespace App\Models;

use App\Enums\TableStatus;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
  protected $fillable = [
    'no_meja',
    'type',
    'harga_perjam',
    'status'
  ];

  protected $casts = [
    'status' => TableStatus::class
  ];

  public function booking()
  {
    return $this->belongsTo(Booking::class);
  }
}

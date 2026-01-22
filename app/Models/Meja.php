<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
  protected $fillable = [
    'no_meja',
    'type',
    'harga_perjam',
    'status'
  ];

  public function booking()
  {
    return $this->belongsTo(Booking::class);
  }
}

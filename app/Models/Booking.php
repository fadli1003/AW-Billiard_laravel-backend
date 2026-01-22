<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
  protected $fillable = [
    'user_id',
    'meja_id',
    'jam_mulai',
    'jam_selesai',
    'durasi',
    'total_harga',
    'status',
  ];

  protected $casts = [
    'jam_mulai' => 'datetime',
    'jam_selesai' => 'datetime'
  ];

  public function users()
  {
    return $this->hasMany(User::class);
  }

  public function mejas()
  {
    return $this->hasMany(Meja::class);
  }

  public function payment()
  {
    return $this->belongsTo(Payment::class);
  }
}

<?php

namespace App\Models;

// use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function Pest\Laravel\get;

class Booking extends Model
{
  use SoftDeletes;
  public $jadwal;
  protected $appends = ['jadwal'];
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

  protected function jadwal(): Attribute
  {
    return Attribute::make(
      get: function (mixed $value, array $attributes) {
        if(!$attributes['jam_mulai'] || !$attributes['jam_selesai']){
          return null;
        }

        $jam_mulai = Carbon::parse($attributes['jam_mulai']);
        $jam_selesai = Carbon::parse($attributes['jam_selesai']);

        return $jam_mulai->translatedFormat('d F') . ', ' . $jam_mulai->format('H:i') . '-' . $jam_selesai->format('H:i');
      }
    );
  }

  // public function getJadwalAttribute(){
  //   $jam_mulai = Carbon::parse($this->jam_mulai);
  //   $jam_selesai = Carbon::parse($this->jam_selesai);
  //   return $this->jadwal = $jam_mulai->format('H:i').''.$jam_selesai;
  // }
}

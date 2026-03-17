<?php

namespace App\Models;

// use Attribute;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function Pest\Laravel\get;

class Booking extends Model
{
  use SoftDeletes;

  public $schedule;
  protected $appends = ['schedule'];
  protected $fillable = [
    'user_id',
    'table_id',
    'start_time',
    'end_time',
    'duration',
    'total_price',
    'status',
  ];

  protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'status' => BookingStatus::class
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function table()
  {
    return $this->belongsTo(Table::class);
  }

  public function payment()
  {
    return $this->belongsTo(Payment::class);
  }

  // protected function jadwal(): Attribute
  // {
  //   return Attribute::make(
  //     get: function (mixed $value, array $attributes) {
  //       if(!$attributes['jam_mulai'] || !$attributes['end_time']){
  //         return null;
  //       }

  //       $jam_mulai = Carbon::parse($attributes['jam_mulai']);
  //       $end_time = Carbon::parse($attributes['end_time']);

  //       return $jam_mulai->translatedFormat('d F') . ', ' . $jam_mulai->format('H:i') . ' s/d ' . $end_time->format('H:i');
  //     }
  //   );
  // }

  // public function getJadwalAttribute(){
  //   $jam_mulai = Carbon::parse($this->jam_mulai);
  //   $end_time = Carbon::parse($this->end_time);
  //   return $this->jadwal = $jam_mulai->format('H:i').''.$end_time;
  // }
}

<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
  use SoftDeletes;

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

  protected function schedule(): Attribute
  {
    return Attribute::make(
      get: function (mixed $value, array $attributes) {
        if (empty($this->start_time) || empty($this->end_time)) {
          return null;
        }

        //tidak dibutuhkan karena sudah didefinisikan di $casts
        // $start_time = Carbon::parse($attributes['start_time']);
        // $end_time = Carbon::parse($attributes['end_time']);

        return $this->start_time->translatedFormat('d F') . ', ' .
               $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
      }
    );
  }
}

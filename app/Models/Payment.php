<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'booking_id',
    'order_id',
    'amount_paid',
    'payment_type',
    'payment_method',
    'status',
    'snap_token'
  ];

  protected $casts = [
    'status' => PaymentStatus::class
  ];

  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
  public function users()
  {
    return $this->hasManyThrough(User::class, Booking::class);
  }
}

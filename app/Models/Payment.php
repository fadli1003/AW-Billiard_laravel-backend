<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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

  public function booking() : HasOne
  {
    return $this->hasOne(Booking::class);
  }
  public function user(): HasOneThrough
  {
    return $this->hasOneThrough(User::class, Booking::class);
  }
}

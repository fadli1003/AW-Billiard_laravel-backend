<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'order_id',
        'jumlah_bayar',
        'payment_type',
        'payment_method',
        'status',
        'snap_token'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

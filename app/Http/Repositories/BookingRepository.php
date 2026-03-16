<?php

namespace App\Http\Repositories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\Date;

class BookingRepository
{
  public function getAll(array $fields) {
    return Booking::select($fields)->latest()->paginate(30);
  }

  public function getById(string $id, array $fields) {
    return Booking::select($fields)->findOrFail($id);
  }

  public function create(array $data) {
    return Booking::create($data);
  }

  public function update(string $id, array $data){
    $booking = Booking::findOrFail($id);
    $booking->update($data);
    return $booking;
  }

  public function delete(string $id){
    $booking = Booking::findOrFail($id);
    $booking->delete($id);
  }

  public function isBooked(string $meja_id, Date $jam_mulai, Date $jam_selesai)
  {
    return Booking::where('meja_id', $meja_id)
                  ->where('status', BookingStatus::confirmed)
                  ->whereTimeOverlap($jam_mulai, $jam_selesai)
                  ->exists();
  }
}

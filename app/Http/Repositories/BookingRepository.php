<?php

namespace App\Http\Repositories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Meja;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingRepository
{
  public function getAll(array $fields)
  {
    return Booking::with('meja', 'user')->select($fields)->latest()->paginate(30);
  }

  public function getById(string $id, array $fields)
  {
    return Booking::with('meja', 'user')->select($fields)->findOrFail($id);
  }

  public function create(array $data)
  {
    return Booking::create($data);
  }

  public function update(string $id, array $data)
  {
    $booking = Booking::findOrFail($id);
    return $booking->update($data);
  }

  public function delete(string $id)
  {
    $booking = Booking::findOrFail($id);
    return $booking->delete($id);
  }

  // public function isBooked(string $meja_id, Date $jam_mulai, Date $jam_selesai)
  // {
  //   return Booking::where('meja_id', $meja_id)
  //     ->where('status', BookingStatus::confirmed)
  //     ->whereTimeOverlap($jam_mulai, $jam_selesai)
  //     ->exists();
  // }

  //Overlap check
  public function isBooked(string $meja_id, Date $jam_mulai, Date $jam_selesai)
  {
    //lock table
    $meja = Meja::where('id', $meja_id)->lockForUpdate()->first();

    $isBooked = Booking::where('meja_id', $meja->id) //apa jadinya jika menambahkan tanda seru (!) didepan model
                      ->whereIn('status', ['pending', 'confirmed', 'paid'])
                      // ->whereIn('status', BookingStatus::values())
                      ->where(fn($q) => $q->where('jam_mulai', '<', $jam_mulai)
                                          ->where('jam_selesai', '>', $jam_selesai))
                      ->exists();
    if ($isBooked) {
      throw ValidationException::withMessages([
        'meja_id' => 'This table already booked'
      ]);
    }

    return;
  }
}

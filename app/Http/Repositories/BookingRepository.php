<?php

namespace App\Http\Repositories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Table;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingRepository
{
  public function getAll(array $fields)
  {
    return Booking::with('table', 'user')->select($fields)->latest()->paginate(30);
  }

  public function getById(string $id, array $fields)
  {
    return Booking::with('table', 'user')->select($fields)->findOrFail($id);
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

  // public function isBooked(string $table_id, Date $start_time, Date $end_time)
  // {
  //   return Booking::where('table_id', $table_id)
  //     ->where('status', BookingStatus::confirmed)
  //     ->whereTimeOverlap($start_time, $end_time)
  //     ->exists();
  // }

  //Overlap check
  public function isBooked(string $table_id, Date $start_time, Date $end_time)
  {
    //lock table
    $table = Table::where('id', $table_id)->lockForUpdate()->first();

    $isBooked = Booking::where('table_id', $table->id) //apa jadinya jika menambahkan tanda seru (!) didepan model
                      ->whereIn('status', ['pending', 'confirmed', 'paid'])
                      // ->whereIn('status', BookingStatus::values())
                      ->where(fn($q) => $q->where('start_time', '<', $start_time)
                                          ->where('end_time', '>', $end_time))
                      ->exists();
    if ($isBooked) {
      throw ValidationException::withMessages([
        'table_id' => 'This table already booked'
      ]);
    }

    return;
  }
}

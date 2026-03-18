<?php

namespace App\Http\Repositories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingRepository
{
  public function getAll(array $fields)
  {
    return Booking::with('table', 'user')->select($fields)->latest()->paginate();
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

  //Overlap check
  public function isBooked(string $table_id, string $start_time, string $end_time)
  {
    //lock table
    $table = Table::where('id', $table_id)->lockForUpdate()->first();

    $isBooked = Booking::where('table_id', $table->id)
                      ->whereIn('status', BookingStatus::exceptValues([
                        BookingStatus::cancelled,
                        BookingStatus::expired
                      ]))
                      ->where(fn($q) => $q->where('start_time', '<', $end_time)
                                          ->where('end_time', '>', $start_time))
                      ->exists();
    if ($isBooked) {
      throw ValidationException::withMessages([
        'table_id' => 'This table already booked'
      ]);
    }

    return;
  }
}

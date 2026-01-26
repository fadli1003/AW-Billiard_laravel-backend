<?php

namespace App\Http\Repositories;

use App\Models\Booking;

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
}

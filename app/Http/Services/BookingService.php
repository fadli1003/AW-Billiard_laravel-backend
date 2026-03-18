<?php

namespace App\Http\Services;

use App\Http\Repositories\BookingRepository;
use App\Models\Meja;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
  private $booking_repo;
  public function __construct(BookingRepository $booking_repo)
  {
    $this->booking_repo = $booking_repo;
  }

  public function getAll(array $fields)
  {
    return $this->booking_repo->getAll($fields);
  }

  public function getById(string $id, array $fields)
  {
    return $this->booking_repo->getById($id, $fields);
  }

  public function create(array $data)
  {
    return $this->booking_repo->create($data);
  }

  public function update(string $id, array $data)
  {
    return $this->booking_repo->update($id, $data);
  }

  public function delete(string $id)
  {
    return $this->booking_repo->delete($id);
  }

  //with validator
  public function placeBooking(array $data)
  {
    return DB::transaction(function () use ($data) {

      $this->booking_repo->isBooked(
        $data['table_id'],
        $data['start_time'],
        $data['end_time']
      );

      return $this->create([
        ...$data,
        'status' => 'pending',
      ]);
    });
  }

  public function createBooking(array $data)
  {
    DB::begintransaction();

    try{
      $this->booking_repo->isBooked(
        $data['table_id'],
        $data['start_time'],
        $data['end_time']
      );

      $booking = $this->create([
        ...$data,
        'status' => 'pending',
      ]);
      DB::commit();
      
      return $booking;

    } catch (\Exception $e) {
      DB::rollBack();
      throw $e;
    }

  }

  public function updateBooking(string $booking_id, array $data)
  {
    return DB::transaction(function () use ($data, $booking_id) {

      $this->booking_repo->isBooked(
        $data['table_id'],
        $data['start_time'],
        $data['end_time']
      );

      return $this->update($booking_id, $data);
    });
  }
}

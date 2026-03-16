<?php

namespace App\Http\Services;

use App\Http\Repositories\BookingRepository;
use Illuminate\Support\Facades\Date;

class BookingService {
  private $booking_repo;
  public function __construct(BookingRepository $booking_repo)
  {
    $this->booking_repo = $booking_repo;
  }
  public function getAll(array $fields){
    return $this->booking_repo->getAll($fields);
  }
  public function getById(string $id, array $fields){
    return $this->booking_repo->getById($id, $fields);
  }
  public function create(array $data){
    return $this->booking_repo->create($data);
  }
  public function update(string $id, array $data){
    return $this->booking_repo->update($id, $data);
  }
  public function delete(string $id){
    return $this->booking_repo->delete($id);
  }

  public function isBooked(string $meja_id, Date $jam_mulai, Date $jam_selesai)
  {
    return $this->booking_repo->isBooked($meja_id, $jam_mulai, $jam_selesai);
  }
}

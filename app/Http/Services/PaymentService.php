<?php

namespace App\Http\Services;

use App\Http\Repositories\PaymentRepository;
use App\Models\Payment;
use App\Models\User;

class PaymentService {
  private PaymentRepository $payment_repo;
  public function __construct(PaymentRepository $payment_repo)
  {
    $this->payment_repo = $payment_repo;
  }
  public function getAll(array $fields){
    return $this->payment_repo->getAll($fields);
  }
  public function getById(string $id, array $fields){
    return $this->payment_repo->getById($id, $fields);
  }
  public function getUserPayments(string $userId, array $fields){
    // return $this->payment_repo->getUserPayments($userId, $fields);

    return $this->payment_repo->getUserPayments($userId, $fields, ['booking']);
  }
  public function update(string $id, array $data){
    return $this->payment_repo->update($id, $data);
  }
  public function delete(string $id){
    return $this->payment_repo->delete($id);
  }

  public function getFilteredPayments(?string $search = null, int $perPage = 15)
  {
    $cleanSearch = $search ? trim($search) : null;
    return $this->payment_repo->getPaginatedWithSearch($cleanSearch, $perPage);
  }
}

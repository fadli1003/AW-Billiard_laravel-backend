<?php

namespace App\Http\Services;

use App\Http\Repositories\PaymentRepository;

class PaymentService {
  private $payment_repo;
  public function __construct(PaymentRepository $payment_repo)
  {
    $this->payment_repo = $payment_repo;
  }
  public function getAll($fields){
    return $this->payment_repo->getAll($fields);
  }
  public function getById($id, $fields){
    return $this->payment_repo->getById($id, $fields);
  }
  public function getUserPayments(string $userId, array $fields){
    return $this->payment_repo->getUserPayments($userId, $fields);
  }
  public function update($id, $data){
    return $this->payment_repo->update($id, $data);
  }
  public function delete($id){
    return $this->payment_repo->delete($id);
  }
}

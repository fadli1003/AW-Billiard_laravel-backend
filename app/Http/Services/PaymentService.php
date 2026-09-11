<?php

namespace App\Http\Services;

use App\Http\Repositories\PaymentRepository;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentService {
  private PaymentRepository $payment_repo;
  public function __construct(PaymentRepository $payment_repo)
  {
    $this->payment_repo = $payment_repo;
  }
  public function getAll(Request $request, int $perpage = 15,  array $fields, array $relations){
    return $this->payment_repo->getAll( $request, $perpage, $fields, $relations );
  }
  public function getById(string $id, array $fields){
    return $this->payment_repo->getById($id, $fields);
  }
  public function getUserPayments(string $userId, array $fields, array $relations){
    // return $this->payment_repo->getUserPayments($userId, $fields);

    return $this->payment_repo->getUserPayments($userId, $fields, $relations);
  }
  public function update(string $id, array $data){
    return $this->payment_repo->update($id, $data);
  }
  public function delete(string $id){
    return $this->payment_repo->delete($id);
  }

  // public function getFilteredPayments(?string $search = null, int $perPage = 15)
  // {
  //   $cleanSearch = $search ? trim($search) : null;
  //   return $this->payment_repo->getPaginatedWithSearch($cleanSearch, $perPage);
  // }
}

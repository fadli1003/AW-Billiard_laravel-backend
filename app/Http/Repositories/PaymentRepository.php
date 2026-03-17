<?php
namespace App\Http\Repositories;

use App\Models\Payment;

class PaymentRepository{

  public function getAll(array $fields){
    return Payment::select($fields)->latest()->paginate(30);
  }

  public function getById(string $id, array $fields){
    return Payment::select($fields)->findOrFail($id);
  }

  public function getUserPayments(string $userId, array $fields){
    return Payment::select($fields)->where('user_id', $userId);
  }

  public function create(array $data){
    return Payment::create($data);
  }

  public function update(string $id, array $data){
    $payment = Payment::findOrFail($id);
    $payment->update($data);
    return $payment;
  }
  
  public function delete(string $id){
    $payment = Payment::findOrFail($id);
    $payment->delete($id);
  }
}

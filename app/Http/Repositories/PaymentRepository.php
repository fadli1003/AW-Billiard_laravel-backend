<?php

namespace App\Http\Repositories;

use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use function Pest\Laravel\json;

class PaymentRepository
{

  public function getAll(array $fields)
  {
    return Payment::select($fields)->latest()->paginate(30);
  }

  public function getById(string $id, array $fields)
  {
    return Payment::select($fields)->findOrFail($id);
  }

  public function getUserPayments(string $userId, array $fields, array $relations)
  {
    $user = User::find($userId);

    if (!$user) {
      return response()->json([
        'message' => 'No user were selected.'
      ]);
    }

    return $user->payments()->select($fields)->with($relations)->latest();
  }

  public function create(array $data)
  {
    return Payment::create($data);
  }

  public function update(string $id, array $data)
  {
    $payment = Payment::findOrFail($id);
    $payment->update($data);
    return $payment;
  }

  public function delete(string $id)
  {
    $payment = Payment::findOrFail($id);
    $payment->delete($id);
  }

  public function getPaginatedWithSearch(?string $search = null, int $perPage = 15): LengthAwarePaginator
  {
    return Payment::query()
      ->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
          $q->where('id', 'like', "%{$search}%")
            ->orWhere('booking_id', 'like', "%{$search}%");
        });
      })
      ->latest()
      ->paginate($perPage)
      // Memastikan query string `?search=...` tidak hilang saat klik tombol next page
      ->withQueryString();
  }
}

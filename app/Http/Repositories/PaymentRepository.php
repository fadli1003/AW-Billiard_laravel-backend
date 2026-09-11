<?php

namespace App\Http\Repositories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class PaymentRepository
{

  public function getALl(Request $request, int $perPage = 15, array $fields = ['*'], array $relations = []): LengthAwarePaginator
  {
    return Payment::query()
      ->select($fields)
      ->with($relations)
      ->when($request->filled('search'), function ($query) use ($request) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
          $q->where('order_id', 'like', "%{$search}%")
            ->orWhere('booking_id', 'like', "%{$search}%");
        });
      })
      ->when($request->filled('status'), function ($query) use ($request) {
        $query->where('status', $request->status);
      })
      ->when($request->filled('payment_type'), function ($query) use ($request) {
        $query->where('payment_type', $request->payment_type);
      })
      ->when($request->filled('weekly') && is_array($request->weekly), function ($query) use ($request) {
        $query->whereBetween('created_at', $request->weekly);
      })
      ->latest()
      ->paginate($perPage)
      ->withQueryString();
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
    return $payment->update($data);
  }

  public function delete(string $id)
  {
    $payment = Payment::findOrFail($id);
    $payment->delete($id);
  }

  public function getBookingPayment(string $booking_id, array $fields)
  {
    return Payment::where('booking_id', $booking_id)->select($fields)->with(['booking', 'user'])->first();
  }
}

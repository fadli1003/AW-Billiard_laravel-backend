<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingPaymentRequest;
use App\Http\Resources\BookingPaymentResource;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class BookingPaymentController extends Controller
{
  public function index()
  {
    $userPayment = User::with('payment')->get();
    if(isEmpty($userPayment)){
      return response([
        'message' => 'User booking payment not found.'
      ]);
    }
    try{
      return response()->json([
        'data' => new BookingPaymentResource($userPayment)
      ]);
    } catch (Exception $e){
      return response()->json([
        'message' => 'Sometings wrong happend',
        'error' => $e->getMessage()
      ]);
    }
  }

  public function create()
  {
    //
  }

  public function store(BookingPaymentRequest $request)
  {
    //
  }

  public function show(Payment $payment)
  {
    //
  }

  public function edit(Payment $payment)
  {
    //
  }

  public function update(BookingPaymentRequest $request, Payment $payment)
  {
    //
  }

  public function destroy(Payment $payment)
  {
    //
  }
}

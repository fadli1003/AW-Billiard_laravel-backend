<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\BookingPaymentRequest;
use App\Http\Resources\BookingPaymentResource;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Services\PaymentService;
use Illuminate\Http\Request;

class BookingPaymentController extends Controller
{
  public PaymentService $paymentService;

  public function __construct(PaymentService $paymentService) {
    $this->paymentService = $paymentService;
  }

  public function index()
  {
      
  }

  public function store(BookingPaymentRequest $request)
  {
    $data = $request->validated();
    try{
      $payment = Payment::create($data);
      return response()->json([
        'success' => 'Payment succeed.',
        'data' => new BookingPaymentResource($payment)
      ]);
    } catch (Exception $e){
      return response()->json([
        'message' => 'Somethings went wrong.',
        'error' => $e->getMessage()
      ]);
    }

  }

  public function update(BookingPaymentRequest $request, Payment $payment)
  {
    $data = $request->validated();
    try{
      if(!Auth::user()->hasRole('admin') || Auth::user()->id !== $payment->user()->id()){
        return  abort(403, 'Access forbiden.');
      }

      $newPayment = $payment->update($data);

      return response()->json([
        'success' => 'Payment information updated successfully.',
        'data' => new BookingPaymentResource($newPayment)
      ]);
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Sometings went wrong.',
        'error' => $e->getMessage()
      ]);
    }
  }

  public function destroy(Payment $payment)
  {
    DB::beginTransaction();
    try {
      if(!Auth::user()->hasRole('admin')){
        return abort(403, 'Access forbidden.');
      }

      $payment->delete();
      DB::commit();

      return response()->json([
        'success' => 'Payment information deleted successfully.',
      ]);

    } catch (Exception $e) {
      DB::callback();
      return response()->json([
        'message' => 'Sometings went wrong.',
        'error' => $e->getMessage()
      ]);
    }
  }
}

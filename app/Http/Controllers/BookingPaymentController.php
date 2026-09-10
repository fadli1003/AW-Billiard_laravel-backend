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

  public function index(?string $search = '')
  {
    try{
      $allPayment = $this->paymentService->getFilteredPayments($search);
      $userPayment = $this->paymentService->getUserPayments(auth()->id(), ['*']);


      if($userPayment->isEmpty() || $allPayment->isEmpty()){
        return response([
          'message' => 'User does not have any transactions .'
          ]);
      }

      if(Auth::user()->role(UserRole::admin || UserRole::manager)){
        return response()->json([
          'data' => new BookingPaymentResource($allPayment)
        ]);
      } elseif (auth()->user()->role === UserRole::customer) {
        return response()->json([
          'data' => new BookingPaymentResource($userPayment),
        ]);
      } else {
        return response()->json([
          'message' => 'Access forbidden.'
        ]);
      }
    } catch (Exception $e){
      return response()->json([
        'message' => 'Sometings wrong happend',
        'error' => $e->getMessage()
      ]);
    }
  }

  public function store(BookingPaymentRequest $request)
  {
    $data = $request->validated();
    try{
      $payment = Payment::create($data);
      return response()->json([
        'message' => 'Payment succeed.',
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
      if(!Auth::user()->role('admin') || Auth::user()->id !== $payment->users){
        return  abort(403, 'Access forbiden.');
      }
      $newPayment = $payment->update($data);
      return response()->json([
        'message' => 'Payment information updated successfully.',
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
      if(!Auth::user()->role('admin')){
        return abort(403, 'Access forbidden.');
      }
      $payment->delete();
      DB::commit();
      return response()->json([
        'message' => 'Payment information deleted successfully.',
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

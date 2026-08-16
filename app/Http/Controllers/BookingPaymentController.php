<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingPaymentRequest;
use App\Http\Resources\BookingPaymentResource;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingPaymentController extends Controller
{
  public function index()
  {
    try{
      $allPayment = User::with('payments')->get();
      $userPayment = Auth::user()->with('payments')->get();
      if($userPayment->isEmpty() || $allPayment->isEmpty()){
        return response([
          'message' => 'User booking payment not found.'
          ]);
          }
      if(!Auth::user()->role('admin')){
        return response()->json([
          'data' => new BookingPaymentResource($allPayment)
        ]);
      }
      return response()->json([
        'data' => new BookingPaymentResource($userPayment),
      ]);
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

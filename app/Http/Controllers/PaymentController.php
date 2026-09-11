<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingPaymentResource;
use App\Http\Services\PaymentService;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  protected PaymentService $paymentService;
   public function __construct(PaymentService $paymentService)
   {
    $this->paymentService = $paymentService;
   }
    public function index(Request $request)
    {
      // $query = Payment::query();

      // if( $request->status) {
      //   $query->where('invoice-code','like','%{$request->status}%');
      // }

      // if( $request->payment_type ) {
      //   $query->where('booking_id','like','%{$request->payment_type}%');
      // }

      // if( $request->search) {
      //   $query->where('order_id', 'like', '%'.$request->search.'%')
      //         ->orWhere('booking_id','like','%'.$request->search.'%');
      // // }

      // $payments = $query->with(['user:name,email,phone', 'booking:id,table_id,'])->paginate(10);
      $fields = ['booking_id', 'order_id', 'amount_paid', 'payment_type', 'payment_method', 'status'];
      $payments = $this->paymentService->getAll($request, $request->perpage, $fields, ['booking', ]);

        return response()->json([
          'data' => BookingPaymentResource::collection($payments),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Services\BookingService;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;
use function PHPUnit\Framework\isEmpty;

class BookingController extends Controller
{
  private BookingService $bookingService;
  public function __construct(BookingService $bookingService)
  {
    $this->bookingService =  $bookingService;
  }

  public function index()
  {
    $fields = ['id', 'table_id', 'user_id', 'start_time', 'end_time', 'duration', 'status', 'total_price'];
    $bookings = $this->bookingService->getAll($fields);
    if(isEmpty($bookings)){
      return response()->json([
        'message' => 'No bookings was found.'
      ]);
    }
    return BookingResource::collection($bookings);
  }

  /*
  public function store(BookingRequest $request)
  {

    $end_time = $request->start_time->addHour($request->durasi);

    $isBooked = Booking::where('meja_id', $request->meja_id)
                      ->where('status', BookingStatus::confirmed)
                      ->whereTimeOverlap($request->start_time, $end_time)
                      ->exists();

    if($isBooked){
      return response()->json([
        'message' => 'Meja sudah dipesan di jam tersebut!'
      ], 422);
    }

    $data = $request->validated();
    $data['end_time'] = $end_time;
    DB::beginTransaction();
    try {
      $booking = Booking::create($data);

      DB::commit();
      return response()->json([
        'message' => 'Booking created successfully.',
        'data' => new BookingResource($booking)
      ], 201);
    }catch(\Exception $e) {
      DB::rollBack();

      return response()->json([
        'message' => 'Terjadi Kesalahan',
        'error' => $e->getMessage(),
        'data' => null
      ], 500);
    }
  }
  */

  public function store(BookingRequest $request)
  {
    $data = $request->validated();

    // if(Auth::user()->role === 'admin' && $data['cash'] === true) {
    //   $data['status'] = 'confirmed';
    // }

    try {
      $start_time = Carbon::parse($data['start_time']);
      $data['end_time'] = $start_time->copy()->addHour($data['duration']);

      $booking = $this->bookingService->placeBooking($data);
      return response()->json([
        'message' => 'Booking created successfully.',
        'data' => new BookingResource($booking)
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Booking proccess failed.',
        'error' => $e->getMessage()
      ], 422);
    }
  }

  public function show(Booking $booking)
  {
    $booking->load('table:id,table_code', 'user:id,name,phone');
    if($booking){
      return response()->json([
        'message' => 'Booking not found.',
        'data' => $booking
      ]);
    } else {
      return new BookingResource($booking);
    }
  }

  public function update(BookingRequest $request, Booking $booking)
  {
    $data = $request->validated();
    $end_time = $data['start_time']->addHour($data['duration']);
    $data['end_time'] = $end_time;

    // DB::beginTransaction();
    try {
      $this->bookingService->update($booking, $data);

      // DB::commit();
      return response()->json([
        'message' => 'Booking updated successfully.',
        'data' => new BookingResource($booking),
      ], 200);
    } catch (\Exception $e) {
      DB::rollBack();

      return response()->json([
        'message' => 'Something wrong happend',
        'error' => $e->getMessage(),
        'data' => null
      ], 422);
    }
  }

  public function destroy(Booking $booking)
  {
    try{
      $booking->delete();
      return response()->json(['message' => 'Booking deleted successfully.']);
    }catch (Exception $e){
      return response()->json([
        'message' => 'Sometings wrong happend',
        'error' => $e->getMessage()
      ]);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Booking;
use App\Http\Services\BookingService;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

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
    if($bookings->isEmpty()){
      return response()->json([
        'message' => 'No bookings was found.'
      ]);
    }
    return BookingResource::collection($bookings);
  }

  public function userBookings()
  {
    try{
      $fields = ['id', 'table_id', 'start_time', 'end_time', 'duration', 'status', 'total_price'];
      $userBookings = $this->bookingService->getByUserId(auth()->id(), $fields);
      // $userBookings = Booking::where('user_id', auth()->id())->with('table:table_code')->latest()->get($fields);
      if(auth()->user() !== $userBookings->user_id){
        abort(403, 'Access Forbidden.');
      }
      return response()->json([
        'data' => new BookingResource($userBookings) //gak passing ke BookingResource, padahal di dump and die ada fields nya
      ]);
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Terjadi kesalahan.',
        'error' => $e->getMessage(),
        'code' => $e->getCode()
      ]);
    }
  }

  public function store(BookingRequest $request)
  {
    $data = $request->validated();
    DB::beginTransaction();
    try {
      if(Auth::user()->hasRole(UserRole::admin) && $data['cash'] === true) {
        $data['status'] = 'confirmed';
      }
      $start_time = Carbon::parse($data['start_time']);
      $data['end_time'] = $start_time->copy()->addHour($data['duration']);

      $booking = $this->bookingService->placeBooking($data);
      DB::commit();
      return response()->json([
        'message' => 'Booking created successfully.',
        'data' => new BookingResource($booking)
      ], 201);
    } catch (Exception $e) {
      DB::rollBack();
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

    DB::beginTransaction();
    try {
      $this->bookingService->update($booking, $data);

      DB::commit();
      return response()->json([
        'message' => 'Booking updated successfully.',
        'data' => new BookingResource($booking),
      ]);
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

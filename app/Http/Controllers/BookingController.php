<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Services\BookingService;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
  private BookingService $bookingService;
  public function __construct(BookingService $bookingService)
  {
    $this->bookingService =  $bookingService;
  }

  public function index()
  {
    $fields = ['id', 'user_id', 'meja_id', 'status', 'total_harga', 'jam_mulai', 'jam_selesai'];
    $bookings = $this->bookingService->getAll($fields);
    // dd($bookings);
    return BookingResource::collection($bookings);
  }

  public function store(BookingRequest $request)
  {
    // $exists = Booking::where('jam_mulai', $request->jam_mulai)
    //                   ->where(function($query) use ($request){
    //                       $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
    //                             ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
    //                     })
    //                   ->exists();

    $jam_selesai = $request->jam_mulai->addHour($request->durasi);

    $isBooked = Booking::where('meja_id', $request->meja_id)
                      ->where('status', BookingStatus::confirmed)
                      ->whereTimeOverlap($request->jam_mulai, $jam_selesai)
                      ->exists();

    if($isBooked){
      return response()->json([
        'message' => 'Meja sudah dipesan di jam tersebut!'
      ], 422);
    }

    $data = $request->validated();
    $data['jam_selesai'] = $jam_selesai;
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

  public function show(Booking $booking)
  {
    return response()->json(new BookingResource($booking));
  }

  public function update(BookingRequest $request, Booking $booking)
  {
    $jam_selesai = $request->jam_mulai + $request->durasi;
    $isBooked = Booking::where('meja_id', $request->meja_id)
                      ->where('status', BookingStatus::confirmed)
                      ->whereTimeOverlap($request->jam_mulai, $jam_selesai)
                      ->exists();
    if($isBooked){
      return response()->json([
        'message' => 'Meja sudah dipesan di jam tersebut!'
      ], 422);
    }

    $data = $request->validated();
    $data['jam_selesai'] = $jam_selesai;

    // if(Auth::user()->role === 'admin' && $data['cash'] === true) {
    //   $data['status'] = 'confirmed';
    // }

    DB::beginTransaction();
    try {
      $booking->update($data);

      DB::commit();
      return response()->json([
        'message' => 'Booking created successfully.',
        'data' => new BookingResource($booking),
      ], 200);
    }catch(\Exception $e) {
      DB::rollBack();

      return response()->json([
        'message' => 'Terjadi Kesalahan',
        'error' => $e->getMessage(),
        'data' => null
      ], 500);
    }
  }

  public function destroy(Booking $booking)
  {
    $booking->delete();
    return response()->json(['message' => 'Booking deleted successfully.'], 204);
  }
}

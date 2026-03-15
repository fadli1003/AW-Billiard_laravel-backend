<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\TableStatus;
use App\Http\Requests\BookingRequest;
use App\Http\Services\BookingService;
use App\Models\Booking;
use App\Models\Meja;
use Illuminate\Http\Request;

class BookingController extends Controller
{
  private BookingService $bookingService;
  public function __construct(BookingService $bookingService)
  {
    $this->bookingService =  $bookingService;
  }

  public function index()
  {
    $fields = ['id', 'user_id', 'meja_id', 'jam_main', 'status'];
    return response()->json($this->bookingService->getAll($fields));
  }

  public function store(BookingRequest $request)
  {
    $meja = Meja::findOrFail($request->meja_id);

    if($meja->status !== TableStatus::available){
      $msg = 'Meja sedang digunakan';
      if( $meja->status === TableStatus::maintenance ){
        $msg = 'Meja dalam perawatan';
      }
      return response()->json([
        'message' => $msg
      ], 422);
    }

    $isBooked = Booking::where('meja_id', $request->meja_id)
                        ->where('status', BookingStatus::confirmed)
                        ->whereTimeOverLap($request->jam_mulai, $request->jam_selesai)
                        ->exists();

    // $exists = Booking::where('jam_mulai', $request->jam_mulai)
    //                   ->where(function($query) use ($request){
    //                       $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
    //                             ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
    //                     })
    //                   ->exists();

    if($isBooked){
      return response()->json([
        'message' => 'Meja sudah dipesan di jam tersebut!'
      ], 422);
    }

    $jadwal_main = $request->tggl . ', ' . $request->jam_mulai . '-' . $request->jam_selesai;


  }

  public function show(Booking $booking)
  {
    //
  }

  public function update(Request $request, Booking $booking)
  {
    //
  }

  public function destroy(Booking $booking)
  {
    //
  }
}

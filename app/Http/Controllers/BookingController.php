<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Services\BookingService;
use App\Models\Booking;
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

    $exists = Booking::where('jam_mulai', $request->jam_mulai)->where(
                        function($query) use ($request){
                          $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                                ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
                        })
                      ->exists();

    if($exists){
      return response()->json([
        'message' => 'Jam tersebut sudah dipesan!'
      ]);
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

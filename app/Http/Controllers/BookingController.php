<?php

namespace App\Http\Controllers;

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
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $fields = ['id', 'user_id', 'meja_id', 'jam_main', 'status'];
    return response()->json($this->bookingService->getAll($fields));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $jadwal_main = $request->tggl . ' ' . $request->jam;
  }

  /**
   * Display the specified resource.
   */
  public function show(Booking $booking)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Booking $booking)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Booking $booking)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Booking $booking)
  {
    //
  }
}

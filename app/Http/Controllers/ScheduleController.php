<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
  public function index(Request $request = null)
  {
    $selectedDate = $request ? $request->input('date') : now();

    $tables = Table::all();

    $query = Booking::query()->with('table:table_code', 'user:name,email');

    if($selectedDate) {
      $query->where('booking_date', $selectedDate)->where('status', 'comfirmed');
    }

    $bookings = $query->latest()->paginate();

    foreach ($bookings as $booking) {
      if (is_string($booking->schedule_details)) {
        $booking->schedule_details = json_decode($booking->schedule_details, true);
      }
    }

    $timeSlots = [];
    for ($hour = 7; $hour <= 22; $hour++) {
      $timeSlots[] = sprintf('%02d:00:00', $hour);
    }

    return response()->json([
      'data' => [$bookings, $timeSlots, $tables],
    ]);
  }

  public function store(Request $request)
  {
    //
  }

  public function show(string $id)
  {
    //
  }

  public function update(Request $request, string $id)
  {
    //
  }

  public function destroy(string $id)
  {
    //
  }
}

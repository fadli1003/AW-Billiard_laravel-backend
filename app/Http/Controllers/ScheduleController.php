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
    $selectedDate = $request ? $request->input('date') : null;

    $tables = Table::all();

    $query = Booking::query();

    if($selectedDate) {
      $query->where('booking_date', $selectedDate)->where('status', 'comfirmed');
    }

    $bookings = $query->get();

    foreach ($bookings as $booking) {
      if (is_string($booking->schedule_details)) {
        $booking->schedule_details = json_decode($booking->schedule_details, true);
      }
    }

    $filteredBookings = $bookings->filter(function ($booking) use ($selectedDate) {
      if ($booking->booking_type === 'member' && $booking->valid_until) {
        return Carbon::now()->lessThanOrEqualTo($booking->valid_until);
      }
      return true;
    });

    $timeSlots = [];
    for ($hour = 7; $hour <= 22; $hour++) {
      $timeSlots[] = sprintf('%02d:00:00', $hour);
    }
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

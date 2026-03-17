<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    public function index(User $user)
    {
      $bookings = $user->bookings()->paginate(10);
      return new BookingResource($bookings);
    }

    public function store(Request $request, User $user)
    {
      $user->bookings()->create($request->validated());
      return response()->json([
        'message' => 'Booking created successfully.',
      ], 201);
    }

    public function show(User $user, string $id)
    {
      $booking = $user->bookings()->findOrFail($id);
      return new BookingResource($booking);
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

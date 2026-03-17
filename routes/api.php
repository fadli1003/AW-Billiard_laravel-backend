<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware(['guest'])->group(function () {
  Route::get('/jadwal', [JadwalController::class, 'index']);

  //Webhooks midtrans
  Route::post('/payments/notification', [BookingPaymentController::class, 'paymentNotification']);
  Route::get('/snap-token', [BookingPaymentController::class, 'getToken']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
  //Users
  Route::get('/users/{id}/bookings', [BookingController::class, 'users.bookings']);

  Route::apiResource('meja', MejaController::class);
  // Route::apiResource('payments', PaymentController::class)->withTrashed();

  //Nested resources
  Route::apiResource('bookings.payment', BookingPaymentController::class)->withTrashed();
});
  Route::apiResource('bookings', BookingController::class)->withTrashed();
  Route::apiResource('users.bookings', UserBookingController::class)->withTrashed();

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});


Route::middleware(['guest'])->group(function () {
  Route::get('/schedules', [ScheduleController::class, 'index']);

  //Webhooks midtrans
  Route::post('/payments/notification', [BookingPaymentController::class, 'paymentNotification']);
  Route::get('/snap-token', [BookingPaymentController::class, 'getToken']);
});

Route::middleware(['auth:sanctum', 'verified', 'throttle:30,1'])->group(function () {
  //Users
  Route::get('/users/{userId}/bookings', [BookingController::class, 'userBookings']);
  Route::get('/users', [UserController::class, 'index'])->can('manage users');

  Route::apiResource('tables', TableController::class);
  // Route::apiResource('payments', PaymentController::class)->withTrashed();

  //Nested resources
  });
  Route::apiResource('bookings.payment', BookingPaymentController::class)->withTrashed();
  Route::apiResource('bookings', BookingController::class)->withTrashed();
  Route::apiResource('profiles', UserController::class);

  Route::get('/payments', [PaymentController::class, 'index']);

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/users', function (Request $request) {
  return $request->user();
});

Route::middleware(['guest'])->group(function () {
  Route::get('/jadwal', [JadwalController::class, 'index']);
});

Route::middleware(['auth:sanctum'])->group(function () {
  Route::apiResource('meja', MejaController::class);
  Route::apiResource('bookings', BookingController::class);
  Route::apiResource('payments', PaymentController::class);

  //Users
  Route::get('/users/{id}/bookings', [BookingController::class, 'users.bookings']);

  //Bookings
  Route::get('/bookings/{id}/payment', [PaymentController::class, 'getPayment']);
  Route::post('/bookings/{id}/payments', [PaymentController::class, 'store']);
});

//Webhooks midtrans
Route::post('/payments/notification', [PaymentController::class, 'paymentNotification']);
Route::get('/snap-token', [PaymentController::class, 'getToken']);

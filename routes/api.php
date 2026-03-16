<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware(['guest'])->group(function () {
  Route::get('/jadwal', [JadwalController::class, 'index']);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
  Route::apiResource('meja', MejaController::class);
  Route::apiResource('bookings', BookingController::class)->withTrashed();
  Route::apiResource('payments', PaymentController::class)->withTrashed();

  //Users
  Route::get('/users/{id}/bookings', [BookingController::class, 'users.bookings']);

  //Bookings
  Route::get('/bookings/{id}/payment', [PaymentController::class, 'getPayment'])->middleware('role:admin,user');
  Route::post('/bookings/{id}/payment', [PaymentController::class, 'store'])->middleware('role:admin,user');
});

//Webhooks midtrans
Route::post('/payments/notification', [PaymentController::class, 'paymentNotification']);
Route::get('/snap-token', [PaymentController::class, 'getToken']);

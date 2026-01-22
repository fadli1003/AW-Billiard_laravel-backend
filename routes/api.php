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

Route::middleware(['guest'])->group(function(){
    Route::get('/jadwal', [JadwalController::class, 'index']);
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('meja', MejaController::class);
    Route::apiResource('booking', BookingController::class);
    Route::apiResource('payment', PaymentController::class);
});

Route::post('/payment', [PaymentController::class, 'store']);
Route::get('/snap-token', [PaymentController::class, 'getToken']);

<?php

namespace App\Http\Controllers;

use App\Http\Requests\MejaRequest;
use App\Http\Resources\MejaResource;
use App\Http\Services\MejaService;
use App\Models\Booking;
use App\Models\Meja;

class MejaController extends Controller
{
  private MejaService $meja_service;

  public function index()
  {
    $bookingsInfo = Booking::where('status', 'confirmed')->get('jadwal');

    return response()->json([
      'data' => new MejaResource(Meja::all()),
      'bookingsInfo' => $bookingsInfo
    ]);
  }

  public function store(MejaRequest $request)
  {
    $data = $request->validated();
    try{
      $meja = Meja::create($data);
      return response()->json([
        'message' => 'Table added successfully.',
        'data' => $meja
      ]);
    } catch (\Exception $e){
      return response()->json([
        'message' => 'Somethings wrong happend.',
        'error' => $e->getMessage(),
        'data' => null
      ], 400);
    }
  }

  public function show(Meja $meja)
  {
    return response()->json(new MejaResource($meja));
  }

  public function update(MejaRequest $request, Meja $meja)
  {
    $data = $request->validated();
    try{
      $meja->update($data);
      return response()->json([
        'message' => 'Table updated successfully.',
        'data' => new MejaResource($meja),
      ]);
    } catch (\Exception $e){
      return response()->json([
        'message' => 'Somethings wrong happend',
        'error' => $e->getMessage(),
        'data' => null
      ], 400);
    }
  }

  public function destroy(Meja $meja)
  {
    //
  }
}

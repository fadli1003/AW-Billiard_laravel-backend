<?php

namespace App\Http\Controllers;

use App\Http\Requests\TableRequest;
use App\Http\Resources\TableResource;
use App\Http\Services\TableService;
use App\Models\Booking;
use App\Models\Table;

class TableController extends Controller
{
  private TableService $table_service;

  public function index()
  {
    $bookingsInfo = Booking::where('status', 'confirmed')->get('schedule');

    return response()->json([
      'data' => new TableResource(Table::all()),
      'bookingsInfo' => $bookingsInfo
    ]);
  }

  public function store(TableRequest $request)
  {
    $data = $request->validated();
    if($data['type'] === 'standard') {
      $data['table_code'] = 'std-'.$request->table_code;
    } else {
      $data['type'] = 'vip-'.$request->table_code;
    }
    try{
      $table = Table::create($data);
      return response()->json([
        'message' => 'Table added successfully.',
        'data' => $table
      ]);
    } catch (\Exception $e){
      return response()->json([
        'message' => 'Somethings wrong happend.',
        'error' => $e->getMessage(),
        'data' => null
      ], 400);
    }
  }

  public function show(Table $table)
  {
    return response()->json(new TableResource($table));
  }

  public function update(TableRequest $request, Table $table)
  {
    $data = $request->validated();
    try{
      $table->update($data);
      return response()->json([
        'message' => 'Table updated successfully.',
        'data' => new TableResource($table),
      ]);
    } catch (\Exception $e){
      return response()->json([
        'message' => 'Somethings wrong happend',
        'error' => $e->getMessage(),
        'data' => null
      ], 400);
    }
  }

  public function destroy(Table $table)
  {
    try {
      $table->delete();

      return response()->json([
        'message' => 'Table deleted successfully.'
      ]);
    } catch (\Exception $e){
      return response()->json([
        'message' => 'Somethings wrong happend',
        'error' => $e->getMessage()
      ], 400);
    }
  }
}

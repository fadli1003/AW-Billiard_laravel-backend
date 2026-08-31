<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function index($q = '')
  {
    $today = Carbon::today()->toDateString();
    try{
      if($q) {
        return UserResource::collection(User::select('*')->where('name', 'like', '%'. $q .'%')->orWhere('email', 'like', '%'. $q .'%')->get());
      }
      return UserResource::collection(User::select('*')->where('role', '!=', 'admin')
              // ->with(['booking' => function ($q) use ($today){
              //   $q->where('start_time', '>=', $today );
              //   }])
              ->get()
        );
    } catch(\Exception $e){
      return response()->json([
        'message' => "Terjadi kesalahan.",
        'error' => $e->getMessage()
      ]);
    }
  }

  public function store(RegisterRequest $request)
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

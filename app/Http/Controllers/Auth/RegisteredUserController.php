<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(RegisterRequest $request): JsonResponse
  {
    $data = $request->validated();
    DB::beginTransaction();
    try{
      $data['password'] = bcrypt($data['password']);
      $data['role'] = UserRole::customer;
      $user = User::create($data);

      // $user = new User($data);
      // $user->save(); sama aja tapi nambah satu baris

      $token = $user->createToken('auth_token')->plainTextToken;
      $request->session()->regenerate();

      event(new Registered($user));
      Auth::login($user);
      DB::commit();
      return response()->json([
        'message' => 'Account registered successfully.',
        'token' => $token
      ], 201);

    } catch (\Exception $e){
      DB::rollBack();
      return response()->json([
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
      ]);
    }
  }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// /** @property \App\Models\User $user */

class AuthenticatedSessionController extends Controller
{
  /**
   * Handle an incoming authentication request.
   */
  public function store(LoginRequest $request): JsonResponse
  {
    try {
      $request->authenticate();
      $user = Auth::user();

      $data = [
        'user' => new UserResource($user),
      ];

      if($request->wantsJson() && !$request->isFromFrontend()
        ||$request->has('device_name'))
      {
        $user->tokens->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        $data["token"] = $token;
      }

      $request->session()->regenerate();
      return response()->json([
        'message' => 'Login Success!',
        'data' => $data
      ], 200);
    } catch(ValidationException $e) {
      throw $e;
    } catch (Exception $e) {
      return response()->json([
        'message' => 'Terjadi Kesalahan saat login!',
        'error' => $e->getMessage(),
      ], 500);
    }

    // $request->authenticate();

    // $token = $request->user()->createToken('auth_token')->plainTextToken;
    // $request->session()->regenerate();

    // return response()->json([
    //   'message' => 'You are successfully logged in',
    //   'token' => $token
    // ]);
  }

  /**
   * Destroy an authenticated session.
   */
  public function destroy(Request $request): JsonResponse
  {
    try{
      $user = Auth::user();
      if($user->currentAccessToken()){
        $user->currentAccessToken()->delete();
      }else{
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
      }
      return response()->json([], 204);
    } catch (\Exception $e) {
      return response()->json([
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}

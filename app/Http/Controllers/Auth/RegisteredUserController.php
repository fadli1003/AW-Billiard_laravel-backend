<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): JsonResponse
  {
    DB::beginTransaction();
    try{
      $rules = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
        'password' => ['required', 'confirmed', Rules\Password::min(6)->numbers()->symbols()->mixedCase()],
      ]);

      $messages = [
        'password' => 'Passwordnya yang kuat ya bro, minimal 6 karakter, ada angka, simbol, huruf besar dan kecil.',
        '*.required' => 'Wajib diisi, jangan dikosongin bro.'
      ];

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->string('password')),
        'phone' => $request->phone
      ]);

      $validator = Validator::make($request->all(), $rules, $messages);
      if($validator->fails()){
        return response()->json([
          'message' => 'validation errors.',
          'errors' => $validator->errors()
        ], 422);
      }
      DB::commit();
      
      $token = $user->createToken('auth_token')->plainTextToken;
      $request->session()->regenerate();
      event(new Registered($user));

      Auth::login($user);

      return response()->json(['message' => 'Account registered successfully.', 'token' => $token], 201);
    }catch (Exception $e){
      return response()->json([
        'message' => 'Terjadi kesalahan',
        'error' => $e->getMessage()
      ]);
    }
  }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
  public function store(Request $request): Response
  {
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
      return response([
        'message' => 'validation errors.',
        'errors' => $validator->errors()
      ], 422);
    }

    event(new Registered($user));

    Auth::login($user);

    return response(['message' => 'Account registered successfully.'], 201);
  }
}

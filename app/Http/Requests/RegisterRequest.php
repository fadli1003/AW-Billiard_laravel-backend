<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:50',
      'email' => 'required|string|lowercase|email|max:50|'.
                  Rule::unique(User::class)->ignore($this->user?->id)
                ,
      'phone' => ['nullable', 'string', 'max:15',
                  Rule::unique(User::class)->ignore($this->user?->id)
                ],
      'photo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
      'address' => 'nullable|string|max:100',
      'password' => 'required|confirmed|' . Password::min(6)->numbers()->symbols()->mixedCase(),
    ];
  }

  public function messages()
  {
    return [
      'email.unique' => 'Akun ini udah terdaftar bro!',
      'phone,unique' => 'No ponsel ini sudah terdaftar bro!',
      'password.confirmed' => 'Password konfirmasinya beda cuk!',
      'password' => 'Passwordnya yang kuat ya bro, minimal 6 karakter, ada angka, simbol, huruf besar dan kecil.',
      '*.required' => 'Wajib diisi, jangan dikosongin bro.'
    ];
  }
}

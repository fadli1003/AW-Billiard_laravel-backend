<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use App\Enums\TableStatus;
use App\Rules\TableIsAvailable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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
      'user_id' => 'required|exists:users,id',
      'meja_id' => ['required', 'exists:mejas,id', new TableIsAvailable],
      'status' => ['required', Rule::in(BookingStatus::values())],
      'jam_mulai' => 'required|date|after:now',
      'durasi' => 'required|integer|min:1|max:5',
      'total_harga' => 'required|numeric'
    ];
  }
}

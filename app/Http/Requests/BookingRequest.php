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
      'table_id' => ['required', 'exists:tables,id', new TableIsAvailable],
      'start_time' => 'required|date|after:now',
      'duration' => 'required|integer|min:1|max:5',
      'amount_paid' => 'required|numeric|min:10000',
      'total_price' => 'required|numeric|min:20000',
      // 'cash' => 'required|boolean'
    ];
  }
}

<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use App\Enums\TableStatus;
use App\Models\Table;
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
    $price = Table::where('id', $this->table_id)->value('price_perhour') ?? 0;
    // $min_amount = 0.5 * $price;

    return [
      'user_id' => 'required|exists:users,id',
      'table_id' => ['required', 'exists:tables,id', new TableIsAvailable],
      'start_time' => 'required|date|after:now',
      'duration' => 'required|integer|min:1|max:5',
      'amount_paid' => ['required', Rule::numeric()->min($price * 0.5)],
      'total_price' => ['required', Rule::numeric()->min($price)],
      // 'cash' => 'required|boolean'
    ];
  }
}

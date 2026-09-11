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
    // return auth()->check();
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $price_perhour = Table::where('id', $this->table_id)->value('price_perhour') ?? 0;
    $price = $price_perhour * $this->duration;
    $cash = $this->input('cash', false);

    return [
      'user_id' => 'required|exists:users,id',
      'table_id' => ['required', 'exists:tables,id', new TableIsAvailable],
      'start_time' => 'required|date|after:now',
      'duration' => 'required|integer|min:1|max:5',
      'amount_paid' => ['required', Rule::numeric()->min($cash === true ? $price : $price * 0.5)->max($price)],
      'total_price' => ['required', 'numeric', Rule::in($price)],
      'cash' => 'required|boolean'
    ];
  }

  public function messages(): array
  {
    $price_perhour = Table::where('id', $this->table_id)->value('price_perhour') ?? 0;
    $price = $price_perhour * $this->duration;

    return [
      // 'user_id.required' => 'User ID is required.',
      // 'user_id.exists' => 'User ID does not exist.',
      // 'table_id.required' => 'Table ID is required.',
      // 'table_id.exists' => 'Table ID does not exist.',
      // 'start_time.required' => 'Start time is required.',
      // 'start_time.date' => 'Start time must be a valid date.',
      // 'start_time.after' => 'Start time must be after the current time.',
      // 'duration.required' => 'Duration is required.',
      // 'duration.integer' => 'Duration must be an integer.',
      'duration.min' => 'Duration must be at least 1 hour.',
      'duration.max' => 'Duration cannot exceed 5 hours.',
      'amount_paid.min' => $this->input('cash', false) === true ? "Amount paid must be at least the total price ({$price} IDR)." : "Amount paid must be at least 50% of the total price.",
      'total_price.in' => "Total price must be {$price} IDR ({$this->duration} hours × {$price_perhour} IDR/hour).",
    ];
  }
}

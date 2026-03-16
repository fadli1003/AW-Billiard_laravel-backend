<?php

namespace App\Http\Requests;

use App\Enums\TableStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MejaRequest extends FormRequest
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
      'tableNo' => 'required|numeric',
      'type' => 'required|in:biasa,VIP',
      'pricePerhour' => 'required|numeric|min:20000',
      'status' => ['required', Rule::in(TableStatus::values())],
    ];
  }
}

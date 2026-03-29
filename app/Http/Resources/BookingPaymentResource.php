<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingPaymentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'booking_id' => new BookingResource($this->booking_id),
      'order_id' => $this->order_id,
      'amount_paid' => $this->amount_paid,
      'payment_type' => $this->payment_type,
      'payment_method' => $this->payment_method,
      'status' => $this->status,
    ];
  }
}

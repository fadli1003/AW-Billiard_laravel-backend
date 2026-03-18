<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      // 'table_info' => new TableResource($this->whenLoaded('table')),
      'table_code' => $this->table->table_code,
      'booking_info' => new UserResource($this->whenLoaded('user')),
      'duration' => $this->duration,
      'total_price' => $this->total_price,
      'status' => $this->status,
      // 'start_time' => $this->start_time,
      // 'end_time' => $this->end_time,
      'schedule' => $this->schedule
    ];
  }
}

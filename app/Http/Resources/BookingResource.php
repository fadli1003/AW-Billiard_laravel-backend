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
      'meja_id' => new MejaResource($this->whenLoaded('meja')),
      'user_id' => new UserResource($this->whenLoaded('user')),
      'type' => $this->type,
      'durasi' => $this->durasi,
      'total_harga' => $this->total_harga
    ];
  }
}

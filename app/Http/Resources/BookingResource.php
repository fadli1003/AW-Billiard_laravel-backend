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
      'meja_id' => new MejaResource($this->meja_id),
      'user_id' => new UserResource($this->user_id),
      'type' => $this->type,
      'jadwal' => $this->jadwal,
      'durasi' => $this->durasi,
      'total_harga' => $this->total_harga
    ];
  }
}

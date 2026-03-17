<?php

namespace App\Http\Repositories;

use App\Models\Meja;

class MejaRepository
{
  public function __construct()
  {
    //
  }

  public function getAll(array $fields)
  {
    return Meja::select($fields);
  }

  public function lockMeja(int $meja_id)
  {
    return Meja::where('id', $meja_id)->lockForUpdate()->first();
  }
}

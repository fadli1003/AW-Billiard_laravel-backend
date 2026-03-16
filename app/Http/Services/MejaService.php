<?php

namespace App\Http\Services;

use App\Http\Repositories\MejaRepository;
use App\Models\Meja;
use App\Models\User;

class MejaService
{
  private MejaRepository $meja_repo;
  public function __construct(MejaRepository $meja_repo)
  {
    $this->meja_repo = $meja_repo;
  }

  public function getAll(array $fields)
  {
    return $this->meja_repo->getAll($fields);
  }

  public function getFinalPrice(Object $userId, float $discount, float $price)
  {
    if ($userId->role === 'member') {
      return $price * $discount;
    }
    return $price;
  }
}

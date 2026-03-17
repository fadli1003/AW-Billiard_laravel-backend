<?php

namespace App\Http\Services;

use App\Http\Repositories\TableRepository;
use App\Models\User;

class TableService
{
  private TableRepository $table_repo;
  public function __construct(TableRepository $table_repo)
  {
    $this->table_repo = $table_repo;
  }

  public function getAll(array $fields)
  {
    return $this->table_repo->getAll($fields);
  }

  public function getFinalPrice(Object $user_id, float $discount, float $price)
  {
    if ($user_id->role === 'member') {
      return $price * $discount;
    }
    return $price;
  }
}

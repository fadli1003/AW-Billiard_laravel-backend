<?php

namespace App\Http\Repositories;

use App\Models\Meja;
use App\Models\Table;

class TableRepository
{
  public function __construct()
  {
    //
  }

  public function getAll(array $fields)
  {
    return Table::select($fields);
  }

  public function lockTable(int $table_id)
  {
    return Table::where('id', $table_id)->lockForUpdate()->first();
  }
}

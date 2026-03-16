<?php

namespace App\Http\Repositories;

use App\Models\Branch;

class BranchRepository
{
    public function __construct()
    {
      //
    }
    public function getAll(array $fields)
    {
      return Branch::select($fields)->latest()->paginate(5);
    }
}

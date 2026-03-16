<?php

namespace App\Http\Services;

use App\Http\Repositories\BranchRepository;

class BranchService
{
    private $branch_repo;
    public function __construct(BranchRepository $branchRepo)
    {
      $this->branch_repo = $branchRepo;
    }

    public function getAll(array $fields)
    {
      return $this->branch_repo->getAll($fields);
    }
}

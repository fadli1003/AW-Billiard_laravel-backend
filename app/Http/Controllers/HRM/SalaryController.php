<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Http\Requests\StoreSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;

class SalaryController extends Controller
{
  public function index()
  {
    //
  }

  public function store(StoreSalaryRequest $request)
  {
    //
  }

  public function show(Salary $salary)
  {
    //
  }

  public function update(UpdateSalaryRequest $request, Salary $salary)
  {
    //
  }

  public function destroy(Salary $salary)
  {
    //
  }
}

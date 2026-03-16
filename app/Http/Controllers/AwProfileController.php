<?php

namespace App\Http\Controllers;

use App\Models\AwProfile;
use App\Http\Requests\StoreAwProfileRequest;
use App\Http\Requests\UpdateAwProfileRequest;
use App\Http\Resources\AwProfileResource;

class AwProfileController extends Controller
{
  public function index()
  {
    return response()->json(new AwProfileResource(AwProfile::all()));
  }

  public function store(StoreAwProfileRequest $request)
  {
    //
  }

  public function show(AwProfile $awProfile)
  {
    //
  }

  public function update(UpdateAwProfileRequest $request, AwProfile $awProfile)
  {
    //
  }

  public function destroy(AwProfile $awProfile)
  {
    //
  }
}

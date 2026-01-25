<?php

namespace App\Enums;

enum UserRole: String
{
  case owner = 'owner';
  case admin = 'admin';
  case user = 'user';
  case staff = 'staff';
  case coach = 'coach';
}

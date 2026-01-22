<?php

namespace App\Enums;

enum UserRole: String
{
  case admin = 'admin';
  case user = 'user';
  case coach = 'coach';
  case karyawan = 'karyawan';
}

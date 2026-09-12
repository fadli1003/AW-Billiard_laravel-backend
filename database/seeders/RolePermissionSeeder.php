<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
  {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $permissions = [
      'create bookings',
      'edit own bookings',
      'edit all bookings',
      'delete own bookings',
      'edit payments',
      'delete payments',

      'delete all bookings',
      'publish bookings',
      'manage users',
      'manage roles',
      'access reports',
      'access payments',
      'access costumer delivery',

      'access payroll'
    ];

    foreach ($permissions as $p) {
      Permission::create(['name' => $p]);
    }

    //create roles and assign permission
    $adminRole = Role::create(['name' => UserRole::admin]);
    $adminRole->syncPermissions(Permission::all());

    $managerRole = Role::create(['name' => UserRole::manager]);
    $managerRole->givePermissionTo([
      'manage users',
      'edit all bookings',
      'access reports',
      'access payments',
    ]);

    $cashierRole = Role::create(['name' => UserRole::cashier]);
    $cashierRole->givePermissionTo([
      'create bookings',
      'edit own bookings',
      'delete own bookings',
    ]);

    $staffRole = Role::create(['name' => UserRole::staff]);
    $staffRole->givePermissionTo([
      'access costumer delivery',
      'access payroll'
    ]);

    $costumerRole = Role::create(['name' => UserRole::customer]);
    $costumerRole->givePermissionTo([
      'create bookings',
      'edit own bookings',
      'delete own bookings',
    ]);

  }
}


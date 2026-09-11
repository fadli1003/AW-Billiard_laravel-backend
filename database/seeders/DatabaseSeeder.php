<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\TableStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Table;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call(RolePermissionSeeder::class);
    $this->call(BookingPaymentSeeder::class);
    $admin = User::create([
      'name' => 'Admin',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
    ]);
    $admin->assignRole([UserRole::admin]);

    $staffs = User::factory(5)->create([
      'password' => Hash::make('password'),
    ]);
    $staffs->each(function (User $staff) {
      $staff->assignRole(UserRole::staff);
    });

    $customers = User::factory(5)->create([
      'password'=> Hash::make('password'),
    ]);
    $customers->each(function (User $customer) {
      $customer->assignRole(UserRole::customer);
    });


    Table::create([
      'table_code' => "std-1",
      'type' => "standard",
      'price_perhour' => 30000,
      'status' => TableStatus::available->value,
    ]);

    Booking::create([
      'user_id' => 1,
      'table_id' => 1,
      'start_time' => now(),
      'end_time' => now()->addHour(),
      'duration' => 1,
      'total_price' => 30000,
      'status' => BookingStatus::confirmed
    ]);


  }
}

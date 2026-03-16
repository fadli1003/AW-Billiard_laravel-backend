<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\TableStatus;
use App\Models\Booking;
use App\Models\Meja;
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
    // User::factory(10)->create();

    // User::create([
    //   'name' => 'Admin',
    //   'email' => 'admin@example.com',
    //   'password' => Hash::make('password'),
    //   'role' => 'admin'
    // ]);
    Meja::create([
      'no_meja' => 1,
      'type' => "biasa",
      'harga_perjam' => 30000,
      'status' => TableStatus::available->value,
    ]);

    Booking::create([
      'user_id' => 1,
      'meja_id' => 1,
      'jam_mulai' => now(),
      'jam_selesai' => now()->addHour(),
      'durasi' => 1,
      'total_harga' => 30000,
      'status' => BookingStatus::confirmed
    ]);
  }
}

<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use Illuminate\Console\Command;

class ExpirePendingBookings extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'bookings:expire-pending';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Checking booking pending status expiration.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $expiredTime = now()->subMinutes(5); // Toleransi 5 menit

    $affected = \App\Models\Booking::where('status', 'pending')
      ->where('created_at', '<', $expiredTime)
      ->update(['status' => BookingStatus::expired->value]);

    $this->info("The $affected pending status booking, status updated to expired successfully.");
  }
}

<?php

use App\Enums\BookingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('bookings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->foreignId('table_id')->constrained()->cascadeOnDelete();
      $table->dateTime('start_time');
      $table->dateTime('end_time');
      $table->integer('duration');
      // $table->boolean('cash')->default(false);
      $table->decimal('total_price', 10, 0);
      $table->enum('status', BookingStatus::values())->default('pending');
      $table->index(['table_id', 'status', 'start_time', 'end_time'], 'idx_booking_availability');
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bookings');
  }
};

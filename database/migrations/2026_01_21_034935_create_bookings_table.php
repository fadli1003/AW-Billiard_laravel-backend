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
      $table->foreignId('meja_id')->constrained()->cascadeOnDelete();
      $table->dateTime('jam_mulai');
      $table->dateTime('jam_selesai');
      $table->integer('durasi');
      // $table->boolean('cash')->default(false);
      $table->decimal('total_harga', 10, 0);
      $table->enum('status', BookingStatus::values())->default('pending');
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

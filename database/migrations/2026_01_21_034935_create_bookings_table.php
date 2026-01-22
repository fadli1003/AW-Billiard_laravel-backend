<?php

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
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->foreignId('meja_id')->constrained()->onDelete('cascade');
      $table->dateTime('jam_mulai');
      $table->dateTime('jam_selesai');
      $table->integer('durasi');
      $table->decimal('total_harga', 10, 0);
      $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled']);
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

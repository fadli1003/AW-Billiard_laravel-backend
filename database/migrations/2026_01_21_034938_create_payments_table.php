<?php

use App\Enums\PaymentStatus;
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
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->foreignId('booking_id')->constrained()->onDelete('cascade');
      $table->string('order_id');
      $table->decimal('amount_paid', 10, 0);
      $table->enum('payment_type', ['dp', 'full', 'settlement']);
      $table->string('payment_method')->nullable();
      $table->enum('status', PaymentStatus::values())->default('pending');
      $table->string('snap_token')->nullable();
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};

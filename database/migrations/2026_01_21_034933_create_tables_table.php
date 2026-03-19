<?php

use App\Enums\TableStatus;
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
    Schema::create('tables', function (Blueprint $table) {
      $table->id();
      $table->string('table_code', 10)->index('table_code');
      $table->enum('type', ['standard', 'VIP']);
      $table->decimal('price_perhour', 10, 0);
      $table->enum('status', TableStatus::values())->default('available');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('tables');
  }
};

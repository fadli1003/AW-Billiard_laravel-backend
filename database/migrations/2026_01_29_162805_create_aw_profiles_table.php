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
        Schema::create('aw_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('day')->unsigned();
            $table->boolean('is_open')->default(true);
            $table->time('open_time');
            $table->time('close_time');
            $table->timestamps();

            $table->unique('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aw_profiles');
    }
};

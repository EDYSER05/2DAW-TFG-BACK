<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shift_id')->constrained('shifts');
            $table->foreignId('day_id')->constrained('days');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_shifts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_log_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_log_id')->constrained('time_logs');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('issue_type_id')->constrained('issue_types');

            $table->text('description')->nullable();
            $table->boolean('resolved')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_log_issues');
    }
};

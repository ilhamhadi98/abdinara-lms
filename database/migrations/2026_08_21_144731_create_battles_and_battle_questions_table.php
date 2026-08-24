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
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 16)->unique();
            $table->foreignId('player1_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('player2_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('player2_name')->nullable();
            $table->boolean('is_bot')->default(false);
            $table->enum('status', ['waiting', 'active', 'finished', 'cancelled'])->default('waiting');
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('winner_name')->nullable();
            $table->integer('player1_score')->default(0);
            $table->integer('player2_score')->default(0);
            $table->timestamps();
        });

        Schema::create('battle_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->constrained('battles')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battle_questions');
        Schema::dropIfExists('battles');
    }
};

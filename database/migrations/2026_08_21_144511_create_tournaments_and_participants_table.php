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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('edition_number')->default(1);
            $table->foreignId('tryout_id')->constrained('tryouts')->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_active')->default(true);
            $table->text('prizes_description')->nullable();
            $table->timestamps();
        });

        Schema::create('tournament_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained('tournaments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tryout_session_id')->nullable()->constrained('tryout_sessions')->nullOnDelete();
            $table->integer('score')->default(0);
            $table->integer('duration_seconds')->nullable();
            $table->unsignedInteger('rank_position')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamps();

            $table->unique(['tournament_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_participants');
        Schema::dropIfExists('tournaments');
    }
};

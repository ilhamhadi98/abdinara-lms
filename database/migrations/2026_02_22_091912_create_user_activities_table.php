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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // e.g., 'login', 'tryout', 'module'
            $table->date('date'); // To easily aggregate per day
            $table->integer('count')->default(1); // To aggregate multiple actions per day
            $table->timestamps();

            // Unique constraint to enforce upsert capabilities
            $table->unique(['user_id', 'action', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};

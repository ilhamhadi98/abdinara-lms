<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('question_id');
            $table->char('selected_answer', 1)->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->unsignedSmallInteger('time_spent')->default(0);
            $table->timestamps();

            $table->index('session_id', 'ta_session_idx');
            $table->index('question_id', 'ta_question_idx');
            $table->unique(['session_id', 'question_id'], 'ta_session_question_unique');

            $table->foreign('session_id', 'ta_session_fk')
                  ->references('id')->on('tryout_sessions')->cascadeOnDelete();
            $table->foreign('question_id', 'ta_question_fk')
                  ->references('id')->on('questions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_answers');
    }
};

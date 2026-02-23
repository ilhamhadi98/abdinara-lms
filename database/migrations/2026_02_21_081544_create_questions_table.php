<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtopic_id');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('option_e');
            $table->char('correct_answer', 1);
            $table->tinyInteger('difficulty')->default(1);
            $table->timestamps();

            $table->index('subtopic_id', 'questions_subtopic_idx');
            $table->index('difficulty', 'questions_difficulty_idx');
            $table->foreign('subtopic_id', 'questions_subtopic_fk')
                  ->references('id')->on('subtopics')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};

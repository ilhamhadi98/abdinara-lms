<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tryout_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->index('tryout_id', 'tq_tryout_idx');
            $table->index('question_id', 'tq_question_idx');
            $table->unique(['tryout_id', 'question_id'], 'tq_unique');

            $table->foreign('tryout_id', 'tq_tryout_fk')->references('id')->on('tryouts')->cascadeOnDelete();
            $table->foreign('question_id', 'tq_question_fk')->references('id')->on('questions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_questions');
    }
};

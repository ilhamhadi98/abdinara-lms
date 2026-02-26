<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tryouts', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subtopic_id')->nullable()->constrained('subtopics')->nullOnDelete();
            $table->unsignedTinyInteger('difficulty')->nullable()->comment('1: Mudah, 2: Sedang, 3: Sulit');
        });
    }

    public function down(): void
    {
        Schema::table('tryouts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['subtopic_id']);
            $table->dropColumn(['category_id', 'subtopic_id', 'difficulty']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tryout_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->unsignedSmallInteger('score')->nullable();
            $table->string('status', 20)->default('ongoing');
            $table->timestamps();

            $table->index('user_id', 'ts_user_idx');
            $table->index('tryout_id', 'ts_tryout_idx');
            $table->index('status', 'ts_status_idx');

            $table->foreign('user_id', 'ts_user_fk')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('tryout_id', 'ts_tryout_fk')->references('id')->on('tryouts')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_sessions');
    }
};

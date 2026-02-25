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
        // 1. Tryout Sessions Ranking Index
        Schema::table('tryout_sessions', function (Blueprint $table) {
            // Drop existing index 'ts_status_idx' that might overlap
            $table->dropIndex('ts_status_idx');
            // Adding composite index primarily used for leaderboards/ranking queries
            $table->index(['tryout_id', 'status', 'score', 'finished_at'], 'ts_ranking_idx');
        });

        // 2. Transactions Quick Filter Index
        Schema::table('transactions', function (Blueprint $table) {
            // Allows rapid filtering of a user's successful transactions (Premium check fallback)
            $table->index(['user_id', 'status'], 'trx_user_status_idx');
            // Useful for Admin Panel to quickly sort & filter incoming transactions
            $table->index(['status', 'created_at'], 'trx_status_date_idx');
        });

        // 3. User Subscriptions Check
        Schema::table('users', function (Blueprint $table) {
            // Middleware usually checks if subscription_expires_at > now()
            $table->index('subscription_expires_at', 'users_subs_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tryout_sessions', function (Blueprint $table) {
            $table->dropIndex('ts_ranking_idx');
            // Re-create the dropped index
            $table->index('status', 'ts_status_idx');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('trx_user_status_idx');
            $table->dropIndex('trx_status_date_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_subs_idx');
        });
    }
};

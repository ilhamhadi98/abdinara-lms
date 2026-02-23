<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserActivity;
use App\Models\User;

class UserActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // get the first user
        if (!$user) return;

        for ($i = 0; $i < 300; $i++) {
            $date = now()->subDays(rand(0, 365))->toDateString();
            $count = rand(0, 15);
            
            // Generate some zero activity days by random chance
            if (rand(1, 100) > 30) {
                UserActivity::updateOrCreate([
                    'user_id' => $user->id,
                    'action' => 'activity',
                    'date' => $date
                ], [
                    'count' => $count
                ]);
            }
        }
    }
}

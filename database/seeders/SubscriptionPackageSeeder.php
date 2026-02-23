<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPackage;

class SubscriptionPackageSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPackage::updateOrCreate(
            ['name' => 'Paket Basic (30 Hari)'],
            [
                'description' => 'Akses seluruh fitur dan materi Tryout selama 30 hari penuh.',
                'price' => 10000.00,
                'duration_days' => 30,
            ]
        );

        SubscriptionPackage::updateOrCreate(
            ['name' => 'Paket Pro (150 Hari)'],
            [
                'description' => 'Akses seluruh fitur dan materi Tryout selama 150 hari penuh (+ ekstra hemat).',
                'price' => 50000.00,
                'duration_days' => 150,
            ]
        );
    }
}

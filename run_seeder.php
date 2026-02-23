<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\SubscriptionPackage::updateOrCreate(
    ['name' => 'Paket Basic (30 Hari)'],
    [
        'description' => 'Akses seluruh fitur dan materi Tryout selama 30 hari penuh.',
        'price' => 10000.00,
        'duration_days' => 30,
    ]
);

\App\Models\SubscriptionPackage::updateOrCreate(
    ['name' => 'Paket Pro (150 Hari)'],
    [
        'description' => 'Akses seluruh fitur dan materi Tryout selama 150 hari penuh (+ ekstra hemat).',
        'price' => 50000.00,
        'duration_days' => 150,
    ]
);

echo "Packages Seeded!\n";

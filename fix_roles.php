<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::all();
foreach ($users as $user) {
    if ($user->roles->count() == 0) {
        $user->assignRole('member');
        echo "Assigned member role to: " . $user->email . "\n";
    }
}
echo "Done.\n";

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil command setup:permissions agar permissions
        // sinkron dengan custom role yang kita buat.
        Artisan::call('setup:permissions');
    }
}

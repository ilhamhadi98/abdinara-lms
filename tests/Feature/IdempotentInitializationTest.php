<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class IdempotentInitializationTest extends TestCase
{
    use RefreshDatabase;

    public function test_initialization_commands_are_safe_to_run_multiple_times()
    {
        // === HARI KE-1: Server pertama kali di-deploy (Container up) ===
        // 1. Script startup menjalankan seeder
        $this->seed(RolePermissionSeeder::class);

        // 2. Script startup membuat super admin
        Artisan::call('make:super-admin', [
            '--name' => 'Admin Utama',
            '--email' => 'admin@abdinara.id',
            '--password' => '@ANiam1998'
        ]);

        // Verifikasi admin terbentuk
        $admin = User::where('email', 'admin@abdinara.id')->first();
        $this->assertNotNull($admin, 'Admin harusnya sudah terbuat');
        $this->assertTrue($admin->hasRole('super-admin'), 'Admin harus memiliki hak super-admin');
        $this->assertNull($admin->telegram_chat_id, 'Di awal, Telegram ID harusnya kosong');

        // === HARI KE-5: Admin memasukkan Chat ID Telegram mereka (Simulasi User Input) ===
        $admin->telegram_chat_id = '987654321';
        $admin->save();

        // Verifikasi tersimpan
        $admin->refresh();
        $this->assertEquals('987654321', $admin->telegram_chat_id);


        // === HARI KE-10: Sistem di-Rebuild (Docker compose build && up -d) ===
        // Container berjalan lagi, script startup akan MENGULANG perintah awal
        
        // 1. Seeder jalan lagi
        $this->seed(RolePermissionSeeder::class);

        // 2. Setup Super Admin jalan lagi dengan data default yang sama
        Artisan::call('make:super-admin', [
            '--name' => 'Admin Utama',
            '--email' => 'admin@abdinara.id',
            '--password' => '@ANiam1998'
        ]);

        // === HASIL PEMBUKTIAN AMAN (ASSERTIONS) ===
        
        // Refresh model dari database ke memory RAM terbaru
        $admin->refresh();

        // 1. Data pribadi tidak boleh hilang atau me-reset ke NULL!
        $this->assertEquals('987654321', $admin->telegram_chat_id, 'Kritis: Chat ID Telegram HILANG/DIRESET ketika container rebuild!');
        
        // 2. Nama tidak boleh ter-reset acak
        $this->assertEquals('Admin Utama', $admin->name, 'Nama admin berubah secara tidak sah');
        
        // 3. Hak akses (Permissions) masih utuh terhubung
        $this->assertTrue($admin->hasRole('super-admin'), 'Admin kehilangan jabatan super-admin setelah rebuild');
        
        // 4. Memastikan Role tidak tergandakan secara aneh di database (Idempotent)
        $superAdminRolesCount = Role::where('name', 'super-admin')->count();
        $this->assertEquals(1, $superAdminRolesCount, 'Role super-admin terduplikasi di database');
        
        // 5. Password masih bisa login (tetap updated)
        $this->assertTrue(\Illuminate\Support\Facades\Auth::guard('web')->attempt([
            'email' => 'admin@abdinara.id',
            'password' => '@ANiam1998'
        ]), 'Password menjadi rusak atau tidak bisa digunakan login setelah rebuild');
    }
}

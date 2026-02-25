<?php

namespace Tests\Feature;

use App\Filament\Resources\AgendaResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\TransactionResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Definisikan permissions untuk pengujian
        $permissions = [
            'view_any_agenda', 'create_agenda', 'update_agenda', 'delete_agenda', 'delete_any_agenda',
            'view_any_transaction', 'create_transaction', 'update_transaction', 'delete_transaction', 'delete_any_transaction',
            'view_any_category', 'create_category', 'update_category', 'delete_category', 'delete_any_category',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat roles beserta set permissions sesuai bisnis logik
        $roleContent = Role::firstOrCreate(['name' => 'admin-content']);
        $roleContent->syncPermissions(['view_any_agenda', 'create_agenda', 'update_agenda']);

        $roleFinance = Role::firstOrCreate(['name' => 'admin-finance']);
        $roleFinance->syncPermissions(['view_any_transaction', 'create_transaction', 'update_transaction']);

        $roleSoal = Role::firstOrCreate(['name' => 'admin-soal']);
        $roleSoal->syncPermissions(['view_any_category', 'create_category', 'update_category']);
        
        $roleSuper = Role::firstOrCreate(['name' => 'super-admin']);
        $roleSuper->syncPermissions(Permission::all()); // Punya akses hapus
    }

    public function test_admin_content_can_manage_agendas_but_cannot_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('admin-content');
        
        $this->actingAs($user);

        // Uji Akses ke Modul Konten (Agenda)
        $this->assertTrue(AgendaResource::canViewAny(), 'Admin Content harusnya bisa melihat Agenda');
        $this->assertTrue(AgendaResource::canCreate(), 'Admin Content harusnya bisa membuat Agenda');
        
        // Uji Akses Hapus Konten (Agenda) - HARUS FALSE
        $this->assertFalse(AgendaResource::canDeleteAny(), 'Admin Content TIDAK BOLEH bisa menghapus Agenda');

        $this->assertFalse(AgendaResource::canDeleteAny(), 'Admin Content TIDAK BOLEH bisa menghapus Agenda');

        // Uji Keterasingan Modul: Admin Content tidak boleh bisa masuk ke Finance / Soal
        $this->assertFalse(TransactionResource::canViewAny(), 'Admin Content dilarang melihat menu Transaksi');
        $this->assertFalse(CategoryResource::canViewAny(), 'Admin Content dilarang melihat menu Kategori Bank Soal');
    }

    public function test_admin_finance_can_manage_transactions_but_cannot_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('admin-finance');
        
        $this->actingAs($user);

        // Uji Akses ke Modul Finance (Transaction)
        $this->assertTrue(TransactionResource::canViewAny(), 'Admin Finance harus bisa melihat Transaksi');
        
        // Uji Akses Hapus Konten (Transaction) - HARUS FALSE
        $this->assertFalse(TransactionResource::canDeleteAny(), 'Admin Finance TIDAK BOLEH menghapus data pembayaran');

        // Uji Keterasingan Modul: Finance tidak merecoki Modul Konten
        $this->assertFalse(AgendaResource::canViewAny(), 'Admin Finance dilarang masuk ke menu Agenda');
    }

    public function test_admin_soal_can_manage_categories_but_cannot_delete()
    {
        $user = User::factory()->create();
        $user->assignRole('admin-soal');
        
        $this->actingAs($user);

        // Uji Akses ke Modul Soal (Category)
        $this->assertTrue(CategoryResource::canViewAny(), 'Admin Soal harus bisa mengelola Kategori Ujian');
        
        // Uji Akses Hapus (Category) - HARUS FALSE
        $this->assertFalse(CategoryResource::canDeleteAny(), 'Admin Soal TIDAK BOLEH menghapus massal relasi Kategori');

        // Uji Keterasingan Relasi
        $this->assertFalse(TransactionResource::canViewAny(), 'Admin Soal dilarang menyentuh transaksi pembayaran');
    }

    public function test_super_admin_has_full_access_including_deletion()
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');
        
        $this->actingAs($user);

        // Uji Akses Penuh tanpa batasan dan memiliki izin hapus ke seluruh lini sistem.
        $this->assertTrue(AgendaResource::canViewAny(), 'Super Admin bisa melihat Agenda');
        $this->assertTrue(AgendaResource::canDeleteAny(), 'Super Admin BEBAS MENGHAPUS Agenda');
        
        $this->assertTrue(TransactionResource::canViewAny(), 'Super Admin bisa melihat Transaksi');
        $this->assertTrue(TransactionResource::canDeleteAny(), 'Super Admin BEBAS MENGHAPUS Transaksi');
    }
}

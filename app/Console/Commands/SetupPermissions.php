<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup roles and initial permissions for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Selalu bersihkan cache permission dulu sebelum setup
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = ['agenda', 'announcement', 'module', 'transaction', 'category', 'question', 'tryout', 'usertarget', 'user', 'subscriptionpackage'];
        $actions = ['view_any', 'create', 'update', 'delete', 'delete_any'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$resource}"]);
            }
        }
        // Tambahan permission dasar dari seeder lama
        $basicPermissions = [
            'manage question',
            'manage tryout',
            'publish tryout',
            'take tryout',
            'view result',
        ];

        foreach ($basicPermissions as $bp) {
            Permission::firstOrCreate(['name' => $bp, 'guard_name' => 'web']);
        }

        // Pastikan role utama terbuat
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'admin-content']);
        Role::firstOrCreate(['name' => 'admin-finance']);
        Role::firstOrCreate(['name' => 'admin-soal']);
        Role::firstOrCreate(['name' => 'admin-full']);

        // Member
        $member = Role::firstOrCreate(['name' => 'member']);
        $member->syncPermissions(['take tryout', 'view result']);

        // Set hak akses role admin-content (Agenda, Announcement, Module) - Tanpa Delete
        $adminContent = Role::findByName('admin-content');
        $adminContent->syncPermissions([
            'view_any_agenda', 'create_agenda', 'update_agenda',
            'view_any_announcement', 'create_announcement', 'update_announcement',
            'view_any_module', 'create_module', 'update_module'
        ]);

        // Set hak akses role admin-finance (Transaction & Subscription Package) - Tanpa Delete
        $adminFinance = Role::findByName('admin-finance');
        $adminFinance->syncPermissions([
            'view_any_transaction', 'create_transaction', 'update_transaction',
            'view_any_subscriptionpackage', 'create_subscriptionpackage', 'update_subscriptionpackage'
        ]);

        // Set hak akses role admin-soal (Category, Question, Tryout) - Tanpa Delete
        $adminSoal = Role::findByName('admin-soal');
        $adminSoal->syncPermissions([
            'view_any_category', 'create_category', 'update_category',
            'view_any_question', 'create_question', 'update_question',
            'view_any_tryout', 'create_tryout', 'update_tryout',
        ]);

        // Set hak akses admin-full (semua akses - Tanpa Delete)
        $permissionsAdminFull = Permission::where('name', 'not like', 'delete_%')->pluck('name');
        $adminFull = Role::findByName('admin-full');
        $adminFull->syncPermissions($permissionsAdminFull);

        $this->info('Permissions and Default Roles configured successfully!');
    }
}

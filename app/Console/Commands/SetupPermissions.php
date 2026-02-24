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
        $resources = ['agenda', 'announcement', 'module', 'transaction', 'category', 'question', 'tryout', 'usertarget', 'user'];
        $actions = ['view_any', 'create', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$resource}"]);
            }
        }

        // Pastikan role utama terbuat
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'admin-content']);
        Role::firstOrCreate(['name' => 'admin-finance']);
        Role::firstOrCreate(['name' => 'admin-soal']);
        Role::firstOrCreate(['name' => 'admin-full']);

        // Set hak akses role admin-content (Agenda, Announcement, Module) - Tanpa Delete
        $adminContent = Role::findByName('admin-content');
        $adminContent->syncPermissions([
            'view_any_agenda', 'create_agenda', 'update_agenda',
            'view_any_announcement', 'create_announcement', 'update_announcement',
            'view_any_module', 'create_module', 'update_module'
        ]);

        // Set hak akses role admin-finance (Transaction) - Tanpa Delete
        $adminFinance = Role::findByName('admin-finance');
        $adminFinance->syncPermissions([
            'view_any_transaction', 'create_transaction', 'update_transaction'
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

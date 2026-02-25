<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$resources = ['agenda', 'announcement', 'module', 'transaction', 'category', 'question', 'tryout', 'usertarget', 'user'];
$actions = ['view_any', 'view', 'create', 'update', 'delete', 'delete_any'];

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
    'view_any_agenda', 'view_agenda', 'create_agenda', 'update_agenda',
    'view_any_announcement', 'view_announcement', 'create_announcement', 'update_announcement',
    'view_any_module', 'view_module', 'create_module', 'update_module'
]);

// Set hak akses role admin-finance (Transaction) - Tanpa Delete
$adminFinance = Role::findByName('admin-finance');
$adminFinance->syncPermissions([
    'view_any_transaction', 'view_transaction', 'create_transaction', 'update_transaction'
]);

// Set hak akses role admin-soal (Category, Question, Tryout) - Tanpa Delete
$adminSoal = Role::findByName('admin-soal');
$adminSoal->syncPermissions([
    'view_any_category', 'view_category', 'create_category', 'update_category',
    'view_any_question', 'view_question', 'create_question', 'update_question',
    'view_any_tryout', 'view_tryout', 'create_tryout', 'update_tryout',
]);

// Set hak akses admin-full (semua akses - Tanpa Delete)
$permissionsAdminFull = Permission::where('name', 'not like', 'delete_%')->pluck('name');
$adminFull = Role::findByName('admin-full');
$adminFull->syncPermissions($permissionsAdminFull);

echo "Permissions generated perfectly!";

<?php
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$u = User::factory()->create();
$r = Role::firstOrCreate(['name' => 'admin-content']);

Permission::firstOrCreate(['name' => 'view_any_agenda']);
Permission::firstOrCreate(['name' => 'view_any_transaction']);

$r->syncPermissions(['view_any_agenda']);
$u->assignRole('admin-content');

echo $u->hasPermissionTo('view_any_transaction') ? 'HAS PERMISSION' : 'NO PERMISSION';

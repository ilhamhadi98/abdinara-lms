<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Filament\Resources\TransactionResource;

$permissions = ['view_any_transaction'];
foreach ($permissions as $p) {
    Permission::firstOrCreate(['name' => $p]);
}
$roleContent = Role::firstOrCreate(['name' => 'admin-content']);

$user = User::factory()->create();
$user->assignRole('admin-content');

Auth::login($user);

var_dump(TransactionResource::canViewAny());
var_dump(auth()->user()->hasPermissionTo('view_any_transaction'));

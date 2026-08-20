<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $c = \App\Models\Category::firstOrCreate(['name'=>'TestCat']);
    $s = \App\Models\Subtopic::create(['category_id'=>$c->id, 'name'=>'TestSub']);
    echo 'SUCCESS: '.$s->id."\n";
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}

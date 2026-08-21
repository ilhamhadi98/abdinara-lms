<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // Paksa database default SELALU menggunakan 'testing' (abdinara_lms_testing)
        // sehingga RefreshDatabase TIDAK PERNAH menyentuh database utama (abdinara_lms_2).
        $app['config']->set('database.default', 'testing');

        return $app;
    }

    protected function connectionsToTransact()
    {
        return ['testing'];
    }
}




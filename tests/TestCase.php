<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // Lindungi database utama: paksa semua koneksi selama test mengarah ke abdinara_lms_testing
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.mysql.database', 'abdinara_lms_testing');

        return $app;
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return ['testing'];
    }

    /**
     * The parameters that should be used when running migrate:fresh.
     *
     * @return array
     */
    protected function migrateFreshUsing()
    {
        return [
            '--database' => 'testing',
            '--drop-views' => false,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Cache::flush();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}




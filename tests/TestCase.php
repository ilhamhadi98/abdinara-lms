<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Alihkan koneksi default ke 'testing' (abdinara_lms_testing).
        // Ini memastikan tests tidak pernah menyentuh database utama (abdinara_lms_2).
        config(['database.default' => 'testing']);
        app('db')->purge('mysql');
        app('db')->reconnect('testing');
    }
}

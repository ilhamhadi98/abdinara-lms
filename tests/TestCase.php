<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'testing']);
        app('db')->purge('mysql');
        app('db')->reconnect('testing');
    }
}



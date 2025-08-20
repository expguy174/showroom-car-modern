<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Use sync queue for deterministic listener execution in tests
        config()->set('queue.default', 'sync');

        // Use sqlite in-memory for fast tests
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        // Ensure migrations are run
        \Artisan::call('migrate', ['--force' => true]);
    }
}

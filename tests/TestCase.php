<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    protected static bool $migratedAndSeeded = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            $sqlitePath = database_path('database.sqlite');
            Config::set('database.connections.sqlite.database', $sqlitePath);

            if (! static::$migratedAndSeeded) {
                if (! file_exists($sqlitePath)) {
                    touch($sqlitePath);
                }
                Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
                Artisan::call('db:seed', ['--database' => 'sqlite', '--force' => true]);
                static::$migratedAndSeeded = true;
            }
        }
    }
}

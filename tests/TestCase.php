<?php

namespace Noardcode\LaravelSignhost\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Noardcode\LaravelSignhost\Providers\SignhostServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use DatabaseTransactions;
    use WithWorkbench;

    const TESTING_FIXTURES_DISK = 'fixtures';

    protected function getPackageProviders($app)
    {
        return [
            SignhostServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.'.self::TESTING_FIXTURES_DISK, [
            'driver' => 'local',
            'root' => __DIR__.'/Assets',
        ]);
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
    }
}

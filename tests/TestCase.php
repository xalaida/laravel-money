<?php

declare(strict_types=1);

namespace Jeka\Money\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Jeka\Money\MoneyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/Support/Migrations');

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [MoneyServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}

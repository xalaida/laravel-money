<?php

namespace Nevadskiy\Money\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\Application;
use Nevadskiy\Money\MoneyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.locale' => 'uk']);

        config(['money.currency' => 'UAH']);

        Model::unguard();
    }

    /**
     * Get package providers.
     *
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            MoneyServiceProvider::class
        ];
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

    /**
     * Get a schema builder instance.
     */
    protected function schema(): Builder
    {
        return Model::getConnectionResolver()
            ->connection()
            ->getSchemaBuilder();
    }
}

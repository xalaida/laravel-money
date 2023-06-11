<?php

namespace Nevadskiy\Money\Tests;

use Illuminate\Foundation\Application;
use Nevadskiy\Money\MoneyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
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
}

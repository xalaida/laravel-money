<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerScaler();
        $this->registerFormatter();
        $this->registerConverter();
        $this->registerRateProvider();
        $this->registerOpenExchangeProvider();
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    /**
     * Register any package configurations.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
    }

    /**
     * Register the money scaler.
     */
    protected function registerScaler(): void
    {
        $this->app->singletonIf(Scaler\Scaler::class, Scaler\ArrayScaler::class);
    }

    /**
     * Register the money formatter.
     */
    protected function registerFormatter(): void
    {
        $this->app->singleton(Formatter\Formatter::class, Formatter\IntlFormatter::class);
    }

    /**
     * Register the money converter.
     */
    protected function registerConverter(): void
    {
        $this->app->singleton(Converter\Converter::class, Converter\ArrayConverter::class);
    }

    /**
     * Register the default rate provider.
     */
    protected function registerRateProvider(): void
    {
        $this->app->singleton(RateProvider\RateProvider::class, $this->app['config']['money']['rate_provider']);
    }

    /**
     * Register the open exchange rate provider.
     */
    protected function registerOpenExchangeProvider(): void
    {
        $this->app->bind('open_exchange_rates', RateProvider\Providers\OpenExchangeProvider::class);

        $this->app->when(RateProvider\Providers\OpenExchangeProvider::class)
            ->needs('$appId')
            ->give(function (Application $app) {
                return $app['config']['money']['rate_providers']['open_exchange_rates']['app_id'];
            });
    }

    /**
     * Publish any package configurations.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/money.php' => config_path('money.php')
        ], 'money-config');
    }
}

<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    /**
     * Register application configuration.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
    }

    /**
     * Register application money scaler.
     */
    protected function registerScaler(): void
    {
        $this->app->singletonIf(Scaler\Scaler::class, Scaler\ArrayScaler::class);
    }

    /**
     * Register application money formatter.
     */
    protected function registerFormatter(): void
    {
        $this->app->singleton(Formatter\Formatter::class, Formatter\IntlFormatter::class);
    }

    /**
     * Register application money formatter.
     */
    protected function registerConverter(): void
    {
        $this->app->singleton(Converter\Converter::class, function (Application $app) {
            return new Converter\ArrayConverter($app->get(RateProvider\RateProvider::class)->getRates());
        });
    }

    /**
     * Register application rate provider.
     */
    protected function registerRateProvider(): void
    {
        $this->app->singleton(RateProvider\RateProvider::class, $this->app['config']['money']['rate_provider']);
    }

    /**
     * Register application open exchange rate provider.
     */
    protected function registerOpenExchangeProvider(): void
    {
        $this->app->bind('open_exchange_rates', RateProvider\OpenExchangeRateProvider::class);

        $this->app->when(RateProvider\OpenExchangeRateProvider::class)
            ->needs('$appId')
            ->give(function (Application $app) {
                return $app['config']['money']['rate_providers']['open_exchange_rates']['app_id'];
            });

        $this->app->extend(
            RateProvider\OpenExchangeRateProvider::class,
            function (RateProvider\OpenExchangeRateProvider $provider, Application $app) {
                return new RateProvider\CacheRateProvider($provider, $app->get('cache.store'));
            }
        );
    }

    /**
     * Publish application configuration.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/money.php' => config_path('money.php')
        ], 'money-config');
    }
}

<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Nevadskiy\Money\RateProvider\RateProviderManager;
use Nevadskiy\Money\Registry\CurrencyRegistry;
use Nevadskiy\Money\Registry\CurrencyRegistryManager;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerCurrencyRegistry();
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
     * Register application currency registry.
     */
    protected function registerCurrencyRegistry(): void
    {
        $this->app->singletonIf(Registry\CurrencyRegistry::class, function (Application $app) {
            return new CurrencyRegistry(
                $app->get('config')['money']['currencies'] ?? []
            );
        });
    }

    /**
     * Register application money scaler.
     */
    protected function registerScaler(): void
    {
        $this->app->singletonIf(Scaler\Scaler::class, function (Application $app) {
            return new Scaler\RoundScaler(
                $app->get(Registry\CurrencyRegistry::class)->pluck('scale')
            );
        });
    }

    /**
     * Register application money formatter.
     */
    protected function registerFormatter(): void
    {
        $this->app->singletonIf(Formatter\Formatter::class, Formatter\IntlFormatter::class);
    }

    /**
     * Register application money formatter.
     */
    protected function registerConverter(): void
    {
        $this->app->singletonIf(Converter\Converter::class, Converter\MajorUnitConverter::class);

        $this->app->extend(Converter\Converter::class, function (Converter\Converter $converter, Application $app) {
            return new Converter\FallbackConverter($converter, $app->get('config')['money']['fallback_currency']);
        });
    }

    /**
     * Register application rate provider.
     */
    protected function registerRateProvider(): void
    {
        $this->app->singletonIf(RateProvider\RateProvider::class, RateProviderManager::class);
    }

    /**
     * Register application open exchange rate provider.
     */
    protected function registerOpenExchangeProvider(): void
    {
        $this->app->when(RateProvider\OpenExchangeRateProvider::class)
            ->needs('$appId')
            ->give(function (Application $app) {
                return $app->get('config')['money']['rate_providers']['open_exchange_rates']['app_id'];
            });
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

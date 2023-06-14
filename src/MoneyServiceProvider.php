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
        $this->registerCurrencyRegistry();
        $this->registerScaler();
        $this->registerFormatter();
        $this->registerSerializer();
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
        $this->app->singletonIf(Registries\CurrencyRegistry::class, function (Application $app) {
            return new Registries\CurrencyRegistry(
                $app->get('config')['money']['currencies'] ?? []
            );
        });
    }

    /**
     * Register application money scaler.
     */
    protected function registerScaler(): void
    {
        $this->app->singletonIf(Scalers\Scaler::class, function (Application $app) {
            return new Scalers\RoundScaler(
                $app->get(Registries\CurrencyRegistry::class)->pluck('scale')
            );
        });
    }

    /**
     * Register application money formatter.
     */
    protected function registerFormatter(): void
    {
        $this->app->singletonIf(Formatters\Formatter::class, Formatters\IntlFormatter::class);
    }

    /**
     * Register application money serializer.
     */
    protected function registerSerializer(): void
    {
        $this->app->singletonIf(Serializers\Serializer::class, Serializers\ArraySerializer::class);
    }

    /**
     * Register application money formatter.
     */
    protected function registerConverter(): void
    {
        $this->app->singletonIf(Converters\Converter::class, Converters\MajorUnitConverter::class);

        $this->app->extend(Converters\Converter::class, function (Converters\Converter $converter, Application $app) {
            return new Converters\FallbackConverter($converter, $app->get('config')['money']['fallback_currency']);
        });
    }

    /**
     * Register application rate provider.
     */
    protected function registerRateProvider(): void
    {
        $this->app->singletonIf(RateProviders\RateProvider::class, RateProviders\RateProviderManager::class);
    }

    /**
     * Register application open exchange rate provider.
     */
    protected function registerOpenExchangeProvider(): void
    {
        $this->app->when(RateProviders\OpenExchangeRateProvider::class)
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

<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\Registry\CurrencyRegistry;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerRegistry();
        $this->registerScaler();
        $this->registerFormatter();
        $this->registerConverter();
        $this->registerCurrencyQueries();
        $this->registerDefaultCurrency();
        $this->registerOpenExchangeProvider();
        $this->registerDefaultRateProvider();
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootMigrations();
        $this->bootMorphMap();
        $this->publishConfig();
        $this->publishMigrations();

        Money::setDefaultCurrency($this->app->get('config')['money']['currency']);
    }

    /**
     * Register any package configurations.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
    }

    /**
     * Register the currency registry.
     */
    protected function registerRegistry(): void
    {
        $this->app->singleton(CurrencyRegistry::class);
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
        $this->app->singleton(Converter\Converter::class, Converter\RegistryConverter::class);
    }

    /**
     * Register any package currency queries.
     */
    protected function registerCurrencyQueries(): void
    {
        $config = $this->app['config']['money']['bindings'][CurrencyQuery::class];

        $this->app->singleton(CurrencyQuery::class, $config['implementation']);

        foreach ($config['decorators'] ?? [] as $decorator) {
            $this->app->extend(CurrencyQuery::class, function (CurrencyQuery $currencies, Application $app) use ($decorator) {
                return $app->make($decorator, [
                    'currencies' => $currencies,
                ]);
            });
        }
    }

    /**
     * Register the default application currency.
     */
    protected function registerDefaultCurrency(): void
    {
        $this->app->when($this->app['config']['money']['bindings'][CurrencyQuery::class]['implementation'])
            ->needs('$defaultCurrencyCode')
            ->give(function (Application $app) {
                return $app['config']['money']['default_currency_code'] ?? null;
            });

        // Money::resolveDefaultCurrencyUsing(function () {
            // return $this->app[CurrencyQuery::class]->default();
        // });
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
     * Register the default rate provider.
     */
    protected function registerDefaultRateProvider(): void
    {
        $this->app->singleton(RateProvider\RateProvider::class, $this->app['config']['money']['default_rate_provider']);
    }

    /**
     * Boot any package console commands.
     */
    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\UpdateCurrencyRatesCommand::class,
                Console\SeedCurrenciesCommand::class,
            ]);
        }
    }

    /**
     * Boot any package migrations.
     */
    protected function bootMigrations(): void
    {
        if ($this->app['config']['money']['default_migrations']) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Boot package morph map.
     */
    protected function bootMorphMap(): void
    {
        Relation::morphMap([
            'currencies' => Models\Currency::class,
        ]);
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

    /**
     * Publish any package migrations.
     */
    protected function publishMigrations(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'money-migrations');
    }
}

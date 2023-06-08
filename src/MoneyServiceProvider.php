<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;
use Nevadskiy\Money\Queries\CurrencyCacheQuery;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\Money;

/**
 * @todo extend Application with currency (make configurable).
 */
class MoneyServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the package.
     *
     * @var array
     */
    private $listen = [
        LocaleUpdated::class => [
            Listeners\UpdateDefaultFormatterLocale::class,
        ],

        Events\DefaultCurrencyUpdated::class => [
            Listeners\UpdateDefaultConverterCurrency::class,
        ],
    ];

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->registerConfig();
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
        $this->bootEvents();
        $this->bootCacheInvalidator();
        $this->bootMigrations();
        $this->bootMorphMap();
        $this->publishConfig();
        $this->publishMigrations();
    }

    /**
     * Register any package configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
    }

    /**
     * Register any package formatter.
     */
    private function registerFormatter(): void
    {
        $this->app->singleton(Formatter\Formatter::class, function (Application $app) {
            return new Formatter\DefaultFormatter($app->getLocale());
        });
    }

    /**
     * Register any package converter.
     */
    private function registerConverter(): void
    {
        $this->app->singleton(Converter\Converter::class, function () {
            return new Converter\RegistryConverter(Money::resolveDefaultCurrency());
        });
    }

    /**
     * Register any package currency queries.
     */
    private function registerCurrencyQueries(): void
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
    private function registerDefaultCurrency(): void
    {
        $this->app->when($this->app['config']['money']['bindings'][CurrencyQuery::class]['implementation'])
            ->needs('$defaultCurrencyCode')
            ->give(function (Application $app) {
                return $app['config']['money']['default_currency_code'] ?? null;
            });

        Money::resolveDefaultCurrencyUsing(function () {
            return $this->app[CurrencyQuery::class]->default();
        });
    }

    /**
     * Register the open exchange rate provider.
     */
    private function registerOpenExchangeProvider(): void
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
    private function registerDefaultRateProvider(): void
    {
        $this->app->singleton(RateProvider\RateProvider::class, $this->app['config']['money']['default_rate_provider']);
    }

    /**
     * Boot any package console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\UpdateCurrencyRatesCommand::class,
                Console\SeedCurrenciesCommand::class,
            ]);
        }
    }

    /**
     * Boot any package events.
     */
    private function bootEvents(): void
    {
        $dispatcher = $this->app[Dispatcher::class];

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }
    }

    /**
     * Boot any package events.
     */
    private function bootCacheInvalidator(): void
    {
        if ($this->app[CurrencyQuery::class] instanceof CurrencyCacheQuery) {
            $this->app[Dispatcher::class]->listen([
                Events\CurrencyCreated::class,
                Events\CurrencyUpdated::class,
                Events\CurrencyDeleted::class,
            ], Listeners\InvalidateCurrencyCache::class);
        }
    }

    /**
     * Boot any package migrations.
     */
    private function bootMigrations(): void
    {
        if ($this->app['config']['money']['default_migrations']) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Boot package morph map.
     */
    private function bootMorphMap(): void
    {
        Relation::morphMap([
            'currencies' => Models\Currency::class,
        ]);
    }

    /**
     * Publish any package configurations.
     */
    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config/money.php' => config_path('money.php')
        ], 'money-config');
    }

    /**
     * Publish any package migrations.
     */
    private function publishMigrations(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'money-migrations');
    }
}

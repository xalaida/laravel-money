<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;
use Nevadskiy\Money\Queries\CurrencyQuery;
use Nevadskiy\Money\ValueObjects\Money;

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

        Events\CurrencyCreated::class => [
            Listeners\InvalidateCurrencyCache::class,
        ],

        Events\CurrencyUpdated::class => [
            Listeners\InvalidateCurrencyCache::class,
        ],

        Events\CurrencyDeleted::class => [
            Listeners\InvalidateCurrencyCache::class,
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
        $this->bootRoutes();
        $this->bootEvents();
        $this->bootMigrations();
        $this->bootMorphMap();
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
        $this->app->singleton(Formatter\Formatter::class, function () {
            return new Formatter\DefaultFormatter($this->app->getLocale());
        });
    }

    /**
     * Register any package converter.
     */
    private function registerConverter(): void
    {
        $this->app->singleton(Converter\Converter::class, Converter\DefaultConverter::class);
    }

    /**
     * Register any package currency queries.
     */
    private function registerCurrencyQueries(): void
    {
        $this->app->singleton(Queries\CurrencyQuery::class, Queries\CurrencyEloquentQuery::class);

        $this->app->extend(Queries\CurrencyQuery::class, function (Queries\CurrencyQuery $currencies) {
            return $this->app->make(Queries\CurrencyCacheQuery::class, [
                'currencies' => $currencies,
            ]);
        });
    }

    /**
     * Register the default application currency.
     */
    private function registerDefaultCurrency(): void
    {
        $this->app->when(Queries\CurrencyEloquentQuery::class)
            ->needs('$defaultCurrencyCode')
            ->give(function () {
                return $this->app['config']['money']['default_currency_code'] ?? null;
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
            ->give($this->app['config']['money']['rate_providers']['open_exchange_rates']['app_id']);
    }

    /**
     * Register the default rate provider.
     */
    private function registerDefaultRateProvider(): void
    {
        $this->app->singleton(RateProvider\RateProvider::class, function () {
            return $this->app[$this->app['config']['money']['default_rate_provider']];
        });
    }

    /**
     * Boot any module routes.
     */
    private function bootRoutes(): void
    {
        $this->app['router']->group([
            'middleware' => 'api',
            'prefix' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Boot any package console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\UpdateRatesCommand::class,
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
     * Publish any package migrations.
     */
    private function publishMigrations(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'money-migrations');
    }
}

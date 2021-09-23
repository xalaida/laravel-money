<?php

namespace Nevadskiy\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Events\LocaleUpdated;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nevadskiy\Money\Converter\DefaultConverterFactory;
use Nevadskiy\Money\Models\Currency;
use Nevadskiy\Money\Queries\CurrencyQueries;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * The package's name.
     */
    private const NAME = 'money';

    /**
     * The event listener mappings for the package.
     *
     * @var array
     */
    private $listen = [
        LocaleUpdated::class => [
            Listeners\UpdateFormatterLocale::class,
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
        $this->registerRateProviders();
        $this->registerCurrencyQueries();
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
    }

    /**
     * Register any package configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', self::NAME);
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
        $this->app->singleton(Converter\Converter::class, static function () {
            return DefaultConverterFactory::create();
        });

        DefaultConverterFactory::resolveDefaultCurrencyUsing(function () {
            try {
                return $this->app[CurrencyQueries::class]->default();
            } catch (ModelNotFoundException $e) {
                return null;
            }
        });
    }

    /**
     * Register any package rate providers.
     */
    private function registerRateProviders(): void
    {
        $this->registerOpenExchangeProvider();

        $this->app->singleton(RateProvider\RateProvider::class, function () {
            return $this->app[$this->app['config']['money']['default_rate_provider']];
        });
    }

    /**
     * Register any package currency queries.
     */
    private function registerCurrencyQueries(): void
    {
        $this->app->singleton(Queries\CurrencyQueries::class, Queries\CurrencyEloquentQueries::class);

        $this->app->extend(Queries\CurrencyQueries::class, function (Queries\CurrencyQueries $queries) {
            return $this->app->make(Queries\CurrencyCacheQueries::class, [
                'queries' => $queries,
            ]);
        });

        $this->app->when([Queries\CurrencyEloquentQueries::class, Queries\CurrencyCacheQueries::class])
            ->needs('$defaultCurrencyCode')
            ->give(function () {
                return $this->app['config']['money']['default_currency_code'];
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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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
     * Register the open exchange rate provider.
     */
    private function registerOpenExchangeProvider(): void
    {
        $this->app->bind('open_exchange_rates', RateProvider\Providers\OpenExchangeProvider::class);

        $this->app->when(RateProvider\Providers\OpenExchangeProvider::class)
            ->needs('$appId')
            ->give($this->app['config']['money']['rate_providers']['open_exchange_rates']['app_id']);
    }
}

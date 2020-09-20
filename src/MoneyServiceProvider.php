<?php

declare(strict_types=1);

namespace Jeka\Money;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Jeka\Money\Models\Currency;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * The module's name.
     */
    private const NAME = 'money';

    /**
     * The event listener mappings for the package.
     *
     * @var array
     */
    protected $listen = [
        // Events...
    ];

    /**
     * Register any module services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootEvents();
        $this->bootMigrations();
        $this->bootViews();
        $this->bootTranslations();
        $this->bootMorphMap();
    }

    /**
     * Register any module configurations.
     */
    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', self::NAME);
    }

    /**
     * Boot any module console commands.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                //
            ]);
        }
    }

    /**
     * Boot any module events.
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
     * Boot any module migrations.
     */
    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Boot any module views.
     */
    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', self::NAME);
    }

    /**
     * Boot any module translations.
     */
    private function bootTranslations(): void
    {
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
    }

    /**
     * Boot module morph map.
     */
    private function bootMorphMap(): void
    {
        Relation::morphMap([
            'currencies' => Currency::class,
        ]);
    }
}
<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Providers;

use Illuminate\Support\ServiceProvider;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Providers\RouteServiceProvider;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Console\Install;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (! $this->app->providerIsLoaded(RouteServiceProvider::class)) {
            $this->app->register(RouteServiceProvider::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();

            $this->commands([
                Install::class,
            ]);
        }
    }

    /**
     * Publish configuration file.
     */
    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/config.php'
                => $this->configPath('prolancee/dynamic.crud.ajax.php'),
        ], 'prolancee:dynamic-crud-ajax:config');
    }

    /**
     * Resolve config path.
     */
    private function configPath(string $file = ''): string
    {
        return base_path('config' . ($file ? '/' . $file : ''));
    }
}

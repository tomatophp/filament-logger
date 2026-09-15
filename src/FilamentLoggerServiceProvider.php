<?php

namespace TomatoPHP\FilamentLogger;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentLogger\Console\FilamentLoggerInstall;
use TomatoPHP\FilamentLogger\Services\Benchmark;
use TomatoPHP\FilamentLogger\Services\LoggerServices;

class FilamentLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register generate command
        $this->commands([
            FilamentLoggerInstall::class,
        ]);

        // Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-logger.php', 'filament-logger');

        // Publish Config
        $this->publishes([
            __DIR__.'/../config/filament-logger.php' => config_path('filament-logger.php'),
        ], 'filament-logger-config');

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-logger-migrations');

        // Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-logger');

        // Publish Lang
        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-logger'),
        ], 'filament-logger-lang');

        Benchmark::start(config('filament-logger.request.benchmark', 'application'));

        $this->app->bind('filament-logger', function () {
            return new LoggerServices;
        });

    }

    public function boot(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
}

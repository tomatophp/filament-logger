<?php

namespace TomatoPHP\FilamentLogger;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentLogger\Console\FilamentLoggerInstall;
use TomatoPHP\FilamentLogger\Listeners\RequestLoggerListener;
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
            __DIR__.'/../resources/lang' => lang_path('vendor/filament-logger'),
        ], 'filament-logger-lang');

        Benchmark::start(config('filament-logger.request.benchmark', 'application'));

        $this->app->bind('filament-logger', function () {
            return new LoggerServices;
        });
    }

    public function boot(): void
    {
        /**
         * Subscribe directly instead of registering a framework EventServiceProvider subclass:
         * that base class re-registers the email verification listener on every boot (#5).
         */
        Event::subscribe(RequestLoggerListener::class);
    }
}

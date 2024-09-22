<?php

namespace TomatoPHP\FilamentLogger;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource;

class FilamentLoggerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-logger';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ActivityResource::class
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}

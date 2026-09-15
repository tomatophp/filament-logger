<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use TomatoPHP\FilamentLogger\Filament\Resources\ActivityResource;
use TomatoPHP\FilamentLogger\FilamentLoggerPlugin;
use TomatoPHP\FilamentLogger\FilamentLoggerServiceProvider;
use TomatoPHP\FilamentLogger\Services\LoggerServices;

it('boots the service provider', function () {
    expect(app()->getProviders(FilamentLoggerServiceProvider::class))->not->toBeEmpty()
        ->and(config('filament-logger.request'))->toBeArray()
        ->and(app('filament-logger'))->toBeInstanceOf(LoggerServices::class);
});

it('registers the plugin and its resource on the panel', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getPlugin('filament-logger'))->toBeInstanceOf(FilamentLoggerPlugin::class)
        ->and($panel->getResources())->toContain(ActivityResource::class);
});

it('registers the install command', function () {
    expect(Artisan::all())->toHaveKey('filament-logger:install');
});

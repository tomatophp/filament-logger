<?php

namespace TomatoPHP\FilamentLogger\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static void log(string $message, string $level = 'info')
 */
class FilamentLogger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-logger';
    }
}

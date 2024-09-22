<?php

namespace TomatoPHP\FilamentLogger\Facades;

use Pest\Mutate\Event\Facade;

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

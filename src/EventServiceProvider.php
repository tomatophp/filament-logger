<?php

namespace TomatoPHP\FilamentLogger;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use TomatoPHP\FilamentLogger\Listeners\RequestLoggerListener;

/**
 * Class EventServiceProvider
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        RequestLoggerListener::class,
    ];
}

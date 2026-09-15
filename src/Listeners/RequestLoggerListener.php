<?php

namespace TomatoPHP\FilamentLogger\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Class RequestLoggerListener
 */
class RequestLoggerListener
{
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            RequestHandled::class,
            RequestLoggerListenerHandler::class
        );
    }
}

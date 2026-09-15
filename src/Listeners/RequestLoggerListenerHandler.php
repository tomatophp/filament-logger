<?php

namespace TomatoPHP\FilamentLogger\Listeners;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use TomatoPHP\FilamentLogger\Jobs\RequestLogJob;
use TomatoPHP\FilamentLogger\Services\Benchmark;

/**
 * Class RequestLoggerListenerHandler
 */
class RequestLoggerListenerHandler
{
    use DispatchesJobs;

    /**
     * @throws Exception
     */
    public function handle(RequestHandled $event): void
    {
        Benchmark::end(config('filament-logger.request.benchmark', 'application'));

        if (! $this->excluded($event->request)) {
            $task = app(RequestLogJob::class, ['request' => $event->request, 'response' => $event->response]);
            $queueName = config('filament-logger.request.queue');
            if (is_null($queueName)) {
                $task->handle();
            } else {
                $this->dispatch(is_string($queueName) ? $task->onQueue($queueName) : $task);
            }
        }
    }

    /**
     * Check if current path is not excluded
     */
    protected function excluded(Request $request): bool
    {
        $excludedPaths = config('filament-logger.request.excluded-paths');
        if ($excludedPaths === null || empty($excludedPaths)) {
            return false;
        }
        foreach ($excludedPaths as $excludedPath) {
            if ($request->is($excludedPath)) {
                return true;
            }
        }

        return false;
    }
}

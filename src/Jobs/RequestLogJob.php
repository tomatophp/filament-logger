<?php

namespace TomatoPHP\FilamentLogger\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Response;
use TomatoPHP\FilamentLogger\Services\RequestLoggerService;

/**
 * Class RequestLogJob
 */
class RequestLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Request $request;

    protected Response $response;

    /**
     * RequestLogJob constructor.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $requestLoggerService = app(RequestLoggerService::class);
        $requestLoggerService->log($this->request, $this->response);
    }
}

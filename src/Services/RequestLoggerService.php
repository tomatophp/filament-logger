<?php

namespace TomatoPHP\FilamentLogger\Services;

use Illuminate\Support\Collection;
use TomatoPHP\FilamentLogger\Interpolations\RequestInterpolation;
use TomatoPHP\FilamentLogger\Interpolations\ResponseInterpolation;
use TomatoPHP\FilamentLogger\Loggers\RequestLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use TomatoPHP\FilamentLogger\Models\Activity;

/**
 * Class RequestLoggerService
 */
class RequestLoggerService
{
    protected Collection $collectLog;
    /**
     *
     */
    protected const LOG_CONTEXT = 'RESPONSE';
    /**
     * @var array
     */
    protected array $formats = [
        'full' => '{request-hash} | HTTP/{http-version} {status} | {remote-addr} | {user} | {method} {url} {query} | {response-time} s | {user-agent} | {referer}',
        'combined' => '{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length} "{referer}" "{user-agent}"',
        'common' => '{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length}',
        'dev' => '{method} {url} {status} {response-time} s - {content-length}',
        'short' => '{remote-addr} {remote-user} {method} {url} HTTP/{http-version} {status} {content-length} - {response-time} s',
        'tiny' => '{method} {url} {status} {content-length} - {response-time} s'
    ];
    /**
     * @var RequestInterpolation
     */
    protected RequestInterpolation $requestInterpolation;
    /**
     * @var ResponseInterpolation
     */
    protected ResponseInterpolation $responseInterpolation;
    /**
     * @var RequestLogger
     */
    protected RequestLogger $logger;

    /**
     * RequestLoggerService constructor.
     *
     * @param RequestLogger $logger
     * @param RequestInterpolation $requestInterpolation
     * @param ResponseInterpolation $responseInterpolation
     */
    public function __construct(
        RequestLogger $logger,
        RequestInterpolation $requestInterpolation,
        ResponseInterpolation $responseInterpolation
    ) {
        $this->logger = $logger;
        $this->requestInterpolation = $requestInterpolation;
        $this->responseInterpolation = $responseInterpolation;
        $this->collectLog = collect([]);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function log(Request $request, Response $response): void
    {
        $this->requestInterpolation->setRequest($request);

        $this->responseInterpolation->setResponse($response);

        if (config('filament-logger.request.enabled')) {
            $format = config('filament-logger.request.format', 'full');
            $format = Arr::get($this->formats, $format, $format);

            $message = $this->responseInterpolation->interpolate($format);

            $this->collectLog->push($this->responseInterpolation->getLogger()->toArray());

            $message = $this->requestInterpolation->interpolate($message);

            $this->collectLog->push($this->requestInterpolation->getLogger()->toArray());

            $logArray = array_merge($this->collectLog->toArray()[0], $this->collectLog->toArray()[1]);


            $active = false;
            foreach (config('filament-logger.request.guards') as $guard){
                if($request->route()?->middleware() && in_array($guard, $request->route()->middleware())){
                    $active = true;
                }
            }

            if(!config('filament-logger.request.livewire')){
                if($request->is('livewire/*')){
                    $active = false;
                }
            }

            if(count(config('filament-logger.request.excluded-paths'))){
                foreach (config('filament-logger.request.excluded-paths') as $path){
                    if($request->is($path)){
                        $active = false;
                    }
                }
            }

            if($active){
                if(config('filament-logger.request.database')) {
                    Activity::query()->create([
                        'model_id' => $request->user() ? $request->user()->id : null,
                        'model_type' => $request->user() ? get_class($request->user()) : null,
                        'request_hash' => $logArray['request-hash'],
                        'response_time' => $logArray['response-time'],
                        'status' => $logArray['status'],
                        'method' => $logArray['method'],
                        'url' => $logArray['url'],
                        'referer' => $logArray['referer'],
                        'query' => $logArray['query'],
                        'remote_address' => $logArray['remote-addr'],
                        'user_agent' => $request->userAgent(),
                        'level' => config('filament-logger.request.level', 'info'),
                        'user' => $logArray['user'],
                    ]);
                }

                if(config('filament-logger.request.log_file')){
                    $this->logger->log(config('filament-logger.request.level', 'info'), $message, [
                        static::LOG_CONTEXT
                    ]);
                }
            }
        }
    }
}

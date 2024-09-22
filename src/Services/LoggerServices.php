<?php

namespace TomatoPHP\FilamentLogger\Services;

use Illuminate\Support\Str;
use TomatoPHP\FilamentLogger\Models\Activity;

class LoggerServices
{
    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    public function log(string $message, string $level = 'info'): void
    {
        $request = request();

        Activity::query()->create([
            'model_id' => $request->user() ? $request->user()->id : null,
            'model_type' => $request->user() ? get_class($request->user()) : null,
            'request_hash' => Str::random(6),
            'response_time' => $request->server()['REQUEST_TIME'],
            'status' => $request->server()['REDIRECT_STATUS'],
            'method' => $request->server()['REQUEST_METHOD'],
            'url' => $request->url(),
            'referer' => $request->header('referer'),
            'query' => $request->query(),
            'remote_address' => $request->server()['REMOTE_ADDR'],
            'user_agent' => $request->userAgent(),
            'level' => $level,
            'user' => null,
            'log' => $message
        ]);
    }
}

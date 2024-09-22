<?php

namespace TomatoPHP\FilamentLogger\Loggers;

use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Class BaseRequestLogger
 */
class RequestLogger implements LoggerInterface
{
    /**
     * @var \Monolog\Logger;
     */
    protected \Monolog\Logger $monolog;

    /**
     * BaseRequestLogger constructor.
     */
    public function __construct()
    {
        if (version_compare(app()->version(), '5.5.99', '<=')) {
            $this->monolog = clone app('log')->getMonolog();
        } else {
            $this->monolog = app('log')->driver()->getLogger();
        }
        if (config('filament-logger.request.enabled') && $handlers = config('filament-logger.request.handlers')) {
            if (count($handlers)) {
                $this->monolog->popHandler();
                foreach ($handlers as $handler) {
                    if (class_exists($handler)) {
                        $this->monolog->pushHandler(app($handler));
                    } else {
                        throw new RuntimeException("Handler class [{$handler}] does not exist");
                    }
                }
            }
        }
    }

    /**
     * Log an alert message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->alert($message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->critical($message, $context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->error($message, $context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->warning($message, $context);
    }

    /**
     * Log a notice to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->notice($message, $context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->info($message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->debug($message, $context);
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->monolog->emergency($message, $context);
    }

    /**
     * Log a message to the logs.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->monolog->log($level, $message, $context);
    }
}

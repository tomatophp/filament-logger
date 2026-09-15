<?php

namespace TomatoPHP\FilamentLogger\Formatters;

use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;
use TomatoPHP\FilamentLogger\Services\Benchmark;

/**
 * Class LineWithHashFormatter
 */
class LineWithHashFormatter extends LineFormatter
{
    public const KEY = 'hash';

    public function format(LogRecord $record): string
    {
        $output = parent::format($record);
        if (str_contains($output, '%'.self::KEY.'%')) {
            $output = str_replace(
                '%'.self::KEY.'%',
                $this->stringify($this->getRequestHash()),
                $output
            );
        }

        return $output;
    }

    /**
     * Get request hash
     */
    protected function getRequestHash(): ?string
    {
        try {
            return Benchmark::hash(config('filament-logger.request.benchmark', 'application'));
        } catch (Exception $e) {
            return null;
        }
    }
}

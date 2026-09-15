<?php

namespace TomatoPHP\FilamentLogger\Interpolations;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use TomatoPHP\FilamentLogger\Contracts\InterpolationContract;

/**
 * Class BaseInterpolation
 */
abstract class BaseInterpolation implements InterpolationContract
{
    protected Collection $logCollection;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    public function __construct()
    {
        $this->logCollection = collect([]);
    }

    public function getLogger(): Collection
    {
        return $this->logCollection;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * Escape string
     */
    protected function escape(string $text): string
    {
        return preg_replace('/\s/', '\\s', $text);
    }

    /**
     * Convert array or null to string
     */
    protected function convertToString($value): string
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (is_null($value)) {
            $value = 'null';
        }

        return $value;
    }

    protected function formatSizeUnits(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).'GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).'MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).'KB';
        } elseif ($bytes > 1) {
            $bytes .= 'B';
        } elseif ($bytes === 1) {
            $bytes .= ' byte';
        } else {
            $bytes = '0B';
        }

        return $bytes;
    }
}

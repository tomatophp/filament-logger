<?php

namespace TomatoPHP\FilamentLogger\LogCustomizers;

use Illuminate\Log\Logger;
use TomatoPHP\FilamentLogger\Formatters\LineWithHashFormatter;

/**
 * Class HashLogCustomizer
 */
class HashLogCustomizer
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(app(
                LineWithHashFormatter::class,
                ['format' => "[%datetime%] %hash% %channel%.%level_name%: %message% %context% %extra%\n"]
            ));
        }
    }
}

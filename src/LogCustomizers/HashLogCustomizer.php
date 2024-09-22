<?php

namespace TomatoPHP\FilamentLogger\LogCustomizers;

use TomatoPHP\FilamentLogger\Formatters\LineWithHashFormatter;
use \Illuminate\Log\Logger;

/**
 * Class HashLogCustomizer
 */
class HashLogCustomizer
{
    /**
     * Customize the given logger instance.
     *
     * @param Logger $logger
     * @return void
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

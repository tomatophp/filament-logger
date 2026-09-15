<?php

namespace TomatoPHP\FilamentLogger\Contracts;

/**
 * Interface InterpolationContract
 */
interface InterpolationContract
{
    public function interpolate(string $text): string;
}

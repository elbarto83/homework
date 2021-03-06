<?php

/**
 * CliApp Script
 *
 * @license https://github.com/elbarto83/homework/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace CliApp\Interfaces;

interface OvhClientInterface
{
    /**
     * Get Api Token for service weatherapi
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Get Condition  for current and next day for a city from Coordinate
     *
     * @param string $lat
     * @param string $lng
     * @return string[]
     */
    public function getForecastFromLatLon(string $lat, string $lng): array;
}

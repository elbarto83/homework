<?php

/**
 * CliApp Script
 *
 * @license https://github.com/elbarto83/homework/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace CliApp\Interfaces;

interface MuseumClientInterface
{
    /**
     * Return for every city from musement.com name,latitude and longitude
     *
     * @return string[]
     */
    public function getAllCities(): array;
}

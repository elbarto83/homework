<?php

/**
 * CliApp Script
 *
 * @license https://github.com/elbarto83/homework/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace CliApp;

use CliApp\Interfaces\MuseumClientInterface;
use CliApp\Interfaces\OvhClientInterface;
use Psr\Log\LoggerInterface;

class Console
{

    /**
     * @var MuseumClientInterface
     */
    protected $museumClient;


    /**
     * @var LoggerInterface
     */
    protected $output;


    /**
     * @var OvhClientInterface
     */
    protected $ovhClient;

    /**
     * @param OvhClientInterface    $ovhClient
     * @param MuseumClientInterface $museumClient
     * @param LoggerInterface       $output
     */
    public function __construct(OvhClientInterface $ovhClient, MuseumClientInterface $museumClient, LoggerInterface $output)
    {
        $this->museumClient = $museumClient;
        $this->ovhClient = $ovhClient;
        $this->output = $output;
    }

    /**
     * Get All City From
     *
     * Return for every city name,latitude and longitude
     *
     */
    public function run(): void
    {
        $cities = $this->museumClient->getAllCities();

        foreach ($cities as $city) {
            if (isset($city['name']) && isset($city['latitude']) && isset($city['longitude'])) {
                $cond = $this->ovhClient->getForecastFromLatLon($city['latitude'], $city['longitude']);
                if (isset($cond[0]) && isset($cond[1])) {
                    $this->output->info("Processed city " . $city['name'] . " | " . $cond[0] . " - " . $cond[1]);
                }
            }
        }
    }
}

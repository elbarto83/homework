<?php

/**
 * CliApp Script
 *
 * @license https://github.com/elbarto83/homework/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace CliApp\Services;

use CliApp\Interfaces\MuseumClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class MuseumClient implements MuseumClientInterface
{

    /**
     * @var ClientInterface
     */
    protected $httpClient;


    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @param string             $apiUrl
     * @param ClientInterface    $container
     * @param LoggerInterface    $logger
     */
    public function __construct($apiUrl, ClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->apiUrl = $apiUrl;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * Get All City From
     *
     * Return for every city name,latitude and longitude
     *
     * @param string $lat
     * @param string $long
     * @return string[]
     */
    public function getAllCities(): array
    {

        try {
            $cities = [];
            $res = $this->httpClient->request(
                'GET',
                $this->apiUrl . "/api/v3/cities"
            );
            $body = (string) $res->getBody();
            $data =  json_decode($body, true);


            foreach ($data as $city) {
                if (isset($city['name']) && isset($city['latitude']) && isset($city['longitude'])) {
                    $cities[] = [
                        'name' => $city['name'],
                        'latitude' => $city['latitude'],
                        'longitude' => $city['longitude']
                    ];
                }
            }

            return $cities;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return [];
        }
    }
}

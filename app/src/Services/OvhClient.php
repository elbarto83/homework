<?php

/**
 * CliApp Script
 *
 * @license https://github.com/elbarto83/homework/LICENSE.md (MIT License)
 */

declare(strict_types=1);

namespace CliApp\Services;

use CliApp\Interfaces\OvhClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class OvhClient implements OvhClientInterface
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
    protected $token;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @param string             $token
     * @param string             $apiUrl
     * @param ClientInterface    $container
     * @param LoggerInterface    $logger
     */
    public function __construct($token, $apiUrl, ClientInterface $httpClient, LoggerInterface $logger = null)
    {
        $this->token = $token;
        $this->apiUrl = $apiUrl;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * Get Api Token
     *
     * Return Api Token for service weatherapi
     *
     * @return String
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get Condition for City From Coordinate
     *
     * Return Condition  for current and next day for a city from Coordinate
     *
     * @param string $lat
     * @param string $long
     * @return string[]
     */
    public function getForecastFromLatLon($lat, $long): array
    {

        try {
            $token = $this->token;

            $res = $this->httpClient->request(
                'GET',
                $this->apiUrl . "forecast.json?key=$token&q=$lat,$long&days=2"
            );
            $data = (string) $res->getBody();

            $body =  json_decode($data, true);

            return [
                $body['forecast']['forecastday'][0]['day']['condition']['text'] ?? "",
                $body['forecast']['forecastday'][1]['day']['condition']['text'] ?? ""
            ];
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return ["",""];
        }
    }
}

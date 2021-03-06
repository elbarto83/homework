<?php

namespace CliApp\Tests;

use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use CliApp\Services\OvhClient;

class OvhClientTest extends TestCase
{

    public function testResponse(): void
    {

        $logger = new Logger('null');
        $logger->pushHandler(new NullHandler());

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], '{"location":{},"current":{},"forecast":{"forecastday":[{"date":"2021-03-05","date_epoch":1614902400,"day":{"condition":{"text":"Patchy rain possible","icon":"","code":1063},"uv":6.0},"astro":{},"hour":[]},{"date":"2021-03-06","date_epoch":1614988800,"day":{"condition":{"text":"Patchy rain possible","icon":"","code":1063},"uv":1.0},"astro":{},"hour":[]}]}}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $ovhClient = new OvhClient("token", "apiurl", $client, $logger);

        $condition = $ovhClient->getForecastFromLatLon("1", "2");

        $this->assertEquals(
            $condition,
            ["Patchy rain possible","Patchy rain possible"]
        );
    }

    public function testMalformedResponse(): void
    {

        $logger = new Logger('null');
        $logger->pushHandler(new NullHandler());

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], 'nojson'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $ovhClient = new OvhClient("token", "apiurl", $client, $logger);

        $condition = $ovhClient->getForecastFromLatLon("1", "2");

        $this->assertEquals($condition,["",""]);
    }

}

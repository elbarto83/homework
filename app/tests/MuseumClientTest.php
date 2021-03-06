<?php

namespace CliApp\Tests;

use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use Monolog\Handler\NullHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use CliApp\Services\MuseumClient;

class MuseumClientTest extends TestCase
{

    public function testResponse(): void
    {

        $logger = new Logger('null');
        $logger->pushHandler(new NullHandler());

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], '[{"id":57,"top":false,"name":"Amsterdam","code":"amsterdam","content":"","weight":20,"latitude":52.374,"longitude":4.9,"country":{"id":124,"name":"Netherlands","iso_code":"NL"},"cover_image_url":"","url":"","activities_count":154,"time_zone":"Europe/Amsterdam","list_count":1,"venue_count":23,"show_in_popular":true}]'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $museumClient = new MuseumClient("", $client, $logger);

        $cities = $museumClient->getAllCities();

        $this->assertEquals(
            $cities,
            [["latitude" => 52.374,"longitude" => 4.9,"name" => "Amsterdam"]]
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

        $museumClient = new MuseumClient("", $client, $logger);

        $cities = $museumClient->getAllCities();

        $this->assertEmpty($cities);
    }
}

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
use CliApp\Services\MuseumClient;
use Monolog\Handler\StreamHandler;    
use Monolog\Formatter\LineFormatter;

use CliApp\Console;

class AppClientTest extends TestCase
{

    public function testRun(): void
    {

        $filepath = './logs/testunit.log';

        $logger = new Logger('null');
        $logger->pushHandler(new NullHandler());

        //Cli Logger
        $streamHandler = new StreamHandler($filepath, Logger::DEBUG);
        $streamHandler->setFormatter(new LineFormatter("%message%\n"));
        $output = new Logger('output');
        $output->pushHandler($streamHandler);
  
        //OvhClient
        $mockHandlerOvh = new MockHandler([
            new Response(200, [], '{"location":{},"current":{},"forecast":{"forecastday":[{"date":"2021-03-05","date_epoch":1614902400,"day":{"condition":{"text":"Patchy rain possible","icon":"","code":1063},"uv":6.0},"astro":{},"hour":[]},{"date":"2021-03-06","date_epoch":1614988800,"day":{"condition":{"text":"Patchy rain possible","icon":"","code":1063},"uv":1.0},"astro":{},"hour":[]}]}}'),
        ]);
        $handlerStackOvh = HandlerStack::create($mockHandlerOvh);
        $httpOvh = new Client(['handler' => $handlerStackOvh]);

        $ovhClient = new OvhClient("token", "apiurl", $httpOvh, $logger);


        //MuseumClient
        $mockHandlerMus = new MockHandler([
            new Response(200, [], '[{"id":57,"top":false,"name":"Amsterdam","code":"amsterdam","content":"","weight":20,"latitude":52.374,"longitude":4.9,"country":{"id":124,"name":"Netherlands","iso_code":"NL"},"cover_image_url":"","url":"","activities_count":154,"time_zone":"Europe/Amsterdam","list_count":1,"venue_count":23,"show_in_popular":true}]'),
        ]);
        $handlerStackMus = HandlerStack::create($mockHandlerMus);
        $clientMus = new Client(['handler' => $handlerStackMus]);

        $museumClient = new MuseumClient("", $clientMus, $logger);

        //App
        $app = new Console($ovhClient,$museumClient,$output);

        $app->run();
        $this->assertEquals(
            md5_file($filepath),
            md5("Processed city Amsterdam | Patchy rain possible - Patchy rain possible\n")
        );
        unlink($filepath);
    }



}

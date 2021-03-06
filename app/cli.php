<?php
require_once __DIR__.'/vendor/autoload.php';

use GuzzleHttp\Client;
use CliApp\Services\MuseumClient;
use CliApp\Services\OvhClient;
use CliApp\Console;
use Symfony\Component\Dotenv\Dotenv;
use Monolog\Logger;  
use Monolog\Handler\StreamHandler;    
use Monolog\Formatter\LineFormatter;

//Loading env
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/env');

//Cli Logger
$clilog = new Logger('log');  
$clilog->pushHandler(new StreamHandler('./logs/cli.log', Logger::INFO));    

//Output Logger
$streamHandler = new StreamHandler('php://stdout', Logger::DEBUG);
$streamHandler->setFormatter(new LineFormatter("%message%\n"));
$output = new Logger('output');
$output->pushHandler($streamHandler);

//GuzzleHttp
$httpClient = new Client();

//MuseumClient
$museumClient = new MuseumClient($_ENV['MUSEUM_APIURL'],$httpClient,$clilog);

//OvhClient
$ovhClient = new OvhClient($_ENV['OVH_TOKEN'],$_ENV['OVH_APIURL'],$httpClient,$output);

//App
$app = new Console($ovhClient,$museumClient,$output);

$app->run();

<?php
require '../_default.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Monolog as Monolog;



$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            //'level' => \Monolog\Logger::DEBUG,
				'level' => 100,
				
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];
$app = new \Slim\App($config);


$loggerSettings = $container->get('settings')['logger'];
pre($loggerSettings);
//You can also access them in route callables via $this:

$app->get('/', function ($request, $response, $args) {
    $loggerSettings = $this->get('settings')['logger'];
	 pre($loggerSettings);
    return $response;
});

$app->run();


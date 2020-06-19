<?php

error_reporting(E_ERROR | E_STRICT);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('magic_quotes_gpc', 'off');


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

	
//require '../vendor/autoload.php';


require_once '../App/_define.php'; 
require_once '../App/Model/HomeData.php';  
require_once '../App/exceptions.php';


//$app = new SlimApp(['settings'=> $config]);
$app = new \Slim\App;
$app->get('/hello2/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});



$app->run();
<?php

error_reporting(E_ERROR | E_STRICT);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('magic_quotes_gpc', 'off');


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

//https://frend.tistory.com/10?category=711253
$app->get('/api/customers' , function(Request $request , Response $response){
    echo 'CUSTOMERS';
});

$app->get('/api/person', function ($request, $response, $args) {

$payload=[];
array_push($payload, array("name"=>"Bob"  ,"birth-year"=>1993));
array_push($payload, array("name"=>"Alice","birth-year"=>1995));

 return $response->withJson($payload,200);

});


	
$app->run();
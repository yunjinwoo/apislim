<?php

require '../_default.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

$app->add(function ($request, $response, $next) {
	$response->getBody()->write('BEFORE');
	$response = $next($request, $response);
	$response->getBody()->write('AFTER');

	return $response;
});

$app->get('/', function ($request, $response, $args) {
	$response->getBody()->write(' <a href="'.$_SERVER['PHP_SELF'].'/sample2">[Hello]</a> ');

	return $response;
});


###sample2
$mw = function ($request, $response, $next) {
    $response->getBody()->write('$mw BEFORE');
    $response = $next($request, $response);
    $response->getBody()->write('$mw AFTER');

    return $response;
};

$app->get('/sample2', function ($request, $response, $args) {
	$response->getBody()->write(' Hello ');

	return $response;
})->add($mw);


###테스트
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});


$app->run();
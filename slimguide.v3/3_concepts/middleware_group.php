<?php

require '../_default.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

$app->get('/', function ($request, $response) {
	$t = ' <a href="'.$_SERVER['PHP_SELF'].'/utils/date">date</a>';
	$t .= ' <a href="'.$_SERVER['PHP_SELF'].'/utils/time">time</a>';
    return $response->getBody()->write('Hello World'.$t);
});

$app->group('/utils', function () use ($app) {
    $app->get('/date', function ($request, $response) {
        return $response->getBody()->write(date('Y-m-d H:i:s'));
    });
    $app->get('/time', function ($request, $response) {
        return $response->getBody()->write(time());
    });
})->add(function ($request, $response, $next) {
    $response->getBody()->write('It is now ');
    $response = $next($request, $response);
    $response->getBody()->write('. Enjoy!');

    return $response;
});


$app->run();
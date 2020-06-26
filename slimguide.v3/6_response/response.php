<?php
require '../_default.php';


/**
How to get the Response object
<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


$app = new \Slim\App;
$app->get('/foo', function (ServerRequestInterface $request, ResponseInterface $response) {
    // Use the PSR-7 $response object

    return $response;
});
$app->run();


The PSR-7 response object is injected into your Slim application middleware as the second 
argument of the middleware callable like this:
<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$app = new \Slim\App;
$app->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
    // Use the PSR-7 $response object

    return $next($request, $response);
});
// Define app routes...
$app->run();


######################################################
The Response Status

$status = $response->getStatusCode();

You can copy a PSR-7 Response object and assign a new status code like this:

$newResponse = $response->withStatus(302);


 * 
 * 
 *  */
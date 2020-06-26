<?php
require '../_default.php';


/**
Route strategies
The route callback signature is determined by a route strategy. 
 * By default, Slim expects route callbacks to accept the request, response, 
 * and an array of route placeholder arguments. This is called the RequestResponse strategy. 
 * However, you can change the expected route callback signature by simply using a different strategy. 
 * As an example, Slim provides an alternative strategy called RequestResponseArgs 
 * that accepts request and response, plus each route placeholder as a separate argument. 
 * 
 * Here is an example of using this alternative strategy; 
 * simply replace the foundHandler dependency provided by the default \Slim\Container:
*/
$c = new \Slim\Container();
$c['foundHandler'] = function() {
    return new \Slim\Handlers\Strategies\RequestResponseArgs();
};

$app = new \Slim\App($c);
$app->get('/hello/{name}', function ($request, $response, $name) {
    return $response->write($name);
});
#You can provide your own route strategy by implementing the \Slim\Interfaces\InvocationStrategyInterface.

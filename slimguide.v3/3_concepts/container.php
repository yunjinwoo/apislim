<?php

require '../_default.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$container = new \Slim\Container;
$app = new \Slim\App($container);

$container = $app->getContainer();
$container['myService'] = function ($container) {
    $myService = new MyService();
    return $myService;
};


$app->get('/foo', function ($req, $res, $args) {
    $myService = $this->get('myService');
	 
	 echo $myService->test();
    return $res;
});

$app->get('/foo2', function ($req, $res, $args) {
    if($this->has('myService')) {
        $myService = $this->myService;
    }
	 $res->getBody()->write($myService->test());
    return $res;
});

$app->run();

class myService{
	function test(){
		return 'call test';
	}
}
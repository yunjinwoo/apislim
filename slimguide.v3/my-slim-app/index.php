<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('magic_quotes_gpc', 'off');


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require_once '../../engine/_define.php'; 

$config = include('../../src/config.php');
//$app = new SlimApp(['settings'=> $config]);
$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});


$app->get('/datafss/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
	 $DartFss = new DataFss;
	 $DartFss->setOrder('fss_idx desc')
				->setLimit( 50 );
	 
	 $list = $DartFss->getList2();

		pre($list);
		echo 'awerawer';
		
		//	 $this->view->render($response, 'app/index.twig', [
//            'widgets' => $widgets
//        ]);

	 
    $response->getBody()->write("Hello, $name");

    return $response;
});

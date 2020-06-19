<?php


error_reporting(E_ERROR | E_STRICT);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('magic_quotes_gpc', 'off');


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

	
//require '../vendor/autoload.php';


require_once '../App/_define.php'; 



$config = ['settings' => [
    'addContentLengthHeader' => false,
]];


// Database information
$settings = array(
		'driver'    => 'mysql',
		'host'      => "localhost",
		'database'  => "cargo",
		'username'  => "cargo",
		'password'  => "cargo12#",
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => 'api_',
);

// Bootstrap Eloquent ORM
$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory();
$conn = $connFactory->make($settings);
$resolver = new \Illuminate\Database\ConnectionResolver();
$resolver->addConnection('default', $conn);
$resolver->setDefaultConnection('default');
\Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
		  
		  
$app = new \Slim\App($config);

// Define app routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});

// Run app
$app->run();

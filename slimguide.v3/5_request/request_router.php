<?php
require '../_default.php';


/**

Route Object
$app->get('/course/{id}', Video::class.":watch")->add(Permission::class)->add(Auth::class);

//.. In the Permission Class's Invoke
### @var $route \Slim\Route 
$route = $request->getAttribute('route');
$courseId = $route->getArgument('id');


 * 
 * 	 


Media Type Parsers

application/x-www-form-urlencoded
application/json
application/xml & text/xml


// Add the middleware
$app->add(function ($request, $response, $next) {
    // add media parser
    $request->registerMediaTypeParser(
        "text/javascript",
        function ($input) {
            return json_decode($input, true);
        }
    );

    return $next($request, $response);
});









Attributes
With PSR-7 it is possible to inject objects/values into the request object for further processing. 
In your applications middleware often need to pass along information to your route closure 
and the way to do is it is to add it to the request object via an attribute.

Example, Setting a value on your request object.



$app->add(function ($request, $response, $next) {
    $request = $request->withAttribute('session', $_SESSION); //add the session storage to your request as [READ-ONLY]
    return $next($request, $response);
});
Example, how to retrieve the value.

$app->get('/test', function ($request, $response, $args) {
    $session = $request->getAttribute('session'); //get the session from the request

    return $response->write('Yay, ' . $session['name']);
});



 * 
 * 
 *  */
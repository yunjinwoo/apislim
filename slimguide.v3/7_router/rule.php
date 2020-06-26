<?php
require '../_default.php';


/**
GET Route
You can add a route that handles only GET HTTP requests with the Slim application’s get() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->get('/books/{id}', function ($request, $response, $args) {
    // Show book identified by $args['id']
});

/** 
POST Route
You can add a route that handles only POST HTTP requests with the Slim application’s post() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
  */
$app = new \Slim\App();
$app->post('/books', function ($request, $response, $args) {
    // Create new book
});

/**
PUT Route
You can add a route that handles only PUT HTTP requests with the Slim application’s put() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->put('/books/{id}', function ($request, $response, $args) {
    // Update book identified by $args['id']
});


/**
DELETE Route
You can add a route that handles only DELETE HTTP requests with the Slim application’s delete() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->delete('/books/{id}', function ($request, $response, $args) {
    // Delete book identified by $args['id']
});




/**
OPTIONS Route
You can add a route that handles only OPTIONS HTTP requests with the Slim application’s options() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->options('/books/{id}', function ($request, $response, $args) {
    // Return response headers
});


/**
PATCH Route
You can add a route that handles only PATCH HTTP requests with the Slim application’s patch() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->patch('/books/{id}', function ($request, $response, $args) {
    // Apply changes to book identified by $args['id']
});








/**
Any Route
You can add a route that handles all HTTP request methods with the Slim application’s any() method. It accepts two arguments:

The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->any('/books/[{id}]', function ($request, $response, $args) {
    // Apply changes to books or book identified by $args['id'] if specified.
    // To check which method is used: $request->getMethod();
});
#Note that the second parameter is a callback. 
#You could specify a Class (which need a __invoke() implementation) instead of a Closure. 
#You can then do the mapping somewhere else:
$app->any('/user', 'MyRestfulController');



/**
Custom Route
You can add a route that handles multiple HTTP request methods with the Slim application’s map() method. It accepts three arguments:
*/
$app = new \Slim\App();
$app->map(['GET', 'POST'], '/books', function ($request, $response, $args) {
    // Create new book or list all books
});



/**
Array of HTTP methods
The route pattern (with optional named placeholders)
The route callback
 */
$app = new \Slim\App();
$app->map(['GET', 'POST'], '/books', function ($request, $response, $args) {
    // Create new book or list all books
});



/**
Redirect helper
You can add a route that redirects GET HTTP requests 
to a different URL with the Slim application’s redirect() method. 
It accepts three arguments:

The route pattern (with optional named placeholders) to redirect from
The location to redirect to, which may be a string or a Psr\Http\Message\UriInterface
The HTTP status code to use (optional; 302 if unset)
*/
$app = new \Slim\App();
$app->redirect('/books', '/library', 301);
#redirect() routes respond with the status code requested 
#and a Location header set to the second argument.




/*
Closure binding
If you use a Closure instance as the route callback, 
the closure’s state is bound to the Container instance. 
This means you will have access to the DI container instance 
inside of the Closure via the $this keyword:
*/
$app = new \Slim\App();
$app->get('/hello/{name}', function ($request, $response, $args) {
    // Use app HTTP cookie service
    $this->get('cookies')->set('name', [
        'value' => $args['name'],
        'expires' => '7 days'
    ]);
});
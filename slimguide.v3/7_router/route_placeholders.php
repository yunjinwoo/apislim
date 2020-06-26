<?php

/* 
Each routing method described above accepts 
a URL pattern that is matched against the current HTTP request URI. 
Route patterns may use named placeholders to dynamically match HTTP request URI segments.

Format
A route pattern placeholder starts with a {, followed by the placeholder name, ending with a }. 
This is an example placeholder named name:
 */
$app = new \Slim\App();
$app->get('/hello/{name}', function ($request, $response, $args) {
    echo "Hello, " . $args['name'];
});

/*
 * Optional segments
 */
##To make a section optional, simply wrap in square brackets:
$app->get('/users[/{id}]', function ($request, $response, $args) {
    // responds to both `/users` and `/users/123`
    // but not to `/users/`
});
##Multiple optional parameters are supported by nesting:

$app->get('/news[/{year}[/{month}]]', function ($request, $response, $args) {
    // reponds to `/news`, `/news/2016` and `/news/2016/03`
});
#For “Unlimited” optional parameters, you can do this:
$app->get('/news[/{params:.*}]', function ($request, $response, $args) {
    $params = explode('/', $args['params']);

    // $params is an array of all the optional segments
});
#In this example, a URI of /news/2016/03/20 would result in the $params array containing three elements: ['2016', '03', '20'].


/**
 * Regular expression matching
By default the placeholders are written inside {} 
and can accept any values. However, placeholders can also 
require the HTTP request URI to match a particular regular expression. 

If the current HTTP request URI does not match a placeholder regular expression, 
the route is not invoked. This is an example placeholder named id that requires one or more digits.
**/
$app = new \Slim\App();
$app->get('/users/{id:[0-9]+}', function ($request, $response, $args) {
    // Find user identified by $args['id']
});
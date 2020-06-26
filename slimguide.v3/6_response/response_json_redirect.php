<?php
require '../_default.php';


/**
Returning JSON
Slim’s Response object has a custom method withJson($data, $status, $encodingOptions) 
to help simplify the process of returning JSON data.

The $data parameter contains the data structure you wish returned as JSON. 
$status is optional, and can be used to return a custom HTTP code. 
$encodingOptions is optional, and are the same encoding options used for json_encode().


In it’s simplest form, JSON data can be returned with a default 200 HTTP status code.
-------------------------------------------------
$data = array('name' => 'Bob', 'age' => 40);
$newResponse = $oldResponse->withJson($data);
 * 
 * 
We can also return JSON data with a custom HTTP status code.
-------------------------------------------------
$data = array('name' => 'Rob', 'age' => 40);
$newResponse = $oldResponse->withJson($data, 201);


The Content-Type of the Response is automatically set to application/json;charset=utf-8.
If there is a problem encoding the data to JSON, 
a \RuntimeException($message, $code) is thrown containing the values of 
json_last_error_msg() as the $message and json_last_error() as the $code.



Reminder
The Response object is immutable. 
This method returns a copy of the Response object that has a new Content-Type header. 
This method is destructive, and it replaces the existing Content-Type header. 
The Status is also replaced if a $status was passed when withJson() was called.


 * 
 * 
 * 
Returning a Redirect
Slim’s Response object has a custom method withRedirect($url, $status = null) 
when you wish to return a redirect to another URL. You provide the $url 
where you wish the client to be redirected to along with an optional $status code. 
The status code defaults to 302 if not provided.

return $response->withRedirect('/new-url', 301);


 * 
 * 
 *  */
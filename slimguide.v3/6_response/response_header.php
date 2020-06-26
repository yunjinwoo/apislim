<?php
require '../_default.php';


/**
The Response Headers

Get All Headers
$headers = $response->getHeaders();
foreach ($headers as $name => $values) {
    echo $name . ": " . implode(", ", $values);
}


Get One Header
$headerValueArray = $response->getHeader('Vary');
$headerValueString = $response->getHeaderLine('Vary');


Detect Header
if ($response->hasHeader('Vary')) {
    // Do something
}


Set Header
$newResponse = $oldResponse->withHeader('Content-type', 'application/json');



Append Header
$newResponse = $oldResponse->withAddedHeader('Allow', 'PUT');


Remove Header
$newResponse = $oldResponse->withoutHeader('Allow');

 * 
 * 
 *  */
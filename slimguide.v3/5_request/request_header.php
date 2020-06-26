<?php

/* 
 * Get All Headers
$headers = $request->getHeaders();
foreach ($headers as $name => $values) {
    echo $name . ": " . implode(", ", $values);
}

 *
 *
 * 
 * 
 * 
 * 
 * 
 *  
 * Get One Header
$headerValueArray = $request->getHeader('Accept');
 * 
 * 
 * 
 * Detect Header
if ($request->hasHeader('Accept')) {
    // Do something
}
 * 
 * 
 * 
 * 
 * 
 */


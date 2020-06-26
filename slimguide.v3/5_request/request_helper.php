<?php
require '../_default.php';


/**
Detect XHR requests
--------------------
POST /path HTTP/1.1
Host: example.com
Content-type: application/x-www-form-urlencoded
Content-length: 7
X-Requested-With: XMLHttpRequest

foo=bar
--------------------

if ($request->isXhr()) {
    // Do something
}


 * Content Type
$contentType = $request->getContentType();


 * 
 * Media Type
$mediaType = $request->getMediaType();
$mediaParams = $request->getMediaTypeParams();


 * Character Set
$charset = $request->getContentCharset();

 * Content Length
$length = $request->getContentLength();


 * Request Parameter
$foo = $request->getServerParam('HTTP_NOT_EXIST', 'default_value_here');


 * 
 *  */
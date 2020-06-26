<?php
require '../_default.php';


/**
The Response Body
An HTTP response typically has a body. Slim provides a PSR-7 Response object with which you can inspect and manipulate the eventual HTTP response’s body.

Just like the PSR-7 Request object, the PSR-7 Response object implements the body as an instance of \Psr\Http\Message\StreamInterface. You can get the HTTP response body StreamInterface instance with the PSR-7 Response object’s getBody() method. The getBody() method is preferable if the outgoing HTTP response length is unknown or too large for available memory.

$body = $response->getBody();
Figure 12: Get HTTP response body
The resultant \Psr\Http\Message\StreamInterface instance provides the following methods to read from, iterate, and write to its underlying PHP resource.

getSize()
tell()
eof()
isSeekable()
seek()
rewind()
isWritable()
write($string)
isReadable()
read($length)
getContents()
getMetadata($key = null)
Most often, you’ll need to write to the PSR-7 Response object. You can write content to the StreamInterface instance with its write() method like this:

$body = $response->getBody();
$body->write('Hello');
Figure 13: Write content to the HTTP response body
You can also replace the PSR-7 Response object’s body with an entirely new StreamInterface instance. This is particularly useful when you want to pipe content from a remote destination (e.g. the filesystem or a remote API) into the HTTP response. You can replace the PSR-7 Response object’s body with its withBody(StreamInterface $body) method. Its argument MUST be an instance of \Psr\Http\Message\StreamInterface.

$newStream = new \GuzzleHttp\Psr7\LazyOpenStream('/path/to/file', 'r');
$newResponse = $oldResponse->withBody($newStream);
 * 
 * 
 * 
 *  */
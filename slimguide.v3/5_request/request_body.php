<?php

/* 
 * 
 * The Request Body
$parsedBody = $request->getParsedBody();

JSON requests are converted into associative arrays with json_decode($input, true).
XML requests are converted into a SimpleXMLElement with simplexml_load_string($input).
URL-encoded requests are converted into a PHP array with parse_str($input).
---------------------------------
$body = $request->getBody();

---------------------------------
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
 * 
 * 
 * 
 * Uploaded Files
$files = $request->getUploadedFiles();

getStream()
moveTo($targetPath)
getSize()
getError()
getClientFilename()
getClientMediaType()

 *
 * 
 */


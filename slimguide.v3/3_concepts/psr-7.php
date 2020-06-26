<?php

require_once '../_default.php';

/**
The PSR-7 interface provides these methods to transform Request and Response objects:

withProtocolVersion($version)
withHeader($name, $value)
withAddedHeader($name, $value)
withoutHeader($name)
withBody(StreamInterface $body)
The PSR-7 interface provides these methods to transform Request objects:

withMethod($method)
withUri(UriInterface $uri, $preserveHost = false)
withCookieParams(array $cookies)
withQueryParams(array $query)
withUploadedFiles(array $uploadedFiles)
withParsedBody($data)
withAttribute($name, $value)
withoutAttribute($name)
The PSR-7 interface provides these methods to transform Response objects:

withStatus($code, $reasonPhrase = '')
Refer to the PSR-7 documentation for more information about these methods.
 * 
 * https://www.php-fig.org/psr/psr-7/
 */
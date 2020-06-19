<?php


class HttpHeader
{
	static function location($str)
	{
		header('Location:'.$str) ;
		die();
	}
	static function charset($str)
	{
		header('Content-Type: text/html; charset='.$str);
	}
	
}
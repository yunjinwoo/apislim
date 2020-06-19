<?php
function strToPhoneArr($str){
	$a = array();
	$str = preg_replace('/[^0-9]/i','',$str);
	if( strlen($str) == 11 ){
		$a[0] = substr($str,0,3);
		$a[1] = substr($str,3,4);
		$a[2] = substr($str,7);
	}elseif( strlen($str) == 10 ){
		if( substr($str,0,2) == '02'){
			$a[0] = substr($str,0,2);
			$a[1] = substr($str,2,4);
			$a[2] = substr($str,6);
		}else{			
			$a[0] = substr($str,0,3);
			$a[1] = substr($str,3,3);
			$a[2] = substr($str,6);
		}
	}else{
		$a[0] = substr($str,0,-8);
		$a[1] = substr($str,-8,4);
		$a[2] = substr($str,-4);
	}
	return $a;
}


function count2($var){
	if( is_array($var) ){
		return count($var);
	}
	return 0;
}

function urlParamMake($f='', $amp='&amp;'){
	$aUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	parse_str($aUrl, $arr);
	if( is_array($f) ){
		foreach( $f as $v ){
			if(isset($arr[$v]))	unset($arr[$v]);
		}
	}else{
		if(isset($arr[$f]))	unset($arr[$f]);
	}
	
	return http_build_query($arr,'',$amp);
}

function getRequestUriType()
{
	$page_uri = $_SERVER['REQUEST_URI'];
	$pageGroup = array(
			'admin.php'		=> '계정관리'
		,	'dist_list.php' => '분배관리'
		,	'my_list.php'	=> '상담관리' 
		,	'my_user.php'	=> '상담관리상세'
		,	'dist_status.php' => '분배현황'
		,	'site_info.php' => '사이트관리'
		,	'login_log.php' => '접속관리');
	$s = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($page_uri , 'logout=on') !== false){
		return '로그아웃';
	}
	foreach ($pageGroup as $page => $title )
	{
		if(strpos($page_uri , $page) !== false){
			return $title;
		}
	}
	
	return '';
}

function getMobileAgent(){
	return array(
			"iphone","ipad","ipod","android","blackberry"
			,"opera Mini", "windows ce", "nokia", "sony" 
			,'sie-', 'lg', 'mot', 'samsung');
}
function isMobile(){
	$agentsArray = getMobileAgent();
	$s = strtolower($_SERVER['HTTP_USER_AGENT']);
	foreach ($agentsArray as $v )
	{
		if(strpos($s , $v) !== false){
			return true;
		}
	}
	return false;
}
function isMobileType(){
	$agentsArray = getMobileAgent();
	$s = strtolower($_SERVER['HTTP_USER_AGENT']);
	foreach ($agentsArray as $v )
	{
		if(strpos($s , $v) !== false){
			$ret = '';
			switch($v){
				case 'iphone' : 
				case 'ipad' : 
				case 'ipod' : 
					$ret = 'iphone';
					break;
				default:
					$ret = 'android';
					
			}
			return $ret;
		}
	}
	return '';
}

function getAgentType(){
	$s = strtolower($_SERVER['HTTP_USER_AGENT']);
	foreach (getMobileAgent() as $v )
	{
		if(strpos($s , $v) !== false){
			return $v;
		}
	}
	
	foreach (array('windows', 'linux', 'macintosh', 'powerpc') as $v )
	{
		if(strpos($s , $v) !== false){
			return 'PC-'.$v;
		}
	}
	
	return 'OTHER';
}




function addVar($title, $content)
{
	VarGroup::add($title, $content);
}
function getVar($title, $glue =' ' , $array_reverse = true)
{
	return VarGroup::get($title, $glue, $array_reverse);
}
function addVarStr($title, $content)
{
	VarGroup::addStr($title, $content);
}
function getVarStr($title)
{
	return VarGroup::getStr($title);
}
function addPre($a)
{
	VarGroup::pre($a);
}

class VarGroup
{
	static $var = array();
	static $pre = array();
	static $rule;
	static function rule($title, $glue = ' | ', $array_reverse = true){
		self::$rule[$title]['glue'] = $glue;
		self::$rule[$title]['reverse'] = $array_reverse;
	}
	static function getRule($title){
		self::$rule[$title]['glue'] = $glue;
		self::$rule[$title]['reverse'] = $array_reverse;
	}
	static function add($title, $content){
		self::$var[$title][] = $content;
	}
	static function get($title,$d_glue=', ', $d_reverse = false ){
		if( !isset(self::$var[$title]) ) return '';
		
		if( !isset(self::$rule[$title]) )
		{
			self::$rule[$title]['glue'] = $d_glue;
			self::$rule[$title]['reverse'] = $d_reverse;
		}
			
		$glue		= self::$rule[$title]['glue'];
		$reverse	= self::$rule[$title]['reverse'] ;
		
		$var = self::$var[$title];
		if( is_array(self::$var[$title]) )
		{
			if( $reverse )
				$var = implode($glue, array_reverse(self::$var[$title]) );
			else
				$var = implode($glue, self::$var[$title] );
		}

		return $var;
	}
	static function addStr($title, $content){
		self::$var[$title] = $content;
	}
	static function getStr($title){
		if( !isset(self::$var[$title]) ) return '';
		
		return self::$var[$title];
	}
	
	
	static function pre($cnt){
		ob_start();
		pre($cnt);
		$c = ob_get_contents();
		ob_clean();
		
		self::$pre[] = $c;
	}
	
	static function getPre(){		
		return implode(' ', self::$pre);
	}
}


function newline()
{
	return "\n" ;	
}
function tab()
{
	return "\t" ;	
}

function debug( $msg , $cnt = 5)
{
	//console::$logCnt = count(debug_backtrace()) ;
	echo nl2br(print_r(debug_backtrace(), true));
	die( "@<br />".$msg ) ;
}

function jslog()
{
	$tmp = func_get_args();
	$a = debug_backtrace() ;
	$a2 = explode("\n",print_r($tmp[0],true));
//	$txt = print_r($tmp[0],true);
	echo '<script>
		console.group("PHP: LINE '.$a[0]['line'].' '.$a[0]['file'] .'");'.chr(13);
	foreach( $a2 as $v ){
		echo '	console.log("'.$v.'");'.chr(13);
	}
	echo 'console.groupEnd();
		</script>';
}
function pre()
{
	$tmp = func_get_args();
	$arr = $tmp[0];
	unset($tmp[0]);
	$a = debug_backtrace() ;
	$sub = '';
	if( isset($tmp[1]) ){
		$sub = print_r($tmp,true);
	}
	
	echo chr(13).'<pre>'.chr(13).'LINE '.$a[0]['line'].' '.$a[0]['file'] .chr(13).print_r($arr,true).chr(13).$sub.chr(13).'</pre>'.chr(13);
	
}

function preTest(){
	if( G::get('log') == 'on' ){
		pre(func_get_args());
	}
}


$__MSG__ = '' ;
function msg( $msg ) 
{
	global $__MSG__ ;
	$__MSG__ = $msg ;
}
function msg_print()
{
	global $__MSG__ ;
	if( !empty($__MSG__) )
		jsPrint('alert("'.$__MSG__.'");') ;
}
function jsPrint($s)
{
	echo '<script type="text/javascript">
		'.$s.'
</script>';	
}
function exitJs( $msg = '' , $href = '' )		
{
	if( is_file(_HTML_TOP_) )
		include _HTML_TOP_;
	
	if( !empty($msg) ) jsPrint ('alert("'.$msg.'")') ;
	if( !empty($href) ) 
		if( strpos($href,'history.') !== false )
			jsPrint ($href);
		elseif( strpos($href,'.close()') !== false )
			jsPrint ($href);
		else
			jsPrint ('location.replace("'.$href.'")') ;
	
	exit ;
}


/**
 * 2018-04-26
 * 함수 모음
 */


/**
 * 2013-08-02
 * 함수 모음
 */
class F
{
	static function post_value_empty($arr, $arr_post = ''){
		if( !is_array($arr_post) ){
			$arr_post = $_POST;
		}
		foreach( $arr as $field => $msg ){
			if( empty($arr_post[$field]) ){
				return $msg;
			}
		}
		return '';
	}
	static function nf($n){
		if(!is_numeric($n)){
			return 0;
		}
		return number_format($n);
	}
	/**
	 * 2013-08-02
	 * 변수가 날짜 형식의 문자열인지 아닌지 판단하는 함수
	 * @param string 2000-10-10
	 * @return true or false 
	 */
	static function isDate($day)
	{
		$day = preg_replace( '/[-.]/' , '', $day);
		if( strlen($day) != 8 ) return false ;
		if( !is_numeric($day) ) return false ;

		return true ;
	}
	/**
	 * 2013-08-21
	 * 변수가 날짜 형식의 문자열인지 아닌지 판단하는 함수
	 * @param string 2000-10-10 00:00:00
	 * @return true or false 
	 */
	static function isDatetime($day)
	{
		$day = preg_replace( '/[-.:\s]/' , '', $day);
		if( strlen($day) != 14 ) return false ;
		if( !is_numeric($day) ) return false ;

		return true ;
	}
	
	/**
	 * 2013-08-12 오류 함수 
	 * 변수가 양수가 아니면 설정값 반환 
	 * @param mixed
	 * @param int
	 * @return int
	 */
	static function number($var,$def = -1 ){
		return (!is_numeric($var) || $var <= 0 ) ? $def : $var ;
	}
	/**
	 * 2013-08-21 오류 함수 
	 * 변수가 날짜형식이 현재 날짜 반환
	 * @param mixed
	 * @return string Y-m-d H:i:s
	 */
	static function datetime($var,$format = 'Y-m-d H:i:s',$day = '0'){
		return F::isDatetime($var) ? $var : date($format, strtotime( $day.' day')) ;
	}
	
	/**
	 * 2013-08-21 오류 함수 
	 * 변수가 날짜형식이 현재 날짜 반환
	 * @param mixed
	 * @return string Y-m-d H:i:s
	 */
	static function date($var,$format = 'Y-m-d',$day = '0'){
		return F::isDate($var) ? $var : date($format, strtotime( $day.' day')) ;
	}
	
	/**
	 * 2013-08-21 오류 함수 
	 * 변수가 날짜형식이 현재 날짜 반환
	 * @param mixed
	 * @return string Y-m
	 */
	static function yyyy_mm($var,$format = 'Y-m',$day = '0'){
		
		$tmp = preg_replace( '/[-.]/' , '', $var);
		$flag = true ; 
		if( strlen($tmp) != 6 ) $flag = false ;
		if( !is_numeric($tmp) ) $flag = false ;

		return $flag ? $var : date($format, strtotime( $day.' day')) ;
	}
	
	/**
	 * 2019-02-12 
	 * 날짜차일를 숫자형식으로 반환??
	 * @param mixed
	 * @return string Y-m-d H:i:s
	 */
	static function datediff($date_big,$format = '',$time2=''){
		$time1 = strtotime( $date_big );
		if( empty($time2) ){
			$time2 = strtotime('now');
		}
		
		$time = $time1 - $time2;
		$day = floor($time / (24*60*60));
		$time = $time % (24*60*60);
		
		$hour = floor($time / (60*60));
		$time = $time % (60*60);
		
		$min = floor($time / 60);
		$sec = $time % 60;
		
		$format = str_replace('%d', $day, $format);
		$format = str_replace('%H', $hour, $format);
		$format = str_replace('%m', $min, $format);
		$format = str_replace('%s', $sec, $format);
		return $format ;
	}
	
	/**
	 * 2013-09-06 유투브 형식의 url로 반환 
	 * 
	 * @param string   http://www.youtube.com/watch?v=GV4-hMYsR6k
	 * @return string //www.youtube.com/embed/XXXXX
	 */
	static function youtube($link){
		parse_str(parse_url($link, PHP_URL_QUERY), $a);
		$s = '';
		if( isset($a['v']))
			$s = '//www.youtube.com/embed/'.$a['v'];
		
		return $s;
	}
	
		
	/**
	 * 2013-08-21 오류 함수 
	 * 변수가 문자형식이 아니면 설정값 반환 
	 * @param mixed
	 * @return string
	 */
	static function str($var,$dep=''){
		return empty($var) ? $dep : $var ;
	}
	
	/**
	 * 2013-08-21 오류 함수 
	 * 변수가 Y,N 이 아니면 N 
	 * @param mixed
	 * @return string
	 */
	static function YN($var){
		return $var == 'Y' ? 'Y' : 'N' ;
	}
	
	/**
	 * 2013-10-07 문자자르기 함수
	 * 
	 * @param string $s 대상문자열
	 * @param int $len 문자열길이
	 * @param string $endpix 적용후 뒤에 붙는 문자
	 * @return string
	 */
	static function mbsubstr($s,$len,$endpix='...'){
		if( mb_strlen( $s ) > $len )
			return mb_substr( $s, 0, $len ,'UTF-8').$endpix ;
		else return $s ;
	}
	
	/**
	 * 2013-10-07 문자자르기 함수
	 * $find 이후 문자를 반환한다.
	 * 
	 * @param string $str 출력대상 문자열
	 * @param string $find 찾을 문자열
	 * @return string 
	 */
	static function posFind($str,$find){
		$pos = strpos($str, $find);
		if( $pos === false )
			return $str;
		else
			return substr($str,$pos);
	}
	
	
	/**
	 * 2013-10-07 문자자르기 함수
	 * $find 이후 문자를 반환한다.
	 * 
	 * @param string $str 출력대상 문자열
	 * @param string $find 찾을 문자열
	 * @return string 
	 */
	static function find($str,$find,$true = 'on',$false='off'){
		$pos = strpos($str, $find);
		if( $pos !== false )
			return $true;
		else
			return $false;
	}
	
	/**
	 * 2013-10-07 html 특수문자 변환
	 * & => &amp; , &lt;, $gt; .......
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function htmlChar($s){
		return htmlspecialchars($s);
	}
	
	/**
	 * 2013-11-22 수가 사이에 있는지 판단
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function isBetween($s,$a,$b){
		return ( $s >= $a && $s <= $b );
	}
	
	
	/**
	 * 2019-01-30 숫자만 넘긴다
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function getNum($s){
		return preg_replace('/[^0-9]/i','',$s);
	}
	/**
	 * 2019-01-30 숫자만 넘긴다
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function getNumJum($s){
		return self::get_preg($s, '/[^0-9\.]/i');
	}
	/**
	 * 2019-01-30 숫자만 넘긴다
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function get_preg($s, $patten = '/[^0-9]/i'){
		return preg_replace($patten,'',$s);
	}
	
	/**
	 * 2019-01-30 숫자 콤마적용
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function price($s){
		if(is_numeric($s)){
			return number_format($s);
		}else{
			return 0;
		}
	}
	
	/**
	 * 2019-01-30 str_pad 함수
	 * 
	 * @param string 변환대상
	 * @return string 변환값
	 */
	static function pad($s,$l,$pix='0',$t=STR_PAD_LEFT){
		return str_pad($s,$l,$pix,$t);
	}
	
	/**
	 * 2019-02-27 openssl_encrypt,openssl_decrypt 이용한 함수
	 * 
	 * @param string 변환대상
	 * [@param string $key]
	 * [@param string $iv]
	 * [@param string $method]
	 * @return string 변환값
	 */
	static function cryptEncode($str,$pass='',$iv='',$method=''){
		$C = new CryptFunc($pass,$iv,$method);
		return $C->encode($str);
	}
	static function cryptDecode($enStr,$pass='',$iv='',$method=''){
		$C = new CryptFunc($pass,$iv,$method);
		return $C->decode($enStr);
	}
	static function cryptHash($str){
		$C = new CryptFunc();
		return $C->hash($str);
	}
	static function cryptVerify($str,$enStr){
		$C = new CryptFunc();
		return $C->verify($str,$enStr);
	}
	
	/**
	 * 2019-03-11 램덤문자반환
	 * 
	 * @param string 길이
	 * @return string 변환값
	 */
	static function randStr($len){
		return str_pad(rand(0,pow(10,$len)), $len, '0' , STR_PAD_LEFT);
	}
}

class Q
{
	static function betweenMonth($yyyymm)
	{
		$yyyy	= substr($yyyymm,0,4) ;
		$mm		= substr($yyyymm,-2) ;
		$date	= $yyyy.'-'.$mm.'-01';
		$date	= F::date($date);
		
		$mm = $mm+1;
		if( $mm >= 13 ){
			$mm = 01;
			$yyyy++;
		} 
		$date_end = date('Y-m-d', strtotime($yyyy.'-'.($mm).'-00'));
		
		return 'BETWEEN \''.$date.' 00:00\' AND \''.$date_end.' 23:59:59.999\'';
	}
}

class A
{
	static function inNumber($arr){
		$ret = [];
		foreach($arr as $k => $v ){
			if(!is_numeric($v)){
				continue;
			}
			$ret[$k] = $v;
		}
		return $ret;
	}
	static function inNotEmpty($arr){
		$ret = [];
		foreach($arr as $k => $v ){
			if(empty($v)){
				continue;
			}
			$ret[$k] = $v;
		}
		return $ret;
	}
	/**
	 * 2013-08-12 
	 * 변수가 숫자형식이 아니면 
	 * @param array 찾을 배열
	 * @param string 변수명
	 * @param int 기본값
	 * @return number
	 */
	static function round(&$arr,$k,$def=0){
		if(!is_numeric($def)) $def = -1 ;
		
		if( isset($arr[$k]) && is_numeric($arr[$k]) ) return round($arr[$k],1) ;
		else return $def ;
	}
	
	/**
	 * 2013-08-12 
	 * 변수가 숫자형식이 아니면 
	 * @param array 찾을 배열
	 * @param string 변수명
	 * @param int 기본값
	 * @return number
	 */
	static function number(&$arr,$k,$def=0){
		if(!is_numeric($def)) $def = -1 ;
		
		if( isset($arr[$k]) && is_numeric($arr[$k]) ) return trim($arr[$k]) ;
		else return $def ;
	}
	
	/**
	 * 2013-08-13 
	 * 변수가 문자형식이 아니면 
	 * @param string 
	 * @return string
	 */
	static function str(&$arr,$k,$def=''){		
		//if( isset($arr[$k]) && !empty($arr[$k]) ) return trim($arr[$k]) ;
		if( isset($arr[$k]) && $arr[$k] != '' ) return trim($arr[$k]) ;
		else return $def ;
	}
	
	/**
	 * 2013-08-13 
	 * 변수가 array 형식이 아니면 
	 * @param string 
	 * @return string
	 */
	static function arr(&$arr,$k){		
		if( isset($arr[$k]) && is_array($arr[$k]) ) return $arr[$k] ;
		else return array();
	}
	
	/**
	 * 2013-08-13 
	 * 배열에 변수가 없으면 empty error 반환
	 * @param string 
	 * @return string
	 */
	static function in(&$arr,$k){		
		if( !in_array($k,$arr) ) return $k ;
		else return 'empty error' ;
	}

	static function htmlChar(&$arr){
		if( is_array($arr) )
		{		
			foreach( $arr as $k => $v )
			{
				if( is_array($v) )
					$arr[$k] = A::htmlChar($v);
				else
					$arr[$k] = stripslashes($v);
			}

			return $arr ;
		}

		return array();
	}
	
	/**
	 * 2015-05-04 
	 * ma
	 * @param array 
	 * @return array
	 */
	static function addslashes(&$arr){
		if( !get_magic_quotes_gpc() && is_array($arr) )
		{		
			foreach( $arr as $k => $v )
			{
				if( !empty($v) && is_string($v) ){
					$arr[$k] = addslashes($v);
				}else{
					$arr[$k] = $v;
				}			
			}

			return $arr ;
		}

		return array();
	}
	
	/**
	 * 2014-01-08 
	 * null 를 공백으로
	 * @param array 찾을 배열
	 */
	static function nulls(&$arr){
		foreach( $arr as $k => $v ){
			if(!is_numeric($v) && empty($v) ){
				$arr[$k] = '' ;
			}
		}
	}
}

class H
{
	/**
	 * 2013-08-13 
	 * 두 변수가 & 연산에 
	 * @param string 2000-10-10
	 * @return "" or checked="checked" 
	 */
	static function bitChecked($d,$v){
		if($d > 0 && $d & $v ) return 'checked="checked"' ;
		return '' ;
	}
	static function checked($d,$v){
		if($d == $v ) return 'checked="checked"' ;
		return '' ;
	}
	static function inArrayChecked($d,$v){
		if( in_array($v, $d) ) return 'checked="checked"' ;
		return '' ;
	}
	static function bitToOn($d,$v){
		if($d > 0 && $d & $v ) return 'on' ;
		return '' ;
	}
	static function selected($d,$v){
		if($d == $v) return 'selected="selected"' ;
		return '' ;
	}
	static function equal($d,$v,$str){
		if($d == $v ) return $str ;
		return '' ;
	}
	static function find($str,$find,$ret){
		$pos = strpos($str, $find);
		if( $pos === false )
			return '';
		else
			return $ret;
	}
}


/*
$C = new CryptFunc('','','');
foreach(["이렇게", "잘되나", 'protected static $instance = null;', '256 bit 키를 만들기 위해서 비밀번호를 해시해서 첫 32바이트를 사용합니다.','삭제
2,165 !@ㅣ따ㅓㄲ)@(%$*!@상승0.70%
문!@#$배철강삭제'] as $v){
	pre([strlen($v), $v]);
	$k = $C->encode($v);
	pre([strlen($k), $k, 'strcmp:'.strcmp($v, $C->decode($k))]);
	pre([$b=$C->hash($v), $c=$C->hash($v)]);
	pre([strlen($b),$C->verify($v, $b)?'OK':'NO', strlen($c),$C->verify($v, $c)?'OK':'NO']);
}
 *  */
class CryptFunc{
	private $pass;
	private $iv;
	private $method;
	
	static $self;
			
	function __construct($pass='',$iv='',$method='') {
		if(empty($pass)){
			$pass = 'jinwoopwd#@!';
		}
		if(empty($iv)||strlen($iv)!=16){
			$iv = 'jinwoopass16byte';
		}
		$ciphers             = openssl_get_cipher_methods();
		if(!in_array($method, $ciphers)){
			$method = 'aes-256-cbc';
		}
		
		$this->pass = $pass;
		$this->iv = $iv;
		$this->method = $method;
	}
	
	function encode($str){
		return base64_encode(openssl_encrypt($str, $this->method, $this->pass, OPENSSL_RAW_DATA, $this->iv));
	}
	function decode($enstr){
		return openssl_decrypt(base64_decode($enstr), $this->method, $this->pass, OPENSSL_RAW_DATA, $this->iv);
	}
	//http://php.net/manual/en/function.password-hash.php
	function hash($str){
		return password_hash($str, PASSWORD_DEFAULT);
	}
	//http://php.net/manual/en/function.password-verify.php
	function verify($str, $hash){
		return password_verify($str, $hash);
	}
}

	/*
	$plainText = '암호화될 메세지';
	$password = 'password string';

// 256 bit 키를 만들기 위해서 비밀번호를 해시해서 첫 32바이트를 사용합니다.
$password = substr(hash('sha256', $password, true), 0, 32);
echo "비밀번호 바이너리:" . $password . "<br/>";

// Initial Vector(IV)는 128 bit(16 byte)입니다.
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

// 암호화
$encrypted = base64_encode(openssl_encrypt($plainText, 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv));

// 복호화
$decrypted = openssl_decrypt(base64_decode($encrypted), 'aes-256-cbc', $password, OPENSSL_RAW_DATA, $iv);

echo 'plainText : ' . $plainText . "<br/>";
echo '암호화 : ' . $encrypted . "<br/>";
echo '복호화 : ' . $decrypted . "<br/>";
// 출처: https://offbyone.tistory.com/347 [쉬고 싶은 개발자]
	*/

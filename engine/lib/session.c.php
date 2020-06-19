<?php
		
/**
 * Description of session
 *
 * @author Administrator
 */
class Session {
	protected $userid ;
	protected $adminid ;
	protected $username ;
	
	protected $tmpArr ;
	protected $memebertype ;
		
	function __construct(){

		$this->userid =		isset($_SESSION['userid'])?$_SESSION['userid']:'' ;
		$this->adminid =		isset($_SESSION['adminid'])?$_SESSION['adminid']:'' ;
		
		$tmpSession = $_SESSION ;
		unset( $tmpSession['userid'] ) ;
		unset( $tmpSession['adminid'] ) ;
		foreach( $tmpSession as $k => $v )
			$this->tmpArr[$k] = $v ;
		
	}
	
	function destroy()
	{
		$this->setUserid('');

		session_destroy() ;
	}
	
	function getMd5key()
	{
		return md5( microtime(true).$_SERVER['REMOTE_ADDR'].session_id()) ;
	}
	
	function setSession( $key , $value )
	{
		//setcookie($key, $value, _COOKIE_expire_ , '/' );
		if( property_exists ( $this , $key) )
		{
			$_SESSION[$key] = $this->{$key} = ($value) ;
		}else{
			$_SESSION[$key] = $this->tmpArr[$key] = ($value) ;
		}
		
		return $this;
		//	throw new Exception( "[".$key."] session".print_r(debug_backtrace(), true)); 
	}
	
	function setBoardIdx($idx){
		$this->setSession('idx'.$idx, 'on');
	}
	function getBoardIdx($idx){
		return $this->getSession('idx'.$idx);
	}
	
	function setCookie( $key , $value, $expire = '', $path = '/' )
	{
		if( !is_numeric($expire) ){
			$expire = _COOKIE_expire_;
		}
			
		setcookie($key, urlencode($value), $expire , $path );
		return $this;
	}
	
	function getSession($k)
	{
		return isset($this->tmpArr[$k]) ? urldecode($this->tmpArr[$k]) : '' ;
	}
	function getCookie($k)
	{
		return isset($_COOKIE[$k]) ? urldecode($_COOKIE[$k]) : '' ;
	}
	
	
	function getWebLoginId() {
		return $this->getSession('web_login_id') ;		
	}
	function setWebLoginId($id) {
		$this->setSession('web_login_id', $id) ;
	}
	
	function setUserid($str)
	{
		$this->setSession('userid', $str);
		return $this;
	}
	function getUserid()
	{
		return $this->userid ;
	}
	
	function setAdminid($str)
	{
		$this->setSession('adminid', $str);
		return $this;
	}
	function getAdminid()
	{
		return $this->adminid ;
	}
	
}

//$Session->setSession('test', 'wqrwqra');
//echo $Session->getSession('test') ;
//print_r($_SESSION);

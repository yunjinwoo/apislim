<?php
//error_reporting(E_ALL | E_STRICT );
error_reporting(E_ERROR | E_STRICT);
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('magic_quotes_gpc', 'off');

ini_set('exit_on_timeout', 'on');

date_default_timezone_set ('Asia/Seoul');

define('_DOCUMENT_ROOT_', str_replace(DIRECTORY_SEPARATOR,'/',$_SERVER['DOCUMENT_ROOT']) );
define('_PATH_', str_replace(DIRECTORY_SEPARATOR,'/',dirname(__FILE__))) ;
define('_WEB_PATH_', str_replace(_DOCUMENT_ROOT_,'',_PATH_)) ;


//session_save_path($_SERVER['DOCUMENT_ROOT'] . '/data/sess');
//ini_set('session.gc_probability', 1);

//ini_set('session.save_path',realpath($_SERVER['DOCUMENT_ROOT'] . '/data/sess'));
//session_save_path ( realpath($_SERVER['DOCUMENT_ROOT'] . '/data/sess') );
//echo realpath($_SERVER['DOCUMENT_ROOT'] . '/data/sess');

//2020-06-15 앱로그인유지...
ini_set('session.gc_maxlifetime', 3600000);
session_cache_expire(43200);//60*24*30
//ini_set('session.gc_maxlifetime', 60);
//session_cache_expire(1);//60*24*30
session_start();
// 사이트 마다 수정될것들....
require_once _PATH_.'/_define_site.php';

//define('_PATH_', dirname(__FILE__)) ;
define('_PATH_lib_', _PATH_.'/lib') ; // 각종 클래스
define('_PATH_model_', _PATH_.'/model'.db_type) ; // 각종 디비연동 기능들
//define('_PATH_data_', _PATH_.'/data') ; // 업로드 되는 폴더
define('_PATH_data_', _PATH_.'/../data') ; // 업로드 되는 폴더
define('_WEB_PATH_DATA_', '/data') ;


define('_PATH_admin_html_', _PATH_.'/admin/p_html') ; // 업로드 되는 폴더

define('_COOKIE_expire_', time()+25920000 );
define( '_TODAY_' , date('Y-m-d'));


function engine_autoloader($class) {
	
	//echo "<br />@".((_PATH_model_.'/db_table/'. $class . '.c.php') ? 'serwer' " '0001231230'));
			  
	if( is_file( _PATH_model_.'/db_table/'. $class . '.c.php') ){
		
		pre(_PATH_model_ .'/db_table/'. $class . '.c.php');
		include _PATH_model_ .'/db_table/'. $class . '.c.php';
	}
	if( is_file(_PATH_model_ .'/db_table/'. $class . '.php') ){
		
		pre(_PATH_model_ .'/db_table/'. $class . '.php');
		include _PATH_model_ .'/db_table/'. $class . '.php';
	}
}
spl_autoload_register('engine_autoloader');

require_once _PATH_lib_.'/global.c.php';
require_once _PATH_lib_.'/httpHeader.c.php';
require_once _PATH_lib_.'/func.php';



load('config');
load('code');
load('log');


require_once _PATH_.'/_default_func.php';

HttpHeader::charset('utf-8');


$dealer_life_day = Code::getCodeStr('FORM_DEALER_LIFE_DAY');
$dealer_life_day2 = Code::getCodeStr('FORM_DEALER_LIFE_DAY2');
$rent_life_day = Code::getCodeStr('FORM_RENT_LIFE_DAY');
if( !is_numeric($dealer_life_day) ){
	$dealer_life_day = 1;
}
if( !is_numeric($dealer_life_day2) ){
	$dealer_life_day2 = 3;
}
if( !is_numeric($rent_life_day) ){
	$rent_life_day = 7;
}
define('_FORM_DEALER_LIFE_DAY_', $dealer_life_day*-1);
define('_FORM_DEALER_LIFE_DAY2_', $dealer_life_day2*-1);

define('_FORM_RENT_LIFE_DAY_', $rent_life_day*-1);

//@@load('admin_team');
//@@$AccessIp = new AccessIp();

//$AccessIpLog = new AccessIpLog();
//$AccessIpLog->logCreate();


//
//if( $AccessIp->isFind($_SERVER['REMOTE_ADDR']) ){
//	$SessionDb = new sessionStatus;
//	$SessionDb->insert_log_session(session_id(), 'ERR:['.$_SERVER['REMOTE_ADDR'].']차단 아이피');
//	
//	HttpHeader::location(Code::getCodeStr('deny_domain'));
//	exit;
//}

//@@@load('session_db');
//try{
//	if(($msg=session_db_check())!==true){
//		$Session = new Session;
//		$k = $Session->getSession('sess_out_cnt');
//		$k = F::number($k, 0) + 1;
//		$Session->setSession('sess_out_cnt', $k);
//
//		$SessionDb = new sessionStatus;
//		$SessionDb->insert_log_session(session_id(), 'ERR:차단 세션-'.$k);
//		if( $k >= 10 ){
//			exitJs($msg, Code::getCodeStr('deny_domain'));
//		}
//
//		exitJs($msg.'['.$k.']');
//	}
//}catch(Exception $e){
//	//고투에서 오류를 낸적이 있어서;;;
//	logProgram::put('error', '세션db오류');
//}
//getLogLoginObject(getLogLoginIdx())->insertPage();

/**
 * @param string adminMember
 * @param string banner
 * @param string board
 * @param string category
 * @param string code
 * @param string coupon
 * @param string editorFile
 * @param string member
 * @param string moms
 * @param string pageMake
 * @param string product
 */
function load($moduleName)
{
	$fn = _PATH_model_.'/'.$moduleName ;
	if(!is_dir($fn)) debug( $fn.' 모듈없음' ) ;

	if(is_file(_PATH_model_.'/'.$moduleName.'/_require.php')){
		require_once _PATH_model_.'/'.$moduleName.'/_require.php';
	}

	$f = dir(_PATH_model_.'/'.$moduleName) ;

	while (false !== ($e = $f->read())) {
		if( strpos( $e , '.php' ) !== false )
			require_once $fn.'/'.$e ;
	}
	$f->close();
}


function loan_load($moduleName)
{
	$path = _PATH_.'/module_loan';
	$fn = $path.'/'.$moduleName ;
	if(!is_dir($fn)) debug( $fn.' 모듈없음' ) ;

	if(is_file($path.'/'.$moduleName.'/_require.php')){
		require_once $path.'/'.$moduleName.'/_require.php';
	}


	$f = dir($path.'/'.$moduleName) ;

	while (false !== ($e = $f->read())) {
		if( strpos( $e , '.php' ) !== false )
			require_once $fn.'/'.$e ;
	}
	$f->close();
}


function dmz_load($moduleName)
{
	$path = _PATH_.'/module_dmz';
	$fn = $path.'/'.$moduleName ;
	if(!is_dir($fn)) debug( $fn.' 모듈없음' ) ;

	if(is_file($path.'/'.$moduleName.'/_require.php')){
		require_once $path.'/'.$moduleName.'/_require.php';
	}


	$f = dir($path.'/'.$moduleName) ;

	while (false !== ($e = $f->read())) {
		if( strpos( $e , '.php' ) !== false )
			require_once $fn.'/'.$e ;
	}
	$f->close();
}


function car_load($moduleName)
{
	$path = _PATH_.'/module_car';
	$fn = $path.'/'.$moduleName ;
	if(!is_dir($fn)) debug( $fn.' 모듈없음' ) ;

	if(is_file($path.'/'.$moduleName.'/_require.php')){
		require_once $path.'/'.$moduleName.'/_require.php';
	}


	$f = dir($path.'/'.$moduleName) ;

	while (false !== ($e = $f->read())) {
		if( strpos( $e , '.php' ) !== false )
			require_once $fn.'/'.$e ;
	}
	$f->close();
}

/**
 * @param string PHPExcel
 * @param string editor
 * @param string simple_html_dom
 * @param string snoopy1.2.4
 * @param string thumb
 *
 * @param string gabia_sms
 * @param string gabia_sms_api_php
 */
function loadLib($libName)
{
	$isFlag = false;
	$fn = _PATH_lib_.'/'.$libName ;
	if(!is_dir($fn)) {
		if( is_file($fn.'.php') ){
			require_once $fn.'.php';
			$isFlag = true;
		}
	}

	if(is_file(_PATH_lib_.'/'.$libName.'/_require.php')){
		require_once _PATH_lib_.'/'.$libName.'/_require.php';
		$isFlag = true;
	}
	if(is_file($fn.'/'.$libName.'.php')){
		require_once $fn.'/'.$libName.'.php' ;
		$isFlag = true;
	}
	if(	!$isFlag ){
		pre('ERROR : loadLib not find - '.$libName);
	}
}



function cssTag($href)
{
	return '<link rel="stylesheet" type="text/css" href="'.$href.'?'.microtime(true).'" />';
}
function jsTag($src, $path = '/front/js/')
{
	return '<script type="text/javascript" src="'.$path.$src.'"></script>';
}


function mobileMoveAct()
{
	//http://blog.naver.com/PostView.nhn?blogId=tyboss&logNo=70171184236
	$agentsArray = array('iPhone', 'iPod', 'SIE-', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
	if( !isset($_COOKIE['is_mobile_pc_view']) || $_COOKIE['is_mobile_pc_view'] != 'on' )
	{
		foreach ($agentsArray as $v )
		{
			if(strpos($_SERVER['HTTP_USER_AGENT'] , $v) !== false)
				h_location ('/m/mb');
		}
	}
}

function pcMoveAct()
{
	if( isset($_COOKIE['is_mobile_pc_view']) && $_COOKIE['is_mobile_pc_view'] == 'on' ){
		setcookie('is_mobile_pc_view', '', 0, '/');
		h_location ('/front/f_html');
	}

}

function mobileToPcMode()
{
	setcookie('is_mobile_pc_view', 'on', 0, '/');

}
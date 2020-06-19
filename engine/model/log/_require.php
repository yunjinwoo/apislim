<?php


/*
CREATE TABLE `jin_log_login` (
	`login_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`admin_id` VARCHAR(50) NULL DEFAULT NULL,
	`reg_id` VARCHAR(50) NULL DEFAULT NULL,
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`http_agent` VARCHAR(200) NULL DEFAULT NULL,
	`agent_type` VARCHAR(10) NULL DEFAULT NULL,
	PRIMARY KEY (`login_idx`),
	INDEX `admin_id` (`admin_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

CREATE TABLE `jin_log_login_page` (
	`page_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`login_idx` INT(11) NULL DEFAULT NULL,
	`page_type` VARCHAR(50) NULL DEFAULT NULL,
	`request_uri` VARCHAR(200) NULL DEFAULT NULL,
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`page_idx`),
	INDEX `login_idx` (`login_idx`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;

 */

// 세션이 필요하다..

define('_db_log_program_'	,_db_fix_.'log_program');
define('_db_log_login_'		,_db_fix_.'log_login');
define('_db_log_login_page_',_db_fix_.'log_login_page');

require_once dirname(__FILE__).'/logProgram.c.php';
require_once dirname(__FILE__).'/logLogin.c.php';


function getLogLoginIdx(){
	$Session = new Session;
	return $Session->getSession('login_idx');
}
function setLogLoginIdx($login_idx){
	$Session = new Session;
	$Session->setSession('login_idx', $login_idx);
}

$__log_login_object__ = null;
function getLogLoginObject($user_id, $login_site = ''){
	global $__log_login_object__;
	
	$login_idx = getLogLoginIdx();
	if( $__log_login_object__ == null ){
		$__log_login_object__ = new LogLogin();
	}
	
	if(!is_numeric($login_idx) ){
		$login_idx = $__log_login_object__->logCreate($user_id, $login_site);
		setLogLoginIdx($login_idx);
	}
	$__log_login_object__->setLoginIdx($login_idx);
	
	return $__log_login_object__;
}

function isLoginLog(){
	$login_idx = getLogLoginIdx();
	$Log = new LogLogin();
	
	$r = $Log->getList_login_log($login_idx);
	
	if( count($r) >= 1){
		return true;
	}else{
		$Log->delete_login_idx($login_idx);
		return false;
	}
}

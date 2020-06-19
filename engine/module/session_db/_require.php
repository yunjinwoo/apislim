<?php


/*
CREATE TABLE IF NOT EXISTS `jin_session_status` (
  `status_idx` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `login_id` varchar(50) DEFAULT NULL,
  `login_group` varchar(50) DEFAULT NULL,
  `session_status` varchar(50) DEFAULT NULL,
  `reg_ip` varchar(50) DEFAULT NULL,
  `http_agent` varchar(200) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

CREATE TABLE IF NOT EXISTS `jin_session_status_log` (
  `status_log_idx` int(11) NOT NULL,
  `status_idx` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `login_id` varchar(50) DEFAULT NULL,
  `login_group` varchar(50) DEFAULT NULL,
  `session_status` varchar(50) DEFAULT NULL,
  `reg_ip` varchar(50) DEFAULT NULL,
  `http_agent` varchar(200) DEFAULT NULL,
  `agent_type` varchar(10) DEFAULT NULL,
  `update_date` datetime,
  `log_reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;
--

 */

// 세션이 필요하다..
define('_db_session_db_'	,_db_fix_.'session_status');
define('_db_session_db_log_'	,_db_fix_.'session_status_log');
require_once dirname(__FILE__).'/session_status.c.php';


function session_db_check(){
	$session_id = session_id();
	$SessionDb = new sessionStatus;
	$SessionDb->addWhere('session_id', $session_id);
	$list = $SessionDb->getListCommon('session_id');
	$row = A::arr($list, $session_id);
	if( isset($row['update_date']) ){
		// 6시간이 지나면 세션을 다시
		if( strtotime($row['update_date']) < time() - 1 ){
//			session_id (md5(time(). microtime()));
//			HttpHeader::location("?");
		}
	}
	
	//공통
	$SessionDbInsert = new sessionStatus;
	$SessionDbInsert->addData('request_uri', $_SERVER['REQUEST_URI']);
	$SessionDbInsert->addData('reg_ip', $_SERVER['REMOTE_ADDR']);
	$SessionDbInsert->addData('http_agent_type', getAgentType());
	$SessionDbInsert->addData('http_agent', $_SERVER['HTTP_USER_AGENT']);
	if( isset($row['status_idx']) ){		
		if($row['session_status'] != 'Y'){
			$SessionDb->insert_log_session(session_id(), 'ERR:차단 세션');
			return "사이트 이용이 제한되었습니다.";
		}
		$SessionDbInsert->addWhere('status_idx', $row['status_idx']);
				
		//수정
		$SessionDbInsert->updateCommon($SessionDbInsert->db());
	}else{
		$Session = new Session;
		$WEB_LOGIN_ID = $Session->getWebLoginId();
		
		//저장
		$SessionDbInsert->addData('session_id', $session_id);
		if( empty($WEB_LOGIN_ID) ){
			$SessionDbInsert->addData('login_id', $WEB_LOGIN_ID);
			$SessionDbInsert->addData('login_group', 'company');
		}else{	
			$SessionDbInsert->addData('login_id', $WEB_LOGIN_ID);
			$SessionDbInsert->addData('login_group', 'company');
		}
		
		$row['status_idx'] = $SessionDbInsert->insertCommon($SessionDbInsert->db());
	}
	
	$SessionDbInsert->insert_log($row['status_idx']);	
	
	
	
	$SessionDbDelete = new sessionStatus;
	$login_log_month = Code::getCodeStr('login_log_month');
	if(is_numeric($login_log_month)){
		if( !empty($login_log_month) ){
			$SessionDbDelete->deleteLog(' -'.$login_log_month.' month');
		}
	}else{
		$login_log_month = ' -3 month ';
		$SessionDbDelete->deleteLog($login_log_month);
	}
	$SessionDbDelete->addWhereStr('update_date' ,' <= \''.date('Y-m-d H:i:s', strtotime('-6 hour')).'\'');
	$del_list = $SessionDbDelete->getListCommon($SessionDbDelete->getPrimaryField());
	
	foreach( $del_list as $k => $r ){
		$SessionDbDelete->__insert_log($r, 'garbage');
	}
	$SessionDbDelete->deleteCommon($SessionDbDelete->db());

	return true;
}
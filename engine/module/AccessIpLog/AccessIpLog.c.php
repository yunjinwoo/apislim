<?php
/*
CREATE TABLE `jin_access_ip_log` (
	`login_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`reg_ip` VARCHAR(50) NULL DEFAULT NULL,
	`session_id` VARCHAR(100) NULL DEFAULT NULL,
	`keyword` VARCHAR(50) NOT NULL DEFAULT '0',
	`request_uri` VARCHAR(200) NULL DEFAULT NULL,
	`http_agent` VARCHAR(200) NULL DEFAULT NULL,
	`agent_type` VARCHAR(10) NULL DEFAULT NULL,
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`login_idx`),
	INDEX `reg_ip` (`reg_ip`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;
 *  */
define('_db_access_ip_', _db_fix_.'access_ip') ; 
define('_db_access_ip_log_', _db_fix_.'access_ip_log') ; 

class AccessIpLog {
	private $login_idx;
	function __construct($login_idx = '') {
		if(is_numeric($this->login_idx)){
			$this->setLoginIdx($login_idx);
		}
	}
	
	
	function delete($time = '-3 month'){
		$reg_date = date('Y-m-d H:i:s', strtotime($time));
		$q = ' DELETE FROM '._db_access_ip_log_. ' WHERE reg_date < \''.$reg_date.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
	}
	
	function getCount($ip){
		$q = ' SELECT COUNT(*) as cnt FROM '._db_access_ip_log_. ' WHERE reg_ip LIKE \''.str_replace('*', '', $ip).'%\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetch(PDO::FETCH_OBJ)->cnt;
	}
	
	function logCreate(){
		$this->delete();
		
		$session = new session();
		$q = ' 
			INSERT INTO '._db_access_ip_log_.'
			SET
				keyword = :keyword
			,	session_id = :session_id
			,	reg_ip = :reg_ip
			,	request_uri = :request_uri
			,	http_agent = :http_agent
			,	agent_type = :agent_type
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':reg_ip', A::str($_SERVER, 'REMOTE_ADDR'));
		$stmt->bindValue(':session_id', session_id());
		$stmt->bindValue(':keyword', urldecode($session->getCookie('keyword')).':::'.$session->getCookie('media'));
		$stmt->bindValue(':request_uri', A::str($_SERVER, 'REQUEST_URI'));
		$stmt->bindValue(':http_agent', A::str($_SERVER, 'HTTP_USER_AGENT') );
		$stmt->bindValue(':agent_type', getAgentType() );
		
		stmtExecute($stmt);
	}
}

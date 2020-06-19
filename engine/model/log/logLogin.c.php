<?php
class LogLogin extends SaveCommon{
	private $login_idx;
	protected $table = _db_log_login_;
	protected $table_sub = _db_log_login_page_;
			
	function __construct($login_idx = '') {
		if(is_numeric($this->login_idx)){
			$this->setLoginIdx($login_idx);
		}
	}
	
	function getPrimaryField() {
		return 'login_idx';
	}
	function getList(){
		$q = '
			SELECT * 
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
				'.$this->getOrder().'
				'.$this->getLimit().'
			';
		$ret = array();
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		while($a = $stmt->fetch()){
			$ret[$a['login_idx']] = $a;
		}
		
		return $ret;
	}
	
	function getGroupList($field){
		$this->setGroup($field);
		$q = '
			SELECT '.$field.' as field, COUNT(*) as cnt
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
				'.$this->getOrder().'
				'.$this->getLimit().'
			';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[$a['field']] = $a['cnt'];
		}
		
		return $ret;
	}
	
	function getList_login_log($login_idx){
		$q = ' 
			SELECT * FROM '.$this->table.' 
			WHERE login_idx = '.$login_idx;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[] = $a;
		}
		
		return $ret;
	}
	
	function getList_login_page($login_idx){
		$q = ' 
			SELECT * FROM '.$this->table_sub.' 
			WHERE login_idx = '.$login_idx.'
			ORDER BY page_idx ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[] = $a;
		}
		
		return $ret;
	}
	
	function delete($time = '-3 month'){
		$reg_date = date('Y-m-d H:i:s', strtotime($time));
		$q = ' DELETE FROM '.$this->table. ' WHERE reg_date < \''.$reg_date.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' DELETE FROM '.$this->table_sub. ' WHERE reg_date < \''.$reg_date.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' OPTIMIZE TABLE '.$this->table;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' OPTIMIZE TABLE '.$this->table_sub;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	
	function delete_login_idx($login_idx){
		$q = ' DELETE FROM '.$this->table. ' WHERE login_idx = \''.$login_idx.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' DELETE FROM '.$this->table_sub. ' WHERE login_idx = \''.$login_idx.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' OPTIMIZE TABLE '.$this->table;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$q = ' OPTIMIZE TABLE '.$this->table_sub;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	
			
	function logCreate($userid, $login_site = ''){
		$login_log_month = Code::getCodeStr('login_log_month');
		if(is_numeric($login_log_month)){
			if( !empty($login_log_month) ){
				$this->delete(' -'.$login_log_month.' month');
			}
		}else{
			$login_log_month = ' -3 month ';
			$this->delete($login_log_month);
		}
		
		$q = ' 
			INSERT INTO '.$this->table.'
			SET
				admin_id = :admin_id
			,	login_site = :login_site
			,	reg_ip = :reg_ip
			,	http_agent = :http_agent
			,	agent_type = :agent_type
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':admin_id', $userid);
		$stmt->bindValue(':login_site', $login_site);
		$stmt->bindValue(':reg_ip', A::str($_SERVER, 'REMOTE_ADDR'));
		$stmt->bindValue(':http_agent', A::str($_SERVER, 'HTTP_USER_AGENT') );
		$stmt->bindValue(':agent_type', getAgentType() );
		
		stmtExecute($stmt);
		
		$this->login_idx = db()->lastInsertId();
		return $this->login_idx;
	}
	
	function setLoginIdx($login_idx){
		$this->login_idx = $login_idx;
	}
	
	function insertPage(){
		$q = '
			INSERT INTO '.$this->table_sub.'
			SET
				login_idx = :login_idx
			,	page_type = :page_type
			,	request_uri = :request_uri
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':login_idx', $this->login_idx, PDO::PARAM_INT);
		$stmt->bindValue(':page_type', getRequestUriType() );
		$stmt->bindValue(':request_uri', $_SERVER['REQUEST_URI'] );
		
		stmtExecute($stmt);
		return $this;
	}
}
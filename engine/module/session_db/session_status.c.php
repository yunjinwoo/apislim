<?php
class sessionStatus extends SaveCommon{
	private $login_idx;
	protected $table = _db_session_db_;
	protected $table_log = _db_session_db_log_;
	protected $table_log_field;
			
	function __construct() {
		$this->table_field = array(
				'status_idx' => '접속자키' 
			,	'session_id' => '세션' 
			,	'login_id' => '로그인아이디' 
			,	'login_group' => '로그인구분'
			,	'request_uri' => '저장페이지'
			,	'session_status' => '세션상태' 
			,	'reg_ip' => '등록아이피'
			,	'http_agent_type' => 'http_agent-구분'
			,	'http_agent' => 'http_agent' 
			,	'update_date' => '수정일'
		);
		
		$this->table_log_field = $this->table_field;
		$this->table_log_field['status_log_idx'] = '접속자로그키';
		$this->table_log_field['log_memo'] = '메모';
		$this->table_log_field['log_reg_date'] = '등록일';
	}
	
	function getPrimaryField(){
		return 'status_idx';
	}
	
	function getRowSession($session_id){
		$SessionDb = new sessionStatus;
		$SessionDb->addWhere('session_id', $session_id);
		$list = $SessionDb->getListCommon('session_id');
		return A::arr($list, $session_id);
	}
	function insert_log($idx, $memo = 'auto'){
		$SessionDb = new sessionStatus;
		$row = $SessionDb->getRowCommon($this->getPrimaryField(), $idx);
		$this->__insert_log($row, $memo);
	}
	/*

	function insert_log($idx, $memo = 'auto'){
		$SessionDb = new sessionStatus;
		$SessionDb->addWhere($SessionDb->getPrimaryField(), $idx);
		$SessionDb->setOrder($SessionDb->getPrimaryField().' DESC ');
		$row = $SessionDb->getListCommonOne();
		$this->__insert_log($row, $memo);
	}
	 * 	 */
	
	function insert_log_session($session_id, $memo = 'auto'){
		$this->__insert_log($this->getRowSession($session_id), $memo);
	}
	
	function __insert_log($row, $memo = 'auto'){
		$SaveCommon = new SaveCommon($this->table_log);
		foreach( $row as $k => $v ){
			$SaveCommon->addData($k, $v);
		}
		$SaveCommon->addData('log_memo', $memo);
		$SaveCommon->insertCommon($this->db());
	}
	
	function deleteSessionStatus($session_id){
		$SessionDb = new sessionStatus;
		$SessionDb->addWhere('session_id', $session_id);
		$SessionDb->deleteCommon($SessionDb->db());
	}
	
	function getList_sessionlog($session_id){
		$SessionLog = new SaveCommon($this->table_log);
		$SessionLog->addWhere('session_id', $session_id);
		$SessionLog->setOrder(' status_log_idx ');
		return $SessionLog->getListCommon('status_log_idx');
	}
	
	function delete($time = '-6 hour'){
		$reg_date = date('Y-m-d H:i:s', strtotime($time));
		$q = ' DELETE FROM '.$this->table. ' WHERE 	update_date < \''.$reg_date.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		
//		$q = ' OPTIMIZE TABLE '.$this->table;
//		$stmt = db()->prepare($q);
//		stmtExecute($stmt);
	}
	
	function deleteLog($time = '-3 month'){
		$reg_date = date('Y-m-d H:i:s', strtotime($time));
		$q = ' DELETE FROM '.$this->table_log. ' WHERE 	update_date < \''.$reg_date.'\' ';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
//		$q = ' OPTIMIZE TABLE '.$this->table_log;
//		$stmt = db()->prepare($q);
//		stmtExecute($stmt);
	}
	
}
<?php

class BoardBase extends SaveCommon {
	protected $table = _db_board_;
	
	function __construct() {
		$this->setOrder('board_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'board_idx' => '게시판번호' 
		,	'board_type' => '게시판분류' 
		,	'user_id' => '작성자아이디' 
		,	'passwd' => '글비밀번호' 
		,	'write_name' => '작성자' 
		,	'board_title' => '제목' 
		,	'board_text' => '내용' 
		,	'upfile1' => '업로드파일명' 
		,	'reg_ip' => '등록아이피' 
		,	'read_cnt' => '조회수' 
		,	'reg_date' => '등록일' 
		,	'update_date' => '수정일' 
		,	'editor_session_key' => '업로드파일키'

		);
	}
	
	function _row_replace($aRow){
		
		return $aRow;
	}
	
	function getPrimaryField(){
		return 'board_idx';
	}
	
	function save($aSave){
		$class = get_class($this);
		$_this = new $class();
		$_this->_addData($aSave);
		
		
		$board_idx = A::str( $aSave , 'board_idx');
		if( is_numeric($board_idx) ) {
			//수정
			$_this->addWhere('board_idx', $board_idx);
			$_this->saveCommon($_this->db());
		}else{
			//추가
			$board_idx = $_this->saveCommon($_this->db());
		}
		
		return $board_idx;
	}

	function delete($board_idx){
		$q = 'DELETE FROM '.$this->table.' WHERE board_idx = '.$board_idx;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	
	function getList($arr_key_name = 'board_idx'){
		return $this->getListCommon($arr_key_name);
	}
}
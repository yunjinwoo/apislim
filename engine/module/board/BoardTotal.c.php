<?php

define('_db_board_total_', _db_fix_.'board_total') ; 

class BoardTotal extends SaveCommon {
	protected $table = _db_board_total_;
	public $list_file = 'board_list.php';
	public $view_file = 'board_view.php';
	public $write_file = 'board_write.php';
	
	function __construct() {
		$this->setOrder('board_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'board_idx' => '게시판번호' 
		,	'board_name' => '게시판명' 
		,	'cate_name' => '게시판분류' 
		,	'user_id' => '작성자아이디' 
		,	'passwd' => '글비밀번호' 
		,	'write_name' => '작성자' 
		,	'board_title' => '제목' 
		,	'board_text' => '내용' 
		,	'is_notice' => '공지여부'
		,	'upfile1' => '업로드파일명' 
		,	'upfile1_alt' => '업로드파일명_alt' 
		,	'reg_ip' => '등록아이피' 
		,	'read_cnt' => '조회수' 
		//,	'reg_login_info' => '등록로그인정보' 
		//,	'reg_date' => '등록일' 
		//,	'update_login_info' => '수정로그인정보' 
		
			,	'board_phone1'=> '전화번호1' 
			,	'board_phone2'=> '전화번호2' 
			,	'board_phone3'=> '전화번호3' 
			
		,	'update_date' => '수정일' 
		,	'editor_session_key' => '업로드파일키'
		);
	}
	
	function getPrimaryField(){
		return 'board_idx';
	}
	
	function getList_no_y($page){
		$this->addWhere('is_notice', 'Y');
		return $this->getList($page);
	}
	
	function getList_no_n($page){
		$this->addWhere('is_notice', 'N');
		return $this->getList($page);
	}
	
	
	
	
	function bindValue($aSave){
	}
	
	function _row_replace($r){
		$r['is_new'] = strtotime($r['reg_date']) > strtotime('-3day') ? true : false;
		$r['is_file'] = isset($r['file_list']) && count($r['file_list']) >= 1 ? true : false;
		
		return $r;
	}
	
	
	function save($aSave){
		$class = get_class($this);
		$_this = new $class();
		$_this->resetWhere();
		
		if( A::str($aSave, 'is_notice') != 'Y' ){
			$aSave['is_notice'] = 'N';
		}
		$_this->_addData($aSave);
		
		$board_idx = A::str( $aSave , 'board_idx');
		if( is_numeric($board_idx) ) {
			//수정
			$_this->addData('update_login_info', getLoginInfo());
			$_this->addData('update_date', date('Y-m-d H:i:s'));
			$_this->addWhere('board_idx', $board_idx);
			$_this->saveCommon($_this->db());
		}else{
			//추가
			$_this->addData('reg_login_info', getLoginInfo());
			$_this->addData('reg_date', date('Y-m-d H:i:s'));
			$_this->unData('board_idx');
			$board_idx = $_this->saveCommon($_this->db());
		}
		
		$aSave['board_idx'] = $board_idx;
		$del_file_idx_list = G::postArr('del_file_board_idx');
		if( isset($del_file_idx_list) && is_array($del_file_idx_list) ){
			foreach( $del_file_idx_list as $k => $v ){
				$v = $this->file_row_delete_board_idx ($k) ;
				

				$class = get_class($this);
				$_this = new $class();
				$_this->addData('upfile1', '');
				$_this->addData('upfile1_alt', '');
				$_this->addWhere('board_idx', $k);
				$_this->saveCommon($_this->db());
				break;
			}
		}
		
		$file_path = $this->fileUpload($board_idx, 'upfile1', A::str($aSave, 'upfile1_alt'));
		if( !empty($file_path) ){
			$class = get_class($this);
			$_this = new $class();
			$_this->addData('upfile1', $file_path);
			$_this->addData('upfile1_alt', A::str($aSave, 'upfile1_alt'));
			$_this->addWhere('board_idx', $board_idx);
			$_this->saveCommon($_this->db());
		}
		
		return $board_idx;
	}
	
	function file_row_delete($board_file_idx){
		$BoardFile = new BoardTotalFile();
		$BoardFile->delete($board_file_idx) ;
	}
	function file_row_delete_board_idx($board_idx){
		$BoardFile = new BoardTotalFile();
		$BoardFile->delete_board_idx($board_idx) ;
	}

	function fileUpload($board_idx, $upfile_field, $upfile_field_alt)
	{
		$BoardFile = new BoardTotalFile();
		return $BoardFile->insert_row($board_idx, $upfile_field, $upfile_field_alt) ;
	}
	
	function readCntUpdate($board_idx, $cnt = 1)
	{
		$q = '
			UPDATE '.$this->table.' 
			SET 
			  read_cnt = read_cnt+:read_cnt
			WHERE board_idx = :board_idx ' ;
		$stmt = $this->db()->prepare($q);
		$stmt->bindValue(':read_cnt',is_numeric($cnt)?$cnt:1,PDO::PARAM_INT);
		$stmt->bindValue(':board_idx',$board_idx,PDO::PARAM_INT);
		
		stmtExecute($stmt);
	}
	
	function delete($board_idx){
		$class = get_class($this);
		$_this = new $class();
		
		$row = $_this->getRowCommon('board_idx', $board_idx);
		
		if( count($row) >= 1 ){
			$_this->addWhere('board_idx', $board_idx);
			$_this->deleteCommon($_this->db());

			$BoardFile = new BoardTotalFile() ;
			$BoardFile->delete_board_idx($board_idx);
		}else{
			return false;
		}
	}
	
	function getList_notice($is_notice = 'Y'){
		$this->addWhere('is_notice', $is_notice);
		return $this->getList();
	}
	
	
	function getList($no = ''){		
		$a = $this->getListCommon($this->getPrimaryField());
		$ret = array();
		foreach( $a as $k => $r ){
			$r['no'] = $no--;

			$BoardFile = new BoardTotalFile() ;			
			$r['file_list'] = $BoardFile->getList($r['board_idx']);
			$ret[$k] = $r;
		}
		
		return $ret;
	}
	
	
	
	function getRowNext($board_idx)
	{
		$class = get_class($this);
		$_this = new $class();
		//$_this = new SaveCommon();
		$_this->addWhereStr('board_idx', ' > '.$board_idx);
		$_this->setOrder( 'board_idx asc ');
		$_this->setLimit( 1 );
		
		$a = $_this->getListCommon('board_idx');
		if( is_array($a) && count($a) >= 1 ){
			foreach($a as $k => $r ){
				break;
			}
		}else{
			$r = array();
		}
		return $r;
	}
	
	function getRowPrev($board_idx)
	{
		$class = get_class($this);
		$_this = new $class();
		//$_this = new SaveCommon();
		$_this->addWhereStr('board_idx', ' < '.$board_idx);
		$_this->setOrder( 'board_idx DESC ');
		$_this->setLimit( 1 );
		
		$a = $_this->getListCommon('board_idx');
		if( is_array($a) && count($a) >= 1 ){
			foreach($a as $k => $r ){
				break;
			}
		}else{
			$r = array();
		}
		return $r;
	}
}
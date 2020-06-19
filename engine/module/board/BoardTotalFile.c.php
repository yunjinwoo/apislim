<?php

/**
 * 게시판 파일관리 
 * 
 * @version 1
 */
define('_db_board_total_file_', _db_fix_.'board_total_files') ; 
class BoardTotalFile extends SaveCommon {
	protected $table = _db_board_total_file_;
	function __construct() {
	}
	
	function getPrimaryField() {
		return 'file_idx';
	}
	
	
	/**
	 * 설정된 정보에 해당하는 정보 가져오기
	 * 
	 * @return array array( file_idx => row )
	 */
	function getList($board_idx){
		$BoardFile = new BoardTotalFile;
		$BoardFile->addWhere('board_idx', $board_idx);
		$list = $BoardFile->getListCommon($this->getPrimaryField());
		
		return $list ;
	}
	function delete_board_idx($board_idx)
	{
		$File = new BoardTotalFile() ;
		$File->addWhere('board_idx', $board_idx);
		
		$this->_delete($File);
	}	
	function delete($file_idx)
	{
		$File = new BoardTotalFile() ;
		$File->addWhere('file_idx', $file_idx);
		$list = $File->getListCommon($this->getPrimaryField());
	}
	private function _delete($file_obj){
		$list = $file_obj->getListCommon($this->getPrimaryField());
		
		foreach($list as $k => $r ){
			$p = $_SERVER['DOCUMENT_ROOT'].$r['file_path'];
			if(is_file($p)){
				@unlink($p);
			}
		}
		$file_obj->deleteCommon($file_obj->db());
		
		return true ;
	}
	
	
	static function download($file_idx)
	{
		$BoardTotalFile = new BoardTotalFile() ;
		$BoardTotalFile->addWhere('file_idx', $file_idx);
		$row = $BoardTotalFile->getRowCommon($this->getPrimaryField(), $file_idx);
		
		//echo $row['file_type'] ;
		header('Content-type: '.$row['file_type']);
		header('Content-Disposition: attachment; filename="'.$row['file_upload_name'].'"');
		readfile($_SERVER['DOCUMENT_ROOT'].$row['file_path']);
		//readfile($_SERVER['DOCUMENT_ROOT'].$row['file_path']);
		exit ;
	}
	
	function insert_row($board_idx, $upload_field, $alt_field = '')
	{
		$webpath = _WEB_PATH_DATA_.'/board/'.date('Ym') ;
		$path = _PATH_data_.'/board/'.date('Ym') ;
		if(!is_dir($path)) mkdir( $path ) ;
		
		$cnt = 0 ;
		$BoardTotalFile = new BoardTotalFile();
		if( isset($_FILES[$upload_field]['tmp_name']) && is_uploaded_file($_FILES[$upload_field]['tmp_name']) )
		{
			$tmp_name = $_FILES[$upload_field]['tmp_name'];
			$name = $_FILES[$upload_field]['name'];
			$size = $_FILES[$upload_field]['size'];
			$type = $_FILES[$upload_field]['type'];
			
			$a = explode('.',$name);
			$ext = array_pop($a) ;
			$filename =  $board_idx.'_'.microtime(true).rand(1,1000).'.'.$ext ;

			move_uploaded_file($tmp_name, $path.'/'.$filename);
			$BoardTotalFile->addData('board_idx', $board_idx);
			
			$BoardTotalFile->addData('file_path', $webpath.'/'.$filename);
			$BoardTotalFile->addData('file_name', $filename);
			$BoardTotalFile->addData('file_alt', $alt_field);
			$BoardTotalFile->addData('file_upload_name', $name);
			$BoardTotalFile->addData('file_size', $size);
			$BoardTotalFile->addData('file_type', $type);
			
			$BoardTotalFile->addData('reg_login_info', getLoginInfo());
			
			$BoardTotalFile->insertCommon($BoardTotalFile->db());
			return $webpath.'/'.$filename;
		}
		return '';
	}
}

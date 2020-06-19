<?php
 
class FileManager extends SaveCommon {
	protected $ftp_host = "qownsimg.cafe24.com";// ftp host명 
	protected $ftp_id = "qownsimg";				// ftp 아이디
	protected $ftp_pw = "qownsimg!@";			// ftp 비밀번호 
	protected $ftp_port = "21";					// ftp 포트
	protected $ftpObj;
	
	
	protected $session_key ;
	protected $path ;
	protected $web_path ;
	protected $table = _db_file_;
			
	function __construct($session_key='') {
		parent::__construct($this->table);
		if( empty($session_key) ){
			$session_key = session_id();
		}
		$this->session_key = $session_key ;
		$ym = date('Ym') ;
		$this->path		= _PATH_data_.'/'.$ym ;
		$this->web_path = _WEB_PATH_DATA_.'/'.$ym ;
		
		if( !is_dir( $this->path ) )
			mkdir($this->path) ;
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'file_idx' => '기본키'
		,	'session_key' => '세션키'
		,	'file_domain' => '도메인'
		,	'file_path' => '경로'
		,	'file_name' => '이름'
		,	'file_alt' => '설명'
		,	'file_size' => '크기'
		,	'file_type' => '$_FILES[type]'
		,	'upload_type' => '등록구분(에디터/파일/이미지등)'
		,	'is_use' => '이건 모지...'
		,	'reg_date' => '등록일'
			
		);
	}
	
	function getPrimaryField(){
		return 'file_idx';
	}
	
	
	function delete($file_idx)
	{
		$FileManager = new FileManager($this->session_key);
		$FileManager->addWhere('session_key', $this->session_key);
		$FileManager->addWhere('file_idx', $file_idx);
		$row = $FileManager->getOne($file_idx);
		$FileManager->deleteCommon($FileManager->db());
		
		if( empty($row['file_doamin']) && is_file($_SERVER['DOCUMENT_ROOT'].$row['file_path']) )
			unlink($_SERVER['DOCUMENT_ROOT'].$row['file_path']) ;
	}
	
	function delete_session_key()
	{
		$FileManager = new FileManager($this->session_key);
		$FileManager->addWhere('session_key', $this->session_key);
		$list = $FileManager->getListCommon();
		
		foreach( $list as $k => $row )
		{
			$FileManager->delete($row['file_idx']) ;
		}
	}
}


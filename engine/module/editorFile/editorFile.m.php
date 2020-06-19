<?php
require_once _PATH_.'/editor/editor.f.php';

define( '_SQL_FILE_SELECT_' , '
	SELECT * FROM '._db_file_.' 
	WHERE session_key = :session_key
' ) ;

define( '_SQL_FILE_SELECT_ROW' , '
	SELECT * FROM '._db_file_.' 
	WHERE file_idx = :file_idx
' ) ;

define( '_SQL_FILE_DELETE_' , '
	DELETE FROM '._db_file_.' 
	WHERE file_idx = :file_idx
' ) ;

define( '_SQL_FILE_INSERT_' , '
	INSERT INTO  '._db_file_.' 
	SET 		
		`session_key`	= :session_key
	,	`file_domain`	= :file_domain
	,	`file_path`		= :file_path
	,	`file_alt`		= :file_alt
	,	`file_name`		= :file_name
	,	`file_size`		= :file_size
	,	`file_type`		= :file_type
	,	`reg_date`		= :reg_date
' ) ;

define( '_SQL_FILE_UPDATE_USE_' , '
	UPDATE '._db_file_.' 
	SET
		is_writing = :is_writing
	WHERE session_key = :session_key
' ) ;
 
class EditorFile {
	protected $session_key ;
	protected $path ;
	protected $web_path ;
			
	function __construct($session_key) {
		$this->session_key = $session_key ;
		$ym = date('Ym') ;
		$this->path		= _PATH_data_.'/editor/'.$ym ;
		$this->web_path = _WEB_PATH_DATA_.'/editor/'.$ym ;
		
		if( !is_dir( $this->path ) )
			mkdir($this->path) ;
	}
	
	function getList($type='')
	{
		if( $type == 'all' ){
		
			return array() ;
		}
		
		$sttm = db()->prepare(_SQL_FILE_SELECT_) ;
		$sttm->bindValue(':session_key' ,  $this->session_key) ;
		stmtExecute($sttm);
		
		return $sttm->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function insert($tmp_file, $file_name, $file_type, $file_size, $file_alt, $domain = '' )
	{
		$aName = explode('.',$file_name) ;
		$filename = microtime(true).rand(1,1000).'.'.$aName[1] ;
		$filepath = $this->path.'/'.$filename ;
		move_uploaded_file($tmp_file, $filepath );
		
		$sttm = db()->prepare(_SQL_FILE_INSERT_) ;
		$sttm->bindValue(':session_key'	,  $this->session_key ) ;
		$sttm->bindValue(':file_domain'	,  $this->valid($domain,'file_domain')) ;
		$sttm->bindValue(':file_path'	,  $this->valid($this->web_path.'/'.$filename,'file_path')) ;
		$sttm->bindValue(':file_alt'	,  $this->valid($file_alt,'file_alt')) ;
		$sttm->bindValue(':file_name'	,  $this->valid($file_name,'file_name')) ;
		$sttm->bindValue(':file_size'	,  $this->valid(F::number($file_size),'file_size') ,PDO::PARAM_INT ) ;
		$sttm->bindValue(':file_type'	,  $this->valid($file_type,'file_type')) ;
		$sttm->bindValue(':reg_date'	,  date('Y-m-d H:i:s')) ;
		
		stmtExecute($sttm);
		
		$sttm = db()->prepare(_SQL_FILE_SELECT_ROW) ;
		$sttm->bindValue(':file_idx' ,  db()->lastInsertId(), PDO::PARAM_INT) ;
		stmtExecute($sttm);
		return $sttm->fetch(PDO::FETCH_ASSOC);
	}
	
	function delete($file_idx)
	{
		$sttm = db()->prepare(_SQL_FILE_SELECT_ROW) ;
		$sttm->bindValue(':file_idx' ,  $file_idx, PDO::PARAM_INT) ;
		stmtExecute($sttm);
		$row = $sttm->fetch(PDO::FETCH_ASSOC);
		if( empty($row['file_doamin']) )
			unlink($_SERVER['DOCUMENT_ROOT'].$row['file_path']) ;
		
		$sttm = db()->prepare(_SQL_FILE_DELETE_) ;
		$sttm->bindValue(':file_idx' ,  $row['file_idx']) ;
		stmtExecute($sttm);
	}
	
	function delete_session_key()
	{
		$sttm = db()->prepare(_SQL_FILE_SELECT_) ;
		$sttm->bindValue(':session_key', $this->session_key) ;
		stmtExecute($sttm) ;
		
		$aList = $sttm->fetchAll(Pdo::FETCH_ASSOC) ;
		foreach( $aList as $k => $row )
		{
			$this->delete($row['file_idx']) ;
		}
	}
				
	function use_writing( $is_writing = 'Y' )
	{
		$sttm = db()->prepare(_SQL_FILE_UPDATE_USE_) ;
		$sttm->bindValue(':is_writing', $is_writing);
		$sttm->bindValue(':session_key', $this->session_key);
		
		stmtExecute($sttm);
	}
	
	function valid($data,$type)
	{
		switch( $type )
		{
			case 'file_domain'	: $data = F::str($data) ; break ;
			case 'file_alt'		: $data = F::str($data) ; break ;
			case 'file_path'	: $data = F::str($data) ; break ;
			case 'file_name'	: $data = F::str($data) ; break ;
			case 'file_size'	: $data = F::number($data,0) ; break ;
			case 'file_type'	: $data = F::str($data) ; break ;
		}
		
		return $data ;
	}
}


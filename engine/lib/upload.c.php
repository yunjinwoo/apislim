<?php
/**
 * Description of upload
 *
 * @author Administrator
 */


function img_upload($files, $filename, $path = 'upload')
{
	$Upload = new Upload('image');
	$upload_path = '';
	if(is_uploaded_file($_FILES[$files]['tmp_name']) )
	{
		$aFile = array(
			'name' => $_FILES[$files]['name']
		,	'type' => $_FILES[$files]['type']
		,	'tmp_name' => $_FILES[$files]['tmp_name']
		,	'error' => $_FILES[$files]['error']
		,	'size' => $_FILES[$files]['size']
		);
		
		$UploadResult = $Upload->move($aFile, $path, $filename);
		if(!$UploadResult->isUploaded){
			exitJs($UploadResult->log);
			exit;
		}
		$upload_path = $UploadResult->path.'/'.$UploadResult->name;
		
		
	}
	
	return $upload_path;
}

function getUploadWebPath($folder="")
{
	$ret = _WEB_PATH_DATA_;
	if(!empty($folder))
	{
		$ret = $ret.'/'.$folder;
		if(!is_dir($_SERVER['DOCUMENT_ROOT'].$ret))
			mkdir($_SERVER['DOCUMENT_ROOT'].$ret, 0777);
	}
	return $ret;
}


class Upload {
	private $aUploadType = array() ; // image or ....
	private $uploadPath = '' ; // upload path 
	private $uploadName = '' ; // upload file name 
	private $aUploadExt = array() ; // upload file name 
	
	function __construct($extUse = '', $type = '', $path = '' , $name = '' )
	{
		if( !empty($type) )
			$this->aUploadType[] = $type ; 
		if( !empty($path) )
			$this->uploadPath = $path ; 
		if( !empty($name) )
			$this->uploadName = $name ; 
		if( !empty($extUse) )
		{
			$a = explode(',', $extUse);
			foreach( $a as $k => $v )
			{
				switch( $v )
				{
					case 'image' : 
						$this->aUploadExt = array_merge($this->aUploadExt, array('gif','bmp','jpg','jpeg','png','zip','g7'));
						break;
					case 'office' : 
						$this->aUploadExt = array_merge($this->aUploadExt, array('xls','xlsx','ppt','pptx','doc','docx'));
						break;
				}
			}
		}
		
	}
	
	/**
	 * 2013-08-07
	 * 지정된 이름과 경로로 파일이동후 결과 클래스 반환
	 * @param array $_FILES
	 * [@param string]
	 * [@param string]
	 * @return object UploadResult
	 */
	function move( $aFile , $path = '' , $name = '' )
	{
		$result = new UploadResult ;
		
		$this->uploadPath = getUploadWebPath($path) ; 
		if( !empty($name) ) $this->uploadName = $name ; 
		
		if( empty($this->uploadPath) ) return $result->error('$this->uploadPath 미설정' ) ;
		if( empty($this->uploadName) ) return $result->error('$this->uploadName 미설정' ) ;
		
		$a = explode('/',$aFile['type']) ;
		$uploadType = $a[0] ;
		$uploadExp = strtolower(substr(strrchr($aFile['name'],"."),1));
		$uloadFileName = $this->uploadPath.'/'.$this->uploadName.'.'.$uploadExp ;
		
		//'/home/hosting_users/chagoftp/www';
		$root_path = get_upload_root_path();
		
		$result->name = $this->uploadName.'.'.$uploadExp ;
		$result->path = $this->uploadPath ;
		$result->size = $aFile['size'] ;
		$result->type = $uploadType ;
		
		if( !is_dir($root_path.$result->path) ){
			mkdir($root_path.$result->path,0777);
			@chmod($root_path.$result->path, 0777);
		}
		//exit;
		
		$isMove = false ;
		if( count($this->aUploadType) == 0 )
			$isMove = true ;

//		foreach($this->aUploadType as $v )
//			if( $v == $result->type ) {
//				$isMove = true ;
//				break ;
//			}
		
		foreach($this->aUploadExt as $v)
			if( $v == $uploadExp ) {
				$isMove = true ;
				break ;
			}
		
//		$q = '
//			INSERT INTO '._db_upload_list_.'
//			SET
//				path = :path
//			,	name = :name
//			,	type = :type
//			,	size = :size
//			,	log = :log
//			';
//		$stmt = db()->prepare($q);
//		$log = $isMove ? "" : "error";
//		stmtExecute($stmt, array(
//				'path' => $result->path
//			,	'name' => $result->name
//			,	'size' => $result->size
//			,	'type' => $result->type
//			,	'log' => $log
//		));
		
		if( $isMove ) {
			//$root_path = _DOCUMENT_ROOT_;
			if( is_file($root_path.$result->path.'/'.$result->name) ){
				@unlink( $root_path.$result->path.'/'.$result->name );
			}
			if( move_uploaded_file( $aFile['tmp_name'], $root_path.$result->path.'/'.$result->name ) ){
				chmod(_DOCUMENT_ROOT_.$result->path.'/'.$result->name,0777);
				$result->isUploaded = true ;
			}else{
				return $result->error ('알수없는 에러[move_uploaded_file:'.$root_path.$result->path.'/'.$result->name.']');
			}
		} else return $result->error('허용안되는 확장자' ) ;
		
		
		
		
		return $result ; 
	}
}

class UploadResult
{
	public $name ;
	public $path ;
	public $size ;
	public $type ;
	
	public $isUploaded = true;
	public $log ;
	
	function __construct($name = '',$path = '',$size = '',$type = '') {
		$this->name = $name ;
		$this->path = $path ;
		$this->size = $size ;
		$this->type = $type ;
	}
	
	function error( $log )
	{
		$this->isUploaded = false ;
		$this->log = $log ;
		return $this ;
	}
}
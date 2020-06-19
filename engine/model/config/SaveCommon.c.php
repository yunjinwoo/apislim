<?php
/**
 * DB 연결에 기본적인 클래스
**/
class SaveCommon extends ListCommon {
	protected $aData;
	protected $aDataParam;
	
	protected $table;
	protected $table_field;
	protected $table_field_file;//업로드 필드
	protected $table_hash_field;//단방향 암호화필드
	protected $table_encode_field;//양방향 암호화필드
	 
	protected $board_data;
	
	
	
	function getPageData(){
		return $this->board_data['page_data'];
	}
	//$ProductRental->setPageData($page, $ProductRental->getInfoPageSize(), ceil(Product::$product_total_cnt/$list_size));
	function setPageData($page, $page_size, $total_page_count){
		$this->board_data['page_data']['page'] = $page;
		$this->board_data['page_data']['page_size'] = $page_size;
		if( empty($total_page_count) ){ $total_page_count = 1; }
		$this->board_data['page_data']['total_page_count'] = $total_page_count;
	}
	
	
	function getTable(){
		return $this->table;
	}
	function getField(){
		return $this->table_field;
	}
	
	// POST 바로 적용할떄 사용하자
	// postArr 과 차이가 있다!!!
	function getFieldPost($post = array()){
		if( count($post) < 1 ){
			$post = $_POST;
		}
		$a = array();
		if(is_array($this->table_field)){
			if( get_magic_quotes_gpc() ){
				foreach( $this->table_field as $k => $v ){
					if( isset($post[$k]) ){
						if( is_array($post[$k]) ){
							foreach( $post[$k] as $kk => $vv ){
								$post[$k][$kk] = stripslashes(trim($vv));
							}
							$a[$k] = implode(',' , $post[$k] );
						}else{
							$a[$k] = stripslashes(trim($post[$k]));
						}
					}
				}
			}else{
				foreach( $this->table_field as $k => $v ){
					if( isset($post[$k]) ){
						if( is_array($post[$k]) ){
							foreach( $post[$k] as $kk => $vv ){
								$post[$k][$kk] = trim($vv);
							}
							$a[$k] = implode(',' , $post[$k] );
						}else{
							$a[$k] = trim($post[$k]);
						}
					}
				}
			}
		}
		return $a;
	}
	function _addData($aSave){ 
		$arr = $this->table_field;
		if( get_magic_quotes_gpc() ){
			foreach( $arr as $k => $v ){
				if(isset($aSave[$k])){
					if( strpos($k, '_text') === false ){
						$this->addData($k, stripslashes(A::str($aSave, $k))); // 
					}else{
						$this->addData_str($k, stripslashes(A::str($aSave, $k))); // 
					}
				}
			}
		}else{
			foreach( $arr as $k => $v ){
				if(isset($aSave[$k])){
					if( strpos($k, '_text') === false ){
						$this->addData($k, A::str($aSave, $k));
					}else{
						$this->addData_str($k, A::str($aSave, $k));
					}
				}
			}
		}
	}
	function _row_replace($aRow){ 
		// 필요에 따라 수정해서...
		if( is_array($this->table_encode_field) ){
			foreach( $this->table_encode_field as $k => $v ){
				if( isset($aRow[$k])){
					$aRow[$k.'_decode'] = $aRow[$k];
					$aRow[$k] = F::cryptDecode($aRow[$k]);
				}
			}			
		}
		return $aRow;
	}
	
	function getNumber($v){
		return preg_replace('/[^0-9\-\.]/i','',$v);
	}
	
	
	function __construct($table = '') {
		parent::__construct();
		
		if( !empty($table) ){
			$this->table = $table;
		}
	}
	function resetData(){
		$this->aData = array();
		$this->aDataParam = array();
		
		return $this;
	}
	
	function addData($field, $value, $pdo_param = PDO::PARAM_STR ){
		$this->aData[$field] = $value;//htmlentities
		if( isset($this->table_int_field[$field]) ){
			$this->aDataParam[$field] = PDO::PARAM_INT;
		}else{
			$this->aDataParam[$field] = $pdo_param;
		}
		
		
		return $this;
	}
	function addData_str($field, $value, $pdo_param = PDO::PARAM_STR ){
		$this->aData[$field] = $value;
		$this->aDataParam[$field] = $pdo_param;
		
		return $this;
	}
	function unData($field){
		unset($this->aData[$field]);//htmlentities
		unset($this->aDataParam[$field]);
		
		return $this;
	}
	
	function insertDefult(){
		return $this->insertCommon($this->db());
	}
	function updateDefult(){
		return $this->updateCommon($this->db());
	}
	function insertDefault(){
		return $this->insertCommon($this->db());
	}
	function updateDefault(){
		return $this->updateCommon($this->db());
	}
	
	function insertCommon($pdo, $is_log_insert = true ){
		if( count($this->aData) < 1){
			return false;
		}
		
		$aField = $aValue = array();
		foreach( $this->aData as $field => $value ){
			$aField[] = $field ;
			$aValue[] = ':'.$field ;
		}
		$q = 'INSERT INTO '.$this->table.'
			('.implode( ',', $aField).')
			VALUES
			('.implode( ',', $aValue).')
			';
		
		$stmt = $pdo->prepare($q);
		foreach( $this->aData as $field => $value ){
			$stmt->bindValue(':'.$field, $value, $this->aDataParam[$field]);
		}
		
		if( $is_log_insert ){
			$this->isLogInsert('INSERT', $q);
		}
		
			
		stmtExecute($stmt);
		return $pdo->lastInsertId();
	}
	
	function updateCommon($pdo){
		if( count($this->aWhere) < 1 ){
			return false;
		}
		if( count($this->aData) < 1){
			return false;
		}
		
		$aField = array();
		foreach( $this->aData as $field => $value ){
			$aField[] = $field .' = :'.$field ;
		}
		$q = 'UPDATE '.$this->table.' SET
			'.implode( ' , ', $aField).' '.$this->getWhere() ;

		$stmt = $pdo->prepare($q);
		foreach( $this->aData as $field => $value ){
			$stmt->bindValue(':'.$field, $value, $this->aDataParam[$field]);
		}
			
		$this->isLogInsert('UPDATE', $q);
		
		stmtExecute($stmt);
		return true;
	}
	
	function saveCommon($pdo){
		if( count($this->aWhere) < 1 ){
			return $this->insertCommon($pdo);
		}else{
			return $this->updateCommon($pdo);
		}
	}
	
	function deleteCommon($pdo){
		if( count($this->aWhere) < 1 ){
			return false;
		}
		
		$q = 'DELETE FROM '.$this->table.' '.$this->getWhere() ;
		$stmt = $pdo->prepare($q);
		
		$this->isLogInsert('DELETE', $q);
		
		stmtExecute($stmt);
		return true;
	}
	
	function replaceCommon($pdo){
		if( count($this->aData) < 1){
			return false;
		}
		
		$aField = $aValue = array();
		foreach( $this->aData as $field => $value ){
			$aField[] = $field ;
			$aValue[] = ':'.$field ;
		}
		$q = 'REPLACE INTO '.$this->table.'
			('.implode( ',', $aField).')
			VALUES
			('.implode( ',', $aValue).')
			';
		$stmt = $pdo->prepare($q);
		foreach( $this->aData as $field => $value ){
			$stmt->bindValue(':'.$field, $value, $this->aDataParam[$field]);
		}
		
		$this->isLogInsert('REPLACE', $q);
		
		stmtExecute($stmt);
		return $pdo->lastInsertId();
	}
	
	function isLogInsert($type, $q){
		$this->executeLogCreate($type, $q);
	}
	
	
	function executeLogCreate($execute_type, $q){
		
		$execLog = new SaveCommon('jin_execute_query_log');
		$execLog->addData('user_id'			, MyInfo::getUserId());
		$execLog->addData('execute_type'	, $execute_type);
		$execLog->addData('table_name'		, $this->table);
		$execLog->addData('query_str'		, $q);
		
		$execLog->addData('reg_ip', A::str($_SERVER, 'REMOTE_ADDR'));
		$execLog->addData('http_agent', A::str($_SERVER, 'HTTP_USER_AGENT'));
		$execLog->addData('page_url', A::str($_SERVER, 'REQUEST_URI'));
		$execLog->addData('msg', print_r(debug_backtrace(),true));
		
	//	$execLog->insertCommon($this->db(), false);
	}
	/*
CREATE TABLE `jin_execute_query_log` (
	`query_log_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` VARCHAR(100) NULL DEFAULT NULL,
	`execute_type` VARCHAR(100) NULL DEFAULT NULL,
	`table_name` VARCHAR(100) NULL DEFAULT NULL,
	`query_str` VARCHAR(1000) NULL DEFAULT NULL,
	`reg_ip` VARCHAR(20) NULL DEFAULT NULL,
	`http_agent` VARCHAR(200) NULL DEFAULT NULL,
	`page_url` VARCHAR(200) NULL DEFAULT NULL,
	`msg` TEXT NULL,
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`query_log_idx`)
)
COMMENT='쿼리 정보'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
	 */
	
	
	
	function save($aSave){
		$class = get_class($this);
		$_this = new $class();
		$_this->_addData($aSave);
		
		$form_idx = A::str( $aSave , $this->getPrimaryField());
		
		$root_path = $_SERVER['DOCUMENT_ROOT'];
		$root_path = '/home/hosting_users/chagoftp/www';
		//파일업로드
		if( is_array($this->table_field_file) ){
			foreach( $this->table_field_file as $k=>$v ){
				//파일삭제 경로를 보내서 지우자
				//어쩔수없이 post를 써야 되는듯하넴;;
				$delete_path = A::str($_POST, $k.'_delete');
				//pre($_POST);
				if(!empty($delete_path)){
					if( is_file($root_path.$delete_path) ){
						unlink($root_path.$delete_path);
						$_this->addData($k,'');
					}
				//	pre($k);
				}
				
				//pre($_POST);
				//pre($_FILES);
				$upfile = img_upload($k, $k.'_'.$form_idx.'_'.date('YmdHis'), array_pop(explode('_', $_this->getTable())));
			//	pre([$k, $upfile]);
				//exit;		
				if( $upfile != '' && is_file($root_path.$upfile) ){
					$_this->addData($k, $upfile);
				}
			}
		}
		//암호화
		if( is_array($this->table_encode_field) ){
			foreach( $this->table_encode_field as $k => $v ){
				if( isset($aSave[$k])){
					$_this->addData($k, F::cryptEncode($aSave[$k]));
				}
			}
		}
		//암호화
		if( is_array($this->table_hash_field) ){
			foreach( $this->table_hash_field as $k => $v ){
				if( isset($aSave[$k])){
					$_this->addData($k, F::cryptHash($aSave[$k]));
				}
			}
		}
	
		if( is_numeric($form_idx) ) {
			//수정
			$_this->addWhere($this->getPrimaryField(), $form_idx);
			$_this->saveCommon($_this->db());
		//	pre(['수정',$_this]);
		}else{
			//추가
			$_this->unData( $this->getPrimaryField());
			$_this->addData('reg_date', date('Y-m-d H:i:s'));
			$form_idx = $_this->saveCommon($_this->db());
		}
		
		return $form_idx;
	}
	
	function delete($form_idx){
		$class = get_class($this);
		$_this = new $class();
		$row =$_this->getOne($form_idx);
		
		if( is_numeric($form_idx) ) {
			$_this->addWhere($this->getPrimaryField(), $form_idx);
			$_this->deleteCommon($_this->db());
			
			//에디터 사용인경우 
			if(class_exists('EditorFile') && isset($this->table_field['editor_session_key']) ){
				$EditorFile = new EditorFile(session_id()) ;
				$EditorFile->delete_session_key();
			}

			if( is_array($this->table_field_file) ){
				foreach( $this->table_field_file as $k=>$v ){				
					$path = $_SERVER['DOCUMENT_ROOT'].A::str($row,$k);
					if( is_file($path) ){
						unlink($path);
					}
				}
			}
			return true;
		}
		return false;		
	}
	
	/**
	 * 기본으로 있어야 하는것들 아닌가;;;
	 * 	
	 **/
	public function __set($name, $value)
    {
    //	echo "Setting '$name' to '$value'\n";
		$this->{$name} = $value;
    }

    public function __get($name)
    {
    //	echo "Getting '$name'\n";
        if (array_key_exists($name, $this)) {
			return $this->{$name};
        }
    }
}

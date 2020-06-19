<?php

class MemberCompany extends SaveCommon {
	protected $table = _db_member_company_;
	
	function __construct() {
		$this->setOrder('company_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'company_idx' => '업체 기본키'
		,	'company_group' => '업체 구분'
		,	'company_id' => '업체 아이디'				
		,	'company_pw' => '업체 비밀번호'
			
		,	'is_use' => '사용여부'
			 
		,	'company_name' => '담당자이름'
		,	'company_phone' => '담당자연락처'
		,	'company_email' => '담당자이메일'
			
		,	'company_addr_code' => '업체우편번호'
		,	'company_addr_1' => '업체주소1'
		,	'company_addr_2' => '업체주소2'
			
		,	'company_hp' => '업체 연락처'
		,	'read_company_name' => '업체명'
			
		,	'company_info_product' => '대표상품소개'
		,	'company_info1' => '한줄'
		,	'company_info2' => '200자'
		,	'company_info3' => '상세설명'
			
		,	'service_mailing' => '메일링서비스'
		,	'service_sms' => 'sms서비스'
			
				
				
		,	'reg_admin_login_info' => '등록아이디'
		,	'reg_date' => '등록일'
		,	'update_admin_login_info' => '수정아이디'
		,	'update_date' => '수정일'

		);
	}
	
	
	function file_upload($company_idx, $upload_field)
	{
		
		$webpath = _WEB_PATH_DATA_.'/user/'.date('Ym') ;
		$path = _PATH_data_.'/user/';
		if(!is_dir($path)) mkdir( $path ) ;
		$path = _PATH_data_.'/user/'.date('Ym') ;
		if(!is_dir($path)) mkdir( $path ) ;
		
		$cnt = 0 ;
		if( isset($_FILES[$upload_field]['tmp_name']) && is_uploaded_file($_FILES[$upload_field]['tmp_name']) )
		{
			$tmp_name = $_FILES[$upload_field]['tmp_name'];
			$name = $_FILES[$upload_field]['name'];
			$size = $_FILES[$upload_field]['size'];
			$type = $_FILES[$upload_field]['type'];
			if( strpos( $type , 'image') !== false ){
				$a = explode('.',$name);
				$ext = array_pop($a) ;
				$filename =  $company_idx.'_'.microtime(true).rand(1,1000).'.'.$ext ;

				move_uploaded_file($tmp_name, $path.'/'.$filename);
				
				$class = get_class($this);
				$_this = new $class();
				$_this->addWhere('company_idx', $company_idx);
				$_this->addData($upload_field, $webpath.'/'.$filename);
				
				$_this->saveCommon($_this->db());
				logProgram::put('action', '첨부파일수정['.$upload_field.']'.chr(13).print_r($_FILES[$upload_field],true));
			}
		}
		if( G::request('del_'.$upload_field) == 'on' ){
			$class = get_class($this);
			$_this = new $class();
			$row = $_this->getRowCompanyIdx($company_idx);
			$path = _PATH_.'/'.$row[$upload_field];
			if( is_file($path) ){
				unlink($path);
			}

			$_this->addWhere('company_idx', $company_idx);
			$_this->addData($upload_field, '');
			$_this->saveCommon($_this->db());
			logProgram::put('action', '첨부파일삭제['.$upload_field.']'.chr(13).print_r($_FILES[$upload_field],true));
		}
	}

	function delete($company_idx){
		$row = $this->getRowCompanyIdx($company_idx);
		
//		$company_idx_group = $row['company_idx_group'];
//		Code::delCode('company_sub_addr_han', $company_idx_group);
//		Code::delCode('company_sub_addr_eng', $company_idx_group);
		
		$q = 'DELETE FROM '.$this->table.' WHERE company_idx = '.$company_idx;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	function getRowCompanyIdx($company_idx){
		$class = get_class($this);
		$_this = new $class();
		$_this->addWhere('company_idx', $company_idx);
		
		$a = $_this->getList();
		return A::arr($a, $company_idx);
	}
	function getRowCompanyId($company_id){
		$class = get_class($this);
		$_this = new $class();
		
		$_this->addWhere('company_id', $company_id);		
		$a = $_this->getList('company_id');
		
		return A::arr($a, $company_id);
	}
	
	function getList($arr_key_name = 'company_idx'){
		return $this->getListCommon($arr_key_name);
	}
}
<?php

class CompanyLoan extends Company {
	protected $table = _db_company_loan_;
	
	protected $table_log = _db_company_loan_log_;
	protected $table_log_field = '';
	
	function __construct() {
		parent::__construct();
		
		
		$this->table_field = array(
			'company_idx' => '업체 기본키'
		,	'company_group' => '업체 구분'
		,	'is_use' => '로그인허용여부'
			
		,	'user_point' => '포인트'			
		,	'user_point_is_lock' => '포인트잠금'
			
		,	'company_id' => '업체 아이디'				
		,	'company_pw' => '업체 비밀번호'
			
		,	'company_manager_name' => '담당자이름'
		,	'company_manager_phone' => '담당자연락처'
		,	'company_manager_email' => '담당자이메일'
			
		,	'company_addr_code' => '업체우편번호'
		,	'company_addr_1' => '업체주소1'
		,	'company_addr_2' => '업체주소2'
			
		,	'company_hp' => '업체 연락처'
		,	'read_company_name' => '업체명'
		,	'read_company_loan_release_number' => '등록번호'
		,	'read_company_loan_release_name' => '대부업등록기관'
		,	'read_company_loan_release_phone' => '대부업등록기관전화번호'
		,	'read_company_loan_release_date_start' => '대부업_유효기간_시작'
		,	'read_company_loan_release_date_end' => '대부업_유효기간_끝'
		
		
		,	'company_loan_limit' => '대출한도'
		,	'company_loan_term' => '기간'
		,	'company_rates_month' => '월금리'
		,	'company_rates_year' => '연금리'
		,	'company_rates_over' => '연체금리'
			
		,	'company_loan_repay' => '상환방식'
		,	'company_loan_early_charge' => '조기상환수수료'
		,	'company_loan_add' => '추가비용'
			
		,	'company_info_product' => '대표상품소개'
		,	'company_info1' => '한줄'
		,	'company_info2' => '200자'
		,	'company_info3' => '상세설명'
			
		,	'service_mailing' => '메일링서비스'
		,	'service_sms' => 'sms서비스'
			
		,	'reg_ip' => '등록아이피'
		,	'reg_admin_login_info' => '등록아이디'
		,	'reg_date' => '등록일'
		,	'update_admin_login_info' => '수정아이디'
		,	'update_date' => '수정일'
		);
		
		$this->table_log_field = $this->table_field;
		$this->table_log_field['company_log_idx'] = '이력변경 번호';
		$this->table_log_field['log_update_login_info'] = '이력번경어드민정보';
		$this->table_log_field['log_info'] = '이력정보';
		$this->table_log_field['log_date'] = '이력정보 등록일';
	}
	
	function getPrimaryField(){
		return 'company_idx';
	}
	
	
	function saveLog($aSave){
		$_this = new SaveCommon($this->table_log);
		foreach( $this->table_log_field as $k => $v ){
			$_this->addData($k, A::str($aSave, $k));
		}
		
		//추가만 있다.
		$_this->addData('log_update_login_info', A::str($aSave, 'admin_login_info'));
		$_this->addData('log_info', A::str($aSave, 'log_info'));
		$_this->addData('log_date', date('Y-m-d H:i:s'));
		$company_banner_idx = $_this->saveCommon($_this->db());

		return $company_banner_idx;
		
	}
	
	function getListQuery() {
		/*
$q = '
			SELECT * 
			,
				( 
					case 
						now() between read_company_loan_release_date_start and read_company_loan_release_date_end
					when 1 then \'Y\'
					else \'N\'
					end
				) as is_use_release_date
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
				'.$this->getOrder().'
				'.$this->getLimit().'
			';
이쿼리도 되지만 그냥 단순하게... 		 */
		$q = '
			SELECT * 
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
				'.$this->getOrder().'
				'.$this->getLimit().'
			';
		
		return $q;
	}
	
	function _row_replace($aRow){ 
		// 필요에 따라 수정해서..
		//if( $aRow['read_company_loan_release_date_start'] <= _TODAY_ &&  $aRow['read_company_loan_release_date_end'] >= _TODAY_ ){

//		if( $aRow['is_use_release_date'] == '1' ){
//			$aRow['is_use_release_date'] = 'Y';
//		}else{
//			$aRow['is_use_release_date'] = 'N';
//		}
		
		$aRow['is_use_release_date'] = 'N';
		if( $aRow['read_company_loan_release_date_start'] <= _TODAY_ &&  $aRow['read_company_loan_release_date_end'] >= _TODAY_ ){
			$aRow['is_use_release_date'] = 'Y';
		}
		return $aRow;
	}
}
<?php
define('_db_point_company_', _db_fix_.'point_log_company');

class PointCompany extends SaveCommon{
	protected $table = _db_point_company_;
	
	function __construct() {
		parent::__construct();
		
		$this->table_field = array(
			'point_log_idx' => '포인트이력 번호' 
		,	'point_type' => '포인트가감구분'
		,	'company_idx' => '업체 번호' 
		,	'point_adjust' => '가감포인트' 
		,	'point_total' => '포인트합계' 
		,	'reg_info' => '이력로그' 
		,	'reg_admin_login_info' => '등록한 로그인정보' 
		,	'reg_date' => '등록일'
		);
	}
	
	
	function getPrimaryField(){
		return 'point_log_idx';
	}
	
	function getList(){
		return $this->getListCommon($this->getPrimaryField());
	}
	
	/**
	 * @param $company_idx
	 * @param $point_type
	 * @param $form_type
	 * @param $form_idx
	 * @return array
	 * 	 */
	function getFindList($company_idx, $point_type, $form_type = '', $form_idx = '' ){
		$PointCompany = new PointCompany();
		$PointCompany->addWhere('company_idx', $company_idx);
		$PointCompany->addWhere('point_type', $point_type);
		if( !empty($form_type) ){
			$PointCompany->addWhere('loan_form_type', $form_type);
		}
		if( !empty($form_idx) ){
			$PointCompany->addWhere('loan_form_idx', $form_idx);
		}
				
		return $PointCompany->getListCommon($PointCompany->getPrimaryField());
	}
	
	function insertLoanFormView($company_idx, $form_idx, $point_num, $isMsg = ''){
		$point_adjust = -1;//차감
		$reg_info = '고객번호 확인';
		$point_type = 'view';
		if( $isMsg == 'msg' ){
			$point_type = 'msg';
			$reg_info = '고객 문자발송';
		}
		$loan_type = 'form';
		
		
		$this->__insertRow($point_type, $company_idx, $point_adjust, $point_num, $loan_type, $form_idx, $reg_info);
	}
	function insertLoanFormViewLoan($company_idx, $form_idx, $point_num, $isMsg = ''){
		$point_adjust = -1;//차감
		$reg_info = '고객번호 확인';
		$point_type = 'view';
		if( $isMsg == 'msg' ){
			$point_type = 'msg';
			$reg_info = '고객 문자발송';
		}
		$loan_type = 'loan';
		
		
		$this->__insertRow($point_type, $company_idx, $point_adjust, $point_num, $loan_type, $form_idx, $reg_info);
	}
	
	
	function __insertRow($point_type, $company_idx, $point_adjust, $point_num, $loan_form_type, $loan_form_idx, $reg_info){
		$PointCompany = new PointCompany;
		$PointCompany->addData('point_type', $point_type); // 고객확인 
		$PointCompany->addData('point_num', $point_num);
		$PointCompany->addData('point_adjust', $point_adjust);
		$PointCompany->addData('reg_info', $reg_info);
		
		$PointCompany->addData('loan_form_type', $loan_form_type);
		$PointCompany->addData('loan_form_idx', $loan_form_idx);
		
		$PointCompany->addData('company_idx', $company_idx);
		
		//회사 정보 포인트 업데이트
		$CompanyLoan = new CompanyLoan();
		$CompanyRow = $CompanyLoan->getRowCompanyIdx($company_idx);
		
		//통합
		$point_total = A::number($CompanyRow, 'user_point') + ($point_num * $point_adjust);
		$PointCompany->addData('point_total', $point_total);
		
		
		//회사 정보 포인트 업데이트
		$CompanyLoan2 = new CompanyLoan();
		$CompanyLoan2->addData('user_point', $point_total);
		$CompanyLoan2->addWhere('company_idx', $company_idx);
		$CompanyLoan2->updateCommon($CompanyLoan2->db());
		
		
		$PointCompany->addData('reg_admin_login_info', A::str($CompanyRow, 'company_id'));
		$PointCompany->addData('reg_date', date('Y-m-d H:i:s'));
		$PointCompany->saveCommon($PointCompany->db());
	}
}


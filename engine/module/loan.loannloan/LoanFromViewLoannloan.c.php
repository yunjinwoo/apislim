<?php

class LoanFormViewCompanyLoannloan extends LoanFormViewCompany {
	protected $table = _db_loan_form_view_company_loannloan_;
	
	function __construct() {
		parent::__construct();
		
		$this->setOrder('form_view_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
				'form_view_idx' => '기본키'
			,	'form_idx' => '신청번호'
			,	'company_idx' => '업체번호'
			,	'reg_date' => '신청구분'
			,	'end_date' => '입력한 관리자'

		);
	}
	
	function getViewCompanyCnt($form_idx){
		$LoanFormViewCompany = new LoanFormViewCompanyLoannloan();
		$LoanFormViewCompany->addWhere('form_idx', $form_idx);
		
		return $LoanFormViewCompany->getCountCommon();
	}
}
<?php

class ApplyFormViewCompany extends SaveCommon {
	protected $table = _db_apply_form_view_company_;
	
	function __construct() {
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
	
	function getPrimaryField(){
		return 'form_view_idx';
	}
	
	function save($aSave=''){		
		$aSave = $this->getFieldPost();
		$this->_addData($aSave);
		
		$form_view_idx = $this->saveCommon($this->db());
		return $form_view_idx;
	}
	
	function getFormIdxViewCnt($form_idx){
		if(!is_numeric($form_idx)){
			return array('view_cnt'=>0,'total_cnt'=>0);
		}
		$q = '
			select 
			( 
				select count(*) from 
				'.$this->table.'
				where form_idx = '.$form_idx.' and reg_date <= now() and end_date > now() 
			) as view_cnt
			,

			( 
				select count(*) from 
				'.$this->table.'
				where form_idx = '.$form_idx.' 
			) as total_cnt			
			';
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetch();		
	}
	
	function getViewCompanyList($form_idx){
		if(!is_numeric($form_idx)){
			return array();
		}
		$CompanyLoan = new CompanyLoan;
		
		$q = '
			SELECT b.*,a.reg_date as view_reg_date FROM 
				'.$this->table.' a LEFT JOIN 
				'.$CompanyLoan->getTable().' b
			ON a.company_idx = b.company_idx
			WHERE form_idx = '.$form_idx.' ';

		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while( $a = $stmt->fetch() ){
			$ret[A::str($a, $CompanyLoan->getPrimaryField())] = $a;
		}
		return $ret;		
	}
	
	
	/********/
	/********/
	/********/
	/********/
	/********/
	/********/
	/********/
	/********/
	/********/
	/********//********/
	function getViewCompanyCnt($form_idx){
		$LoanFormViewCompany = new LoanFormViewCompany();
		$LoanFormViewCompany->addWhere('form_idx', $form_idx);
		
		return $LoanFormViewCompany->getCountCommon();
	}

	function delete($company_idx){
		$row = $this->getRowCompanyIdx($company_idx);
		
//		$company_idx_group = $row['company_idx_group'];
//		Code::delCode('company_sub_addr_han', $company_idx_group);
//		Code::delCode('company_sub_addr_eng', $company_idx_group);
		
		$q = 'DELETE FROM '.$this->table.' WHERE form_view_idx = '.$company_idx;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	
	
	
	
	function setWhereRegDateSearch( $start_date, $end_date, $month = '' )
	{
		if( F::isDate($start_date) && F::isDate($end_date) ){
			$this->addWhereStr('reg_date', ' BETWEEN \''.$start_date.' 00:00:00\' AND \''.$end_date.' 23:59:59\' ' );
		}elseif( F::isDate($end_date) ){
			$this->addWhereStr('reg_date', ' <= \''.$end_date.' 23:59:59\' ' );
		}else if( F::isDate($start_date) ){
			$this->addWhereStr('reg_date', ' >= \''.$start_date.' 00:00:00\' ');
		}elseif(is_numeric ($month) ){
			// 날짜가 없으면 기본 6개월로...
			$start_date = date('Y-m-d', strtotime(' -'.$month.' month' ));
			$this->addWhereStr('reg_date', ' >= \''.$start_date.' 00:00:00\' ');
		}
	}
}
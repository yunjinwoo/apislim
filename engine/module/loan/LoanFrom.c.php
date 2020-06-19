<?php

class LoanFormCode {
	static $status = array(
			0 => '대기중'
		,	50 => '상담중'
		,	52 => '부재'
		,	57 => '견적서발송'
		,	106 => '계약서진행'

		,	107 => '계약완료'
		,	113 => '차량인수완료'
		,	110 => '보류'
		,	115 => '단기랜트카신청'
		,	114 => '고객취소'

		,	112 => '불가(신용불량자)'
		,	22 => 'SMS발송'
		,	200 => '번호중복'
	);
}	
class LoanForm extends SaveCommon {
	protected $table = _db_loan_form_;
	
	function __construct() {
		$this->setOrder('form_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
				'form_idx' => '기본키'
			,	'media' => '미디어'
			,	'keyword' => '키워드'
			,	'reg_type' => '신청구분'
			,	'reg_admin_id' => '입력한 관리자'
			,	'is_use' => '사용여부'
			
			,	'loan_user_name' => '고객명'
			,	'loan_phone1' => '고객전화번호'
			,	'loan_phone2' => '고객전화번호'
			,	'loan_phone3' => '고객전화번호'
			,	'loan_passwd_crypt' => '비밀번호'
			,	'loan_gender' => '성별'
			,	'loan_title' => '제목'
			,	'loan_content' => '내용'
			,	'loan_area' => '지역'
			,	'loan_job' => '직업'
			
			,	'loan_age' => '나이'
			,	'loan_amount' => '희망금액'
			,	'loan_type' => '대출구분'
			
			,	'reg_ip' => '저장아이피'
			,	'reg_date' => '등록일'
			,	'update_time' => '수정일'

		);
	}
	
	
	function updateUse($idx,$is_use = 'Y')
	{
		if( is_numeric($idx) ){
			$q = ' update '.$this->table.' set is_use = \''.F::YN($is_use).'\' WHERE form_idx = '.$idx;
			$this->db()->query($q);
		}
	}
	
	function updateReadCnt($idx)
	{
		if( is_numeric($idx) ){
			$q = ' update '.$this->table.' set read_cnt = read_cnt + 1 WHERE form_idx = '.$idx;
			$this->db()->query($q);
		}
	}
	
	
	
	function save($aSave){
		$class = get_class($this);
		$_this = new $class();
		$_this->_addData($aSave);
		
		
		$form_idx = A::str( $aSave , 'form_idx');
		if( is_numeric($form_idx) ) {
			//수정
			$_this->addData('update_admin_login_info', A::str($aSave, 'admin_login_info'));
			$_this->addWhere('form_idx', $form_idx);
			$_this->saveCommon($_this->db());
		}else{
			//추가
			$_this->addData('reg_admin_login_info', A::str($aSave, 'admin_login_info'));
			$_this->addData('reg_date', date('Y-m-d H:i:s'));
			$form_idx = $_this->saveCommon($_this->db());
		}
		
		return $form_idx;
	}

	function delete($form_idx){
		$row = $this->getRow($form_idx);
		
//		$company_idx_group = $row['company_idx_group'];
//		Code::delCode('company_sub_addr_han', $company_idx_group);
//		Code::delCode('company_sub_addr_eng', $company_idx_group);
		
		$q = 'DELETE FROM '.$this->table.' WHERE form_idx = '.$form_idx;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	function getRow($form_idx){
		return $this->getRowCommon('form_idx', $form_idx);
	}
	
	
	function getList($arr_key_name = 'form_idx'){
		return $this->getListCommon($arr_key_name);
	}
	
	
	function getCntMonth($yyyymm){
		$class = get_class($this);
		$_this = new $class();
		$_this->addWhere('is_use', 'Y');
		$_this->addWhereStr('reg_date', ' LIKE \''.$yyyymm.'%\' ');
		return $_this->getCountCommon();
	}
	function getCntTotal(){
		$class = get_class($this);
		$_this = new $class();
		$_this->addWhere('is_use', 'Y');
		return $_this->getCountCommon();
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
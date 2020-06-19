<?php

class LoanFormLoannloan extends LoanForm {
	protected $table = _db_loan_form_loannloan_;
	
	function __construct() {
		parent::__construct();
		
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
	
	
}
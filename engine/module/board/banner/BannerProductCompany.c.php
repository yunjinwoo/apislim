<?php

class BannerProductCompany extends BannerCompany {
	protected $table = _db_banner_company_product_;
	
	protected $table_log_field;
	protected $table_log = _db_banner_company_product_log_;
	
	
	
	function __construct() {
		parent::__construct();
		
		
		$this->table_field = array(
				'product_idx' => '베너 번호' 
			,	'company_idx' => '업체 번호' 
			,	'product_0' => '전체' 
			,	'product_1' => '직장인대출' 
			,	'product_2' => '무직자대출' 
			,	'product_3' => '여성대출' 
			,	'product_4' => '개인돈대출' 
			,	'product_5' => '연체자대출' 
			,	'product_6' => '소액대출' 
			,	'product_7' => '무방문대출' 
			,	'product_8' => '일수대출' 
			,	'product_9' => '당일대출' 
			,	'product_10' => '사업자대출' 
			,	'product_11' => '월변대출' 
			,	'product_12' => '저신용자대출' 
			,	'product_13' => '신용대출' 
			,	'product_14' => '추가대출' 
			,	'product_15' => '자동차담보' 
			,	'product_16' => '부동산담보' 
			,	'product_17' => '기타대출' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_date' => '등록일' 
			,	'update_date' => '수정일'
		);
		
		$this->table_log_field = array(
				'product_log_idx' => '베너로그 번호' 
			,	'product_idx' => '베너 번호' 
			,	'company_idx' => '업체 번호' 
			,	'product_0' => '전체' 
			,	'product_1' => '직장인대출' 
			,	'product_2' => '무직자대출' 
			,	'product_3' => '여성대출' 
			,	'product_4' => '개인돈대출' 
			,	'product_5' => '연체자대출' 
			,	'product_6' => '소액대출' 
			,	'product_7' => '무방문대출' 
			,	'product_8' => '일수대출' 
			,	'product_9' => '당일대출' 
			,	'product_10' => '사업자대출' 
			,	'product_11' => '월변대출' 
			,	'product_12' => '저신용자대출' 
			,	'product_13' => '신용대출' 
			,	'product_14' => '추가대출' 
			,	'product_15' => '자동차담보' 
			,	'product_16' => '부동산담보' 
			,	'product_17' => '기타대출' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_info' => '이력로그' 
			,	'reg_admin_login_info' => '등록한 로그인정보' 
			,	'reg_date' => '등록일'
		);
	}
	
	function getPrimaryField(){
		return 'product_idx';
	}
	
	
}
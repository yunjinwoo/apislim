<?php
class CarCompanyInfo extends SaveCommon {
	protected $table = 'car_company_info';
	
	function __construct() {
		parent::__construct($this->table);
		
		$this->min_price = 0;
		$this->max_price = 0;
		
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'car_company_seq' => '기본키'
		,	'country' => '국산,수입'
		,	'company' => '제조사'
		,	'company_logo' => '제조사 로고'
		,	'company_info' => '제조사 설명'
		,	'reg_date' => '등록일'		
		,	'update_date' => '수정일'
		);
		// 테이블 업로드필드
		$this->table_field_file = array(
			'company_logo' => '렌트사로고'
		);
	}
	
	function getPrimaryField(){
		return 'car_company_seq';
	}
	
	
	function getAllLogoList(){
		$arr = $this->getListCommon();
		$ret = [];
		foreach( $arr as $k=> $row){
			$ret[$row['country']][$row['company']] = $row['company_logo'];
		}
		return $ret;
	}
}

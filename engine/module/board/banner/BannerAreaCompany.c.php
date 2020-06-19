<?php

class BannerAreaCompany extends BannerCompany {
	protected $table = _db_banner_company_area_;
	protected $table_log = _db_banner_company_area_log_;
	protected $table_log_field;
			
	function __construct() {
		parent::__construct();
		
		
		$this->table_field = array(
				'area_idx' => '베너 번호' 
			,	'company_idx' => '업체 번호' 
			,	'area_0' => '전국' 
			,	'area_1' => '서울' 
			,	'area_2' => '경기' 
			,	'area_3' => '인천' 
			,	'area_4' => '대전' 
			,	'area_5' => '대구' 
			,	'area_6' => '부산' 
			,	'area_7' => '광주' 
			,	'area_8' => '울산' 
			,	'area_9' => '세종' 
			,	'area_10' => '강원' 
			,	'area_11' => '충북' 
			,	'area_12' => '충남' 
			,	'area_13' => '전북' 
			,	'area_14' => '전남' 
			,	'area_15' => '경북' 
			,	'area_16' => '경남' 
			,	'area_17' => '제주' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_date' => '등록일' 
			,	'update_date' => '수정일'
		);
		
		$this->table_log_field = array(
				'area_log_idx' => '베너로그 번호' 
			,	'area_idx' => '베너 번호' 
			,	'company_idx' => '업체 번호' 
			,	'area_0' => '전국' 
			,	'area_1' => '서울' 
			,	'area_2' => '경기' 
			,	'area_3' => '인천' 
			,	'area_4' => '대전' 
			,	'area_5' => '대구' 
			,	'area_6' => '부산' 
			,	'area_7' => '광주' 
			,	'area_8' => '울산' 
			,	'area_9' => '세종' 
			,	'area_10' => '강원' 
			,	'area_11' => '충북' 
			,	'area_12' => '충남' 
			,	'area_13' => '전북' 
			,	'area_14' => '전남' 
			,	'area_15' => '경북' 
			,	'area_16' => '경남' 
			,	'area_17' => '제주' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_info' => '이력로그' 
			,	'reg_admin_login_info' => '등록한 로그인정보' 
			,	'reg_date' => '등록일'
		);
	}
	
	
	function getPrimaryField(){
		return 'area_idx';
	}
	
}
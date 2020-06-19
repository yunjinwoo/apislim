<?php

function getBannerProductFieldList(){
	return array(
		
				
				'product_0' => '전체' 
			,	'product_1' => '직장인' 
			,	'product_2' => '무직자' 
			,	'product_3' => '여성' 
			,	'product_4' => '개인돈' 
			,	'product_5' => '연체자' 
			,	'product_6' => '소액' 
			,	'product_7' => '무방문' 
			,	'product_8' => '일수' 
			,	'product_9' => '당일' 
			,	'product_10' => '사업자' 
			,	'product_11' => '월변' 
			,	'product_12' => '저신용' 
			,	'product_13' => '신용' 
			,	'product_14' => '추가' 
			,	'product_15' => '자동차' 
			,	'product_16' => '부동산' 
			,	'product_17' => '기타' 
	);
}
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
			,	'product_1' => '직장인' 
			,	'product_2' => '무직자' 
			,	'product_3' => '여성' 
			,	'product_4' => '개인돈' 
			,	'product_5' => '연체자' 
			,	'product_6' => '소액' 
			,	'product_7' => '무방문' 
			,	'product_8' => '일수' 
			,	'product_9' => '당일' 
			,	'product_10' => '사업자' 
			,	'product_11' => '월변' 
			,	'product_12' => '저신용' 
			,	'product_13' => '신용' 
			,	'product_14' => '추가' 
			,	'product_15' => '자동차' 
			,	'product_16' => '부동산' 
			,	'product_17' => '기타' 
			,	'product_18' => '전월세' 
			,	'product_19' => '보증금' 
			,	'product_20' => '회파복' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_date' => '등록일' 
			,	'update_date' => '수정일'
		);
		
		
		$this->table_log_field = $this->table_field;
		$this->table_log_field['product_log_idx'] = '베너로그 번호';
		$this->table_log_field['reg_info'] = '이력로그';
		$this->table_log_field['reg_admin_login_info'] = '등록한 로그인정보';
		unset($this->table_log_field['update_date']);
		
	}
	
	function getGoodCompanyCntList($datetime, $is_flag = 'Y' ){
		$qq = array();
		for( $i = 0 ; $i <= 17 ; $i++ ){
			$qq[] = "
				SELECT 'product_".$i."' as banner_title, COUNT(*) as cnt
				FROM ".$this->table." WHERE product_".$i." = :is_flag AND end_date >= :datetime
			";
		}
		
		if( !F::isDatetime($datetime) ){
			$datetime = date('Y-m-d H:i:s');
		}
		
		$stmt = $this->db()->prepare(implode(' UNION ', $qq));
		$stmt->bindValue(':is_flag' , $is_flag);
		$stmt->bindValue(':datetime' , $datetime);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[$a['banner_title']] =  $a['cnt'];
		}
		
		return $ret;
	}
	
	
	function getAreaToField($area){
		return array_search($area, $this->table_field);
	}
	
	function getPrimaryField(){
		return 'product_idx';
	}
	
	
}
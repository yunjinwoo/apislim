<?php

function getBannerAreaFieldList(){
	return array(
		
				'area_0' => '전국' 
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
	);
}
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
		
		$this->table_log_field = $this->table_field;
		$this->table_log_field['area_log_idx'] = '베너로그 번호';
		$this->table_log_field['reg_info'] = '이력로그';
		$this->table_log_field['reg_admin_login_info'] = '등록한 로그인정보';
		unset($this->table_log_field['update_date']);
	}
	
	function getGoodCompanyCntList($datetime, $is_flag = 'Y' ){
		$qq = array();
		for( $i = 0 ; $i <= 17 ; $i++ ){
			$qq[] = "
				SELECT 'area_".$i."' as banner_title, COUNT(*) as cnt
				FROM  ".$this->table."  WHERE area_".$i." = :is_flag AND end_date >= :datetime
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
		return 'area_idx';
	}
	
	function getRow($company_idx){
		$class = get_class($this);
		$_this = new $class();
		$row = $_this->getRowCommon('company_idx', $company_idx);
		
		return $row;
	}
	
	function _row_replace($row){
		$area_han = array();
		for( $i = 0 ; $i < 18 ; $i++ ){
			$f = 'area_'.$i;
			if( $row[$f] == 'Y' ){ 
				$area_han[] = $this->table_field[$f];
				$row['area_top'] = $this->table_field[$f];
			}
		}
		$row['area_han'] = $area_han;
		$row['area_top'] = A::str($row['area_han'], 0);
		
		if( $row['area_0'] == 'Y' ){
			$row['area_top'] = '전국';
		}elseif( $row['area_1'] == 'Y' ){
			$row['area_top'] = '서울';
		}elseif( $row['area_2'] == 'Y' || $row['area_3'] == 'Y' ){
			$row['area_top'] = '수도권';
		}
		
		return $row;
	}
	
}
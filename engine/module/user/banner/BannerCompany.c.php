<?php


function getBannerList(){
	return array(
		
		'premium' => '프리미엄'
	,	'sponsor' => '스폰서'
	,	'main_top' => '메인상단'
	,	'main_center' => '메인'
	);
}
class BannerCompany extends SaveCommon {
	protected $table = _db_banner_company_;
	protected $table_log = _db_banner_company_log_;
	protected $table_log_field;
			
	function __construct() {
		parent::__construct();
		
		
		$this->table_field = array(
				'banner_idx' => '베너 번호' 
			,	'company_idx' => '업체 번호' 
			,	'premium' => '프리미엄' 
			,	'sponsor' => '스폰서'
			
			,	'main_top' => '메인상단' 
			,	'main_center' => '메인' 
			,	'start_date' => '시작일' 
			,	'end_date' => '종료일' 
			,	'reg_date' => '등록일' 
			,	'update_date' => '수정일'
		);
		
		$this->table_log_field = $this->table_field;
		$this->table_log_field['reg_info'] = '이력로그';
		$this->table_log_field['reg_admin_login_info'] = '등록한 로그인정보';
		unset($this->table_log_field['update_date']);
	}
	
	function getGoodCompanyCntList($datetime, $is_flag = 'Y' ){
		$q = "
			SELECT 'premium' as banner_title, COUNT(*) as cnt
			FROM  ".$this->table."  WHERE premium = :is_flag AND end_date >= :datetime
			UNION

			SELECT 'sponsor' as banner_title, COUNT(*) as cnt
			FROM  ".$this->table."  WHERE sponsor = :is_flag AND end_date >= :datetime
			UNION

			SELECT 'main_top' as banner_title, COUNT(*) as cnt
			FROM  ".$this->table."  WHERE main_top = :is_flag AND end_date >= :datetime
			UNION

			SELECT 'main_center' as banner_title, COUNT(*) as cnt
			FROM  ".$this->table."  WHERE main_center = :is_flag AND end_date >= :datetime
		";
		
		if( !F::isDatetime($datetime) ){
			$datetime = date('Y-m-d H:i:s');
		}
		$stmt = $this->db()->prepare($q);
		$stmt->bindValue(':is_flag' , $is_flag);
		$stmt->bindValue(':datetime' , $datetime);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[$a['banner_title']] =  $a['cnt'];
		}
		
		return $ret;
	}
	
	function getGoodCompanyList($field, $datetime = ''){		
		$class = get_class($this);
		$_this = new $class();
		if( !F::isDatetime($datetime) ){
			$datetime = date('Y-m-d H:i:s');
		}
		
		$_this->addWhere($field, 'Y');
		$_this->addWhereStr('end_date', ' >= \''.$datetime.'\' ');
		
		$ret = $_this->getListCommon('company_idx');
		
		return $ret;
	}
	
	
	function getPrimaryField(){
		return 'banner_idx';
	}
	
	
	function getRow($company_idx){
		$class = get_class($this);
		$_this = new $class();
		return $_this->getRowCommon('company_idx', $company_idx);
	}
	
	
	function save($aSave){
		$class = get_class($this);
		$_this = new $class();
		$_this->_addData($aSave);
		
		//company_idx
		$company_idx = A::str( $aSave , 'company_idx');
		$row = $_this->getRow($company_idx);
		if( isset($row[$this->getPrimaryField()]) ){
			//수정
			$_this->addWhere('company_idx', $company_idx);
			$_this->saveCommon($_this->db());
			$company_banner_idx = $row[$this->getPrimaryField()];
		}else{
			$_this->addData('reg_date', date('Y-m-d H:i:s'));
			$company_banner_idx = $_this->saveCommon($_this->db());
		}

		return $company_banner_idx;
	}
	
	function saveLog($aSave){
		$_this = new SaveCommon($this->table_log);
		foreach( $this->table_log_field as $k => $v ){
			$_this->addData($k, A::str($aSave, $k));
		}
		
		//추가만 있다.
		$_this->addData('reg_admin_login_info', A::str($aSave, 'admin_login_info'));
		$_this->addData('reg_date', date('Y-m-d H:i:s'));
		$company_banner_idx = $_this->saveCommon($_this->db());

		return $company_banner_idx;
		
	}
}
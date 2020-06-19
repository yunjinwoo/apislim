<?php
class CarCompany extends SaveCommon {
	protected $table = 'car_company_data';
	protected $table_car_list = 'car_list';
	
	public $detail_list;
	public $min_price;
	public $max_price;
			
	function __construct() {
		parent::__construct($this->table);
		
		$this->min_price = 0;
		$this->max_price = 0;
		
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'car_seq' => '기본키'
		,	'country' => '국내/수입구분'
		,	'company' => '제조사'
		,	'car_name' => '차량명'
			
		,	'car_name_detail' => '세부모델명'
		,	'car_price' => '차량가'
			
		,	'car_sort' => '출력순서'
		
		,	'car_img' => '차량이미지'
			 
		,	'is_view' => '출력여부'		
		,	'reg_date' => '등록일'		
		,	'update_date' => '수정일'
		);
		
		$this->table_field_file['car_img'] = '차량이미지';
		
	}
	
	function getPrimaryField(){
		return 'car_seq';
	}
	
	function getListView()
	{
		$CarCompany = new CarCompany();
		$CarCompany->addWhereStr('car_price', ' >= 1 ');
		$CarCompany->addWhere('is_view', 'Y');
		$CarCompany->setOrder('country, company, car_sort desc, car_name ');
		return $CarCompany->getListCommon($this->getPrimaryField());
	}
	function getListViewJoin()
	{
		$q = ' 
			SELECT a.* FROM 
			'.$this->table.' a LEFT JOIN '.$this->table_car_list.' 
			ON a.country = b.country AND a.company = b.company
			WHERE a.car_price >= 1 AND a.is_view = \'Y\'
			ORDER BY country, company, car_sort desc, car_name ';
		
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[A::str($a, $this->getPrimaryField())] =  $this->_row_replace($a);
		}
		
		return $ret;
	}
	
	function getListAll()
	{
		return $this->getListCommon($this->getPrimaryField());
	}
	
	
	function getListCompany($company)
	{
		$CarCompany = new CarCompany();
		$CarCompany->addWhereStr('car_price', ' >= 1 ');
		$CarCompany->addWhere('is_view', 'Y');
		$CarCompany->addWhere('company', $company);
		$CarCompany->setOrder('country, company, car_sort');
		return $CarCompany->getListCommon($this->getPrimaryField());
	}
	
	
	function getDetailList($country = '', $company = '' , $car_name = ''){
		$CarCompany = new CarCompany;
		if( !empty($country) ){
			$CarCompany->addWhere('country', $country);
		}
		if( !empty($company) ){
			$CarCompany->addWhere('company', $company);
		}
		if( !empty($car_name) ){
			$CarCompany->addWhere('car_name', $car_name);
		}
		
		$this->detail_list = $CarCompany->getListCommon('car_seq');
		foreach( $this->detail_list as $k => $row ){
			if( empty($this->min_price) ){
				$this->min_price = $row['car_price'];
			}
			if( $this->min_price > $row['car_price'] ){
				$this->min_price = $row['car_price'];
			}
			if( empty($this->max_price) ){
				$this->max_price = $row['car_price'];
			}
			if( $this->max_price < $row['car_price'] ){
				$this->max_price = $row['car_price'];
			}
		}
		
		return $this->detail_list;
	}
	
	function getCounryList(){
		return $this->getGroupByField('country');		
	}
	function getCompanyList(){
		return $this->getGroupByField('company');		
	}
	function getCarNameList(){
		return $this->getGroupByField('car_name');
	}
	function getCarNameDetailList(){
		$a = $this->getListCommon();
		$ret = array();
		foreach($a as $k => $r ){
			$ret[$r['car_name_detail']] = $r['car_price'];
		}
		return $ret;
	}
}

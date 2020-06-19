<?php
load('car_company');

class CarOption extends SaveCommon {
	protected $table = 'car_option';
	private $car_seq;
	private $option_name;
	private $option_list;
	protected $CarRentCompany_items;
			
	function __construct($seq = '', $option_name = '') {
		parent::__construct($this->table);
		
		if( is_numeric($seq) ){
			$this->car_seq = $seq;
			$this->addWhere('car_seq', $seq);
		}
		$this->option_name = $option_name;			
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'option_seq' => '기본키'
		,	'car_seq' => '차키'
		,	'option_name' => '옵션이름'
		,	'option_key' => '옵션키'
		,	'option_value' => '옵션값'
			
		,	'option_sort' => '순서'	
		,	'reg_date' => '등록일'		
		,	'update_date' => '수정일'
		);
	}
	
	function getPrimaryField(){
		return 'option_seq';
	}
	
	
	function _row_replace($aRow){ 
		// 필요에 따라 수정해서...
		$aRow['rent'] = $this->getClassCarRentCompany($aRow['option_key']);
		if( count($aRow['rent']) < 1 ){
			return false;
		}
		return $aRow;
	}
	function getClassCarRentCompany($seq){
		if( !isset($this->CarRentCompany_items[$seq]) ){
			$CarRentCompany = new CarRentCompany;
			$this->CarRentCompany_items[$seq] = $CarRentCompany->getOne($seq);
		}		
		
		return $this->CarRentCompany_items[$seq];
	}
	
	function getOption($option_name='')//, $is_insert = false
	{
		if(empty($option_name)){
			$option_name = $this->option_name;
		}
		if( count($this->option_list) >= 1 ){
			return $this->option_list;
		}
		
		$this->addWhere('option_name', $option_name);
		//$this->setOrder(' option_sort desc');
		$this->setOrder(' option_value ');
		$this->option_list = $this->getListCommon('option_key');
		if( count($this->option_list) == 0 ){// 기본으로 넣고 등록된걸로 업데이트.... || count($this->option_list) == 0
			if( $option_name = 'car_rent' ){
				$CarRentCompany = new CarRentCompany();
				$CarRentCompany->addWhere('is_rent', 'Y');
				$CarRentCompany->setOrder('rent_per');
				foreach( $CarRentCompany->getListCommon() as $k => $row ){
					$a = array();
					$a['option_key'] = $k;
					$a['option_sort'] = 0;
					$a['option_value'] = $row['rent_per'];					
					$a['rent'] = $row;
					$this->option_list[$row['rent_seq']] = $a;
				}
				
			}elseif( $option_name = 'car_lease' ){
				$CarRentCompany = new CarRentCompany();
				$CarRentCompany->addWhere('is_lease', 'Y');
				$CarRentCompany->setOrder('rent_per');
				foreach( $CarRentCompany->getListCommon() as $k => $row ){
					$a = array();
					$a['option_key'] = $k;
					$a['option_sort'] = 0;
					$a['option_value'] = $row['rent_per'];					
					$a['rent'] = $row;
					$this->option_list[$row['rent_seq']] = $a;
				}
			}
		}		
		//$this->option_list = array_replace_recursive($this->option_list, $this->getListCommon('option_key'));//$this->getPrimaryField()
		
		//return $this->__option_sort($this->option_list);
		return $this->option_list;
	}
	
	function __option_sort($arr){
		$tmp = array();
		foreach( $arr as $k => $r ){
			for( $i = count($arr)-1 ; $i > $k ; $i-- ){
				if( !isset($arr[$i]['option_sort']) ){ break; }
				if( !isset($arr[$k]['option_sort']) ){ break; }
				if( $arr[$k]['option_sort'] < $arr[$i]['option_sort'] ){
					$tmp = $arr[$i];
					$arr[$i] = $arr[$k];
					$arr[$k] = $tmp;
				}				
			}
		}
		return $arr;
	}
	
	
	function deleteRow($option_seq){
		$CarOption = new CarOption();
		$CarOption->addWhere('option_seq', $option_seq);
		$CarOption->deleteCommon($CarOption->db());				
	}
	
	
	/**********************/
	/*** 이하 정렬 함수 ***/
	/**********************/
	function setOptionSort(){
		$list = $this->getOption();
		$cnt = count($list);
		foreach( $list as $k => $r ){
			$CarOptionSave = new CarOption();
			$CarOptionSave->addData('option_sort', $cnt--);
			$CarOptionSave->addWhere('option_seq', $k);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
	
	
	function upOptionSort($up_option_seq){
		$option_list = $this->getOption();
		
		$isBreak = false;
		$temp_seq = '';
		foreach( $option_list as $k => $r ){			
			if( $up_option_seq == $k ){
				$isBreak = true;
			}
			if( $isBreak ){
				break;
			}
			$temp_seq = $k;
		}
		if( isset($option_list[$temp_seq]) && isset($option_list[$up_option_seq]) ){
			$CarOptionSave = new CarOption();
			$CarOptionSave->addData('option_sort', $option_list[$temp_seq]['option_sort']);
			$CarOptionSave->addWhere('option_seq', $up_option_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
			
			$CarOptionSave = new CarOption();
			$CarOptionSave->addData('option_sort', $option_list[$up_option_seq]['option_sort']);
			$CarOptionSave->addWhere('option_seq', $temp_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
	
	function downOptionSort($down_option_seq){
		$option_list = $this->getOption();
		$isBreak = false;
		$temp_seq = '';
		foreach( $option_list as $k => $r ){
			if( $isBreak ){
				$temp_seq = $k;
				break;
			}
			if( $down_option_seq == $k ){
				$isBreak = true;
			}
		}
		if( isset($option_list[$temp_seq]) && isset($option_list[$down_option_seq]) ){
			$CarOptionSave = new CarOption();
			$CarOptionSave->addData('option_sort', $option_list[$temp_seq]['option_sort']);
			$CarOptionSave->addWhere('option_seq', $down_option_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
			
			$CarOptionSave = new CarOption();
			$CarOptionSave->addData('option_sort', $option_list[$down_option_seq]['option_sort']);
			$CarOptionSave->addWhere('option_seq', $temp_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
}

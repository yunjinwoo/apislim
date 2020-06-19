<?php
load('car_company');

class CarCompanyOption extends SaveCommon {
	protected $table = 'car_company_option';
	protected $items;
	private $car_name;
	private $option_group;
	private $option_list;
			  
	function __construct($car_name = '', $option_group = 'detail') {
		parent::__construct($this->table);
		$this->car_name = $car_name;
		$this->option_group = $option_group;
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'option_seq' => '기본키'
		,	'car_name' => '차이름'
		,	'option_group' => '옵션그룹'
		,	'option_name' => '옵션이름'
		,	'option_value' => '옵션값'
			
		,	'option_sort' => '순서'	
		,	'reg_date' => '등록일'		
		,	'update_date' => '수정일'
		);
	}
	
	function getPrimaryField(){
		return 'option_seq';
	}
	
	function getCarOption($car_name='')//, $is_insert = false
	{
		if( empty($car_name) ){
			$car_name = $this->car_name;
		}
		$car_name_trim = preg_replace('/[\s]/i','',$car_name);
		
		if( count($this->option_list[$car_name_trim]) >= 1 ){
			return $this->option_list[$car_name_trim];
		}
		
		$this->addWhere('car_name', $car_name);
		$this->addWhere('option_group', $this->option_group);
		//$this->setOrder(' option_sort desc');
		$this->setOrder(' option_sort desc ');
		$aaa =$this->getList2();
	//	pre($this->getList2());
		///pre($aaa);
		//$this->option_list[$car_name_trim] = $this->getList2();
		$this->option_list[$car_name_trim] = $aaa;
	//	pre($this->option_list);
		
	//	pre([$this->option_list,$car_name_trim]);
		return $this->option_list[$car_name_trim];
	}
	
	function update_option_name($target, $change){
		$C = new CarCompanyOption();
		$C->addData('car_name', $change);
		$C->addWhere('car_name', $target);
		$C->updateDefult();
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
		$CarCompanyOption = new CarCompanyOption();
		$CarCompanyOption->addWhere('option_seq', $option_seq);
		$CarCompanyOption->deleteCommon($CarCompanyOption->db());				
	}
	
	
	/**********************/
	/*** 이하 정렬 함수 ***/
	/**********************/
	function setOptionSort($car_name=''){
		$list = $this->getCarOption($car_name);
		$cnt = count($list);
	//	pre($list);
		foreach( $list as $k => $r ){
			$CarOptionSave = new CarCompanyOption();
			$CarOptionSave->addData('option_sort', $cnt--);
			$CarOptionSave->addWhere('option_seq', $r['option_seq']);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
	
	
	function upOptionSort($up_option_seq){
		$option_list = $this->getCarOption();
		
		$isBreak = false;
		$temp_seq = '';
		$up_option_row = $temp_option_row = [];
		foreach( $option_list as $k => $r ){			
			if( $up_option_seq == $r['option_seq'] ){
				$up_option_row = $r;
				$isBreak = true;
			}
			if( $isBreak ){
				break;
			}
			$temp_option_row = $r;
		}
		
		if( count($temp_option_row)>=1 && count($up_option_row) >= 1 ){
			$CarOptionSave = new CarCompanyOption();
			$CarOptionSave->addData('option_sort', $temp_option_row['option_sort']);
			$CarOptionSave->addWhere('option_seq', $up_option_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
			
			$CarOptionSave = new CarCompanyOption();
			$CarOptionSave->addData('option_sort', $up_option_row['option_sort']);
			$CarOptionSave->addWhere('option_seq', $temp_option_row['option_seq']);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
	
	function downOptionSort($down_option_seq){
		$option_list = $this->getCarOption();
		$isBreak = false;
		$temp_seq = '';
		$down_option_row = $temp_option_row = [];
		foreach( $option_list as $k => $r ){
			if( $isBreak ){
				$temp_option_row = $r;
				break;
			}
			if( $down_option_seq == $r['option_seq'] ){
				$down_option_row = $r;
				$isBreak = true;
			}
		}
		if( !$isBreak ){
			$temp_option_row = $r['option_seq'];
		}
		
		
		if( count($temp_option_row)>=1 && count($down_option_row) >= 1 ){
			$CarOptionSave = new CarCompanyOption();
			$CarOptionSave->addData('option_sort', $temp_option_row['option_sort']);
			$CarOptionSave->addWhere('option_seq', $down_option_seq);
			$CarOptionSave->saveCommon($CarOptionSave->db());
			
			$CarOptionSave = new CarCompanyOption();
			$CarOptionSave->addData('option_sort', $down_option_row['option_sort']);
			$CarOptionSave->addWhere('option_seq', $temp_option_row['option_seq']);
			$CarOptionSave->saveCommon($CarOptionSave->db());
		}
	}
	
	function copy_option($copy_option_name){
		if( $this->car_name != '' && $this->car_name != $copy_option_name ){
			$q = ' 
				INSERT INTO '.$this->table.' (car_name, option_name, option_value, reg_date)
				SELECT \''.$this->car_name.'\', option_name, option_value,\''.date('Y-m-d H:i:s').'\' FROM 
					'.$this->table.' WHERE car_name = \''.$copy_option_name.'\'
				';
			//pre($q);
			$this->db()->query($q);
		}
	}
}

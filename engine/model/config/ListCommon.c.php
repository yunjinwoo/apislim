<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Where
 *
 * @author Administrator
 */
class ListCommon extends PageVar{
        protected $sLeft;	
        protected $aWhere = array();
	protected $aAndOr = array();
	protected $sGroup;
	protected $sOrder;
	protected $sLimit;
		
	protected $bind_group = [];
	protected $bind_group_data = [];
	protected $bind_group_return = [];
	function bind_group(){
//		pre($this->bind_group_data);
//		pre($this->bind_group);
//		exit;
		foreach( $this->bind_group_data as $group_name => $data ){
			$_group = $this->bind_group[$group_name];
			//$obj = new $this->bind_class[$field]();
			$obj = new $_group[3]();
			$obj->addWhereStr($_group[2], ' IN (\''.implode('\',\'', array_keys($data)).'\') ');
			$a = $obj->getListCommon();
//pre([$obj->getListQuery(), $a, $obj]);
			if( $_group[1] == '1:1' ){//이쪽으로는 안오는게 맞는거 같네;;;
				foreach( $a as $k => $r ){
					$this->bind_group_return[$_group[0]] = $r;
				}
			}elseif( $_group[1] == '1:n' ){
				foreach( $a as $k => $r ){
					$this->bind_group_return[$_group[0]][$r[$_group[2]]][$k] = $r;
				}
			}else{//1:n
				foreach( $a as $k => $r ){
					$this->bind_group_return[$_group[0]][$r[$_group[2]]] = $r;
				}
			}
		}
		
		return $this;
	}
	function bind_group_return_list(){		
		return $this->bind_group_return;
	}
	function bind_group_find_rows($field, $key){
	//	pre($this->bind_group_return);
		if( isset($this->bind_group_return[$field][$key]) ){
			return $this->bind_group_return[$field][$key];
		}
		return [];
	}
	
	protected $bind_data = [];
	protected $bind_return = [];
	protected $bind_field = [];
	protected $bind_class = [];
	function bind(){
		//pre($this->bind_data );
		foreach( $this->bind_data as $field => $data ){
			$obj = new $this->bind_class[$field]();
			$obj->addWhereStr($field, ' IN (\''.implode('\',\'', array_keys($data)).'\') ');
			$a = $obj->getListCommon();
			//pre([$obj->getListQuery(), $a]);
			foreach( $a as $k => $r ){
				//pre([$r[$field],$k, $r]);
				$this->bind_return[$field][$r[$field]][$k] = $r;
		//		pre('##########');
		//		pre([$r[$field],$field, $r]);
				//pre([$this->bind_data[$field][$r[$field]], $this->bind_data[$field][$r[$field]][$k], $r]);
			}
		//	pre($this->bind_return[$field]);
		}
		//	pre('----------------------------------------------------');
		//$this->bind_data[$field][$arr[$field]] = $find_field;
		
		return $this;
	}
	function bind_return_list(){		
		return $this->bind_return;
	}
	function bind_find_rows($field, $key){
		if( isset($this->bind_return[$field][$key]) ){
			return $this->bind_return[$field][$key];
		}
		return [];
	}
	function bind_find_row($field, $key){
		if( isset($this->bind_return[$field][$key]) ){
			list( $k, $a) = each( $this->bind_return[$field][$key] );
			return $a;
		}
		return [];
	}
//	function bind_find_field($field, $key){
//		if( isset($this->bind_return[$field][$key]) ){
//			return $this->bind_return[$field][$key];
//		}
//		return [];
//	}
	
	
	function resetWhere(){
		$this->aWhere = array();
		
		return $this;
	}
	
	function slash_value($value)
	{
		return addslashes($value);
	}
	
	function addWhere($field, $value, $isInt = false)
	{
		// 클래스마다 바뀔 예정이다...
		switch ($field)
		{
			default: 
				if( $isInt ){
					$this->aWhere[$field] = ' '.$field.' = '.$this->slash_value($value).' ' ; 
				}else{
					$this->aWhere[$field] = ' '.$field.' = \''.$this->slash_value($value).'\'' ; 
				}
				break;
		}
		
		return $this;
	}
	
	function addWhereLike($field, $value)
	{
		$this->aWhere[$field] = ' '.$field.' LIKE \''.$this->slash_value($value).'\' ' ; 
		return $this;
	}
	
	function addWhereStr($field, $value)
	{
		$this->aWhere[$field] = ' '.$field.' '.$value.' ' ; 
		return $this;
	}
	function addWhereStrFull($field, $value)
	{
		$this->aWhere[$field] = ' '.$value.' ' ; 
		return $this;
	}
	
		
	function getWhere( $pix = ' WHERE ' )
	{
		if( count($this->aWhere) >= 1 ){
			$pix .= ' 1 ';
			foreach( $this->aWhere as $k => $v ){
				$pix .= A::str( $this->aAndOr, $k, ' AND ').' '.$v;
			}
			return $pix;
		}else{
			return '';
		}
		
	}
	function setAndOr( $field , $and_or )
	{
		if( isset($this->aWhere[$field]) ){
			$this->aAndOr[$field] = $and_or;
		}
	}
	
	function setWhereArr($aWhere){ if(is_array($aWhere)){ $this->aWhere = array_merge($this->aWhere, $aWhere) ; } }
	function getWhereArr(){ return $this->aWhere ; }
	
	function setGroup($sGroup){
		$this->sGroup = $sGroup;
	}
	
	function getGroup(){
		if(empty($this->sGroup)){
			return '';
		}else{
			return ' GROUP BY '.$this->sGroup;
		}		
	}
	
	function setOrder($sOrder){
		$this->sOrder = $sOrder;
		return $this;
	}
	
	function getOrder(){
		if(empty($this->sOrder)){
			return '';
		}else{
			return ' ORDER By '.$this->sOrder;
		}		
	}
	
	function setLimit($list_size=1, $page = ''){
		if( is_numeric($page) && is_numeric($list_size) ){
			$startNum = ($page-1)*$list_size ;
			$this->sLimit = $startNum.', '.$list_size;
		}else{
			$this->sLimit = $list_size;
		}
		
		return $this;
	}
	
	function setLimitStr($sLimit){
		$this->sLimit = str_replace('LIMIT ', '', $sLimit);
		return $this;
	}
	
	function getLimit(){
		if(empty($this->sLimit)){
			return '';
		}else{
			return ' LIMIT '.$this->sLimit;
		}		
	}
	
	function getCountField($field, $value = ''){
		if( $value != '' ){
			$this->addWhere($field, $value);
		}
		$q = '
			SELECT count('.$field.') as cnt 
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
			';
		$ret = array();
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchObject()->cnt ;
	}
	
	function getCountCommon(){
		$q = '
			SELECT count(*) as cnt 
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
			';
		$ret = array();
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchObject()->cnt ;
	}
	
	function getListQuery(){
		return '
			SELECT * 
			FROM '.$this->table.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
				'.$this->getOrder().'
				'.$this->getLimit().'
			';
	}
	
	function getPrimaryField(){
		return '';
	}
   
	     
   function getList2($step=''){
		$q = $this->getListQuery();
		$ret = array();
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		if( is_numeric($step) ){
			for( $i = 0; $i < $step ; $i++ ){
				$r = $stmt->fetch();
				$r = $this->_row_replace($r);
			}
			return $r;
		}
		while($a = $stmt->fetch()){
			$a = $this->_row_replace($a);
			if( $a != false ){
				$ret[] = $a;
			}
		}
		
		
		if( is_array($this->bind_field) ){
			foreach( $ret as $key => $arr ){
				foreach( $this->bind_field as $field => $find_field ){
					if( !empty($arr[$find_field[0]]) ){
						$this->bind_data[$field][$arr[$find_field[0]]] = $find_field[1];
					}
				}
			}
		}
		if( is_array($this->bind_group) ){
			foreach( $ret as $key => $arr ){
				foreach( $this->bind_group as $field => $group_data ){
					if( !empty($arr[$field]) ){
						$this->bind_group_data[$field][$arr[$field]] = $arr[$field];
					}
				}
			}
			
		}
		return $ret;
	}
	
   function getListCommon($idx_field=''){
		if( empty($idx_field) ){
			$idx_field = $this->getPrimaryField();
		}
		$q = $this->getListQuery();
		//pre($q);
		$ret = array();
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		while($a = $stmt->fetch()){
			$a = $this->_row_replace($a);
			if( $a != false ){
				$ret[A::str($a, $idx_field)] = $a;
			}
		}
		
		
		if( is_array($this->bind_field) && count($this->bind_field) >= 1 ){
			foreach( $ret as $key => $arr ){
				foreach( $this->bind_field as $field => $find_field ){
					if( !empty($arr[$find_field[0]]) ){
						$this->bind_data[$field][$arr[$find_field[0]]] = $find_field[1];
					}
				}
			}
		}
		
		//pre($this->bind_group );
		if( is_array($this->bind_group) && count($this->bind_group) >= 1 ){
			foreach( $ret as $key => $arr ){
				foreach( $this->bind_group as $field => $group_data ){
					//pre($arr[$field]);
					if( !empty($arr[$field]) ){
						$this->bind_group_data[$field][$arr[$field]] = $arr[$field];
					}
				}
			}
			//pre($this->bind_group_data);
		}
		
		return $ret;
	}
    function getListCommonOne($idx_field='',$idx = 0 ){
		$l = $this->getListCommon($idx_field);
		foreach( $l as $k => $r ){
			if( $idx == 0 ){
				return $r;
			}
			$idx--;
		}
	}
	
	function getRowCommon($idx_field, $table_idx){
		$class = get_class($this);
		$_this = new $class();
		$_this->addWhere($idx_field, $table_idx);
		$a = $_this->getListCommon($idx_field);
		
		return A::arr($a, $table_idx);
	}
	function getOne($table_idx, $idx_field = ''){
		if( empty($idx_field) ){
			$idx_field = $this->getPrimaryField();
		}
		$class = get_class($this);
		$_this = new $class();
		$_this->addWhere($idx_field, $table_idx);
		$a = $_this->getListCommon($idx_field);
		
		return A::arr($a, $table_idx);
	}
	function getOnePrev($table_idx, $idx_field = ''){
		if( empty($idx_field) ){
			$idx_field = $this->getPrimaryField();
		}
		$this->addWhereStr($idx_field, ' < '.$table_idx);
		$this->setOrder( $idx_field.' desc');
		$this->setLimit( 1 );
		return $this->getListCommonOne($idx_field,0);
	}
	function getOneNext($table_idx, $idx_field = ''){
		if( empty($idx_field) ){
			$idx_field = $this->getPrimaryField();
		}
		$this->addWhereStr($idx_field, ' > '.$table_idx);
		$this->setOrder( $idx_field );
		$this->setLimit( 1 );
		return $this->getListCommonOne($idx_field,0);
	}
	
	
	
	function getGroupByField($field){
		$q = ' SELECT '.$field.' , COUNT('.$field.') as cnt FROM '
				. $this->getTable()
				. $this->getWhere()
				.' GROUP BY '.$field
				.$this->getOrder()
				.$this->getLimit();
		
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[$a[$field]] =  $a['cnt'];
		}
		return $ret;
	}
	
	function db(){
		return db();
	}
}
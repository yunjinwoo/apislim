<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of memberOut
 *
 * @author Administrator
 */
class memberOut {
	
	public $list_size = 15 ;
	protected $listCnt = -1;
	protected $aWhere = array();
	protected $aKeyword = array();
	public $out_msg = array();
	
	function __construct() {
		$this->out_msg = Code::getCodeKV('out_msg');
	}
	
	function setSearch( $field , $keyword )
	{
		$this->aWhere[$field] = $field.' LIKE :'.$field ;
		$this->aKeyword[$field] = $keyword ;
	}
	
	function getWhere()
	{
		if( count($this->aWhere) >= 1 )
			$w = ' '.implode( ' AND ', $this->aWhere).' ' ;
		else $w = ' 1=1 ' ;
		
		
		$w = ' WHERE '.$w ;
		
		return $w ;
	}
	
	function limitDelete()
	{
		$this->aWhere['out_date'] = ' out_date <= \''.date('Y-m-d H:i:s', strtotime('-3 month')).'\' ';
		$q = ' 
			DELETE FROM '._db_member_out_.'
			'.$this->getWhere();
		$stmt = db()->prepare($q);
		
		stmtExecute($stmt);
	}
	function delete($out_idx)
	{
		$q = ' 
			DELETE FROM '._db_member_out_.'
			WHERE out_idx = :out_idx';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':out_idx', $out_idx, PDO::PARAM_INT);
		
		stmtExecute($stmt);
	}
	
	function getList($page=1)
	{
		$startNum = ($page-1)*$this->list_size ;
		
		$q = ' 
			SELECT *
			FROM '._db_member_out_.' 
			'.$this->getWhere().'
			ORDER BY out_idx desc
			LIMIT '.$startNum.','.$this->list_size  ;
		
		$stmt = db()->prepare($q) ;
		foreach( $this->aKeyword as $k => $v ){
			$stmt->bindValue(":$k", "%$v%");
		}
		
		stmtExecute($stmt);
		
		$ret = array();
		$no = $this->getCount() - $startNum ;
		while($r = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$r['no'] = $no-- ;
			$ret[$r['out_idx']] = $r ;
		}
		
		return $ret ;
	}
	
	
	function getCount()
	{
		if( $this->listCnt < 0 )
			$this->setCount () ;
			
		return $this->listCnt ;
	}
	
	private function setCount()
	{
		$q = ' SELECT COUNT(*) FROM '._db_member_out_.' '.$this->getWhere() ;
	
		$stmt = db()->prepare($q) ;
		foreach( $this->aKeyword as $k => $v ){
			$stmt->bindValue(":$k", "%$v%");
		}
		
		stmtExecute($stmt);
		$this->listCnt = $stmt->fetchColumn(0) ;
	}
	
	function out($user_id, $user_name, $member_type, $reg_date, $out_bit, $out_msg)
	{
		$q = '
			INSERT INTO '._db_member_out_.'
			SET
				`out_id`		= :user_id
			,	`out_name`		= :out_name
			,	`member_type`	= :member_type
			,	`reg_date`		= :reg_date
			,	`out_bit`		= :out_bit
			,	`out_msg`		= :out_msg
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id', $user_id);
		$stmt->bindValue(':out_name', $user_name);
		$stmt->bindValue(':member_type', $member_type);
		$stmt->bindValue(':reg_date', $reg_date);
		$stmt->bindValue(':out_bit', $out_bit);
		$stmt->bindValue(':out_msg', $out_msg);
		
		stmtExecute($stmt);
		
		$q = ' DELETE FROM '._db_member_.' WHERE user_id = :user_id ';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id', $user_id);
		
		stmtExecute($stmt);
	}
	
	
}

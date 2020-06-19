<?php

 /**
  * 회원정보
  * 
  * @version 1
  */
class memberList {
	public $list_size = 15 ;
	protected $listCnt = -1;
	protected $aWhere = array();
	protected $aKeyword = array();
	protected $orderby = ' user_idx DESC ';
	
	
	function setSearch( $field , $keyword )
	{
		switch( $field )
		{
			case 'reg_date': case 'birth':
				$this->aWhere[$field] = $field.' '.$keyword;
				break;
			default:
				$this->aWhere[$field] = $field.' LIKE :'.$field ;
				$this->aKeyword[$field] = $keyword ;
				break;
		}
	}
	
	function getWhere()
	{
		if( count($this->aWhere) >= 1 )
			$w = ' '.implode( ' AND ', $this->aWhere).' ' ;
		else $w = ' 1=1 ' ;
		
		
		$w = ' WHERE '.$w ;
		
		return $w ;
	}
	
	function setOrderby($orderby)
	{
		$this->orderby = $orderby ;
	}
	function getOrderby()
	{
		return $this->orderby;
	}
	function getList($page=1)
	{
		$startNum = ($page-1)*$this->list_size ;
		
		$q = ' 
			SELECT *
			FROM '._db_member_.' 
			'.$this->getWhere().'
			ORDER BY '.$this->getOrderby().'
			LIMIT '.$startNum.','.$this->list_size  ;
		
		console::log($q);
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
			$ret[$r['user_idx']] = $r ;
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
		$q = ' SELECT COUNT(*) FROM '._db_member_.' '.$this->getWhere() ;
	
		$stmt = db()->prepare($q) ;
		foreach( $this->aKeyword as $k => $v ){
			$stmt->bindValue(":$k", "%$v%");
		}
		
		stmtExecute($stmt);
		$this->listCnt = $stmt->fetchColumn(0) ;
	}
}


<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageMake
 *
 * @author Administrator
 */
class PageMake {
	protected $pageName ;
	protected $list;
	
	private $isFindList = false ;
	
	function __construct($pageName="") {
		$this->setPageName($pageName);
	}
	
	function setPageName($pageName)
	{
		$this->pageName = $pageName;
	}
	
	function getList($is_use = 'all')
	{
		if( $this->isFindList )
			return $this->list;
		
		$useWhere = '1' ;
		if( $is_use == 'Y' )
			$useWhere = ' AND is_use=\'Y\' ';
		else if( $is_use == 'N' )
			$useWhere = ' AND is_use=\'N\' ';
		
		$q = '
			SELECT * FROM '._db_page_make_.'
			WHERE '.$useWhere.'
			ORDER BY page_idx asc
				';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
				
		while($a = $stmt->fetch(PDO::FETCH_ASSOC))
			$this->list[$a['page_idx']] = $a;
		
		$this->isFindList = true;
		return $this->list ;
	}
	
	function getPageText($isUse = 'Y')
	{
		$row = $this->getPageRow($this->pageName, $isUse);		
		return $row['page_text'] ;
	}
	
	function setPageText($page_text)
	{
		$stmt = db()->prepare($this->_update());
		$stmt->bindValue(':page_text', $page_text);
		$stmt->bindValue(':page_name', $this->pageName);
		
		stmtExecute($stmt);
	}
	
	
	function getPageRow($pageName='', $isUse = 'Y')
	{
		if(empty($pageName))
			$pageName = $this->pageName ;
		
		$stmt = db()->prepare($this->_pageText($isUse));
		$stmt->bindValue(':page_name', $pageName);
		
		stmtExecute($stmt);
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	function _pageText($isUse)
	{
		$useWhere = '' ;
		if( $isUse == 'Y' )
			$useWhere = ' AND is_use=\'Y\' ';
		else if( $isUse == 'N' )
			$useWhere = ' AND is_use=\'N\' ';
		
		$q = '
			SELECT * FROM '._db_page_make_.'
			WHERE page_name = :page_name
		'.$useWhere;
		
		return $q; 
	}
	
	
	function _update()
	{
		$q = '
			UPDATE '._db_page_make_.'
			SET 
				page_text = :page_text
			WHERE page_name = :page_name
			AND is_use = \'Y\'
			' ;
		
		return $q; 
	}
}

?>

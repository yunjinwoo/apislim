<?php
/**
 * @author 윤진우
 */
class ItemList {
	protected $aData = array();
	protected $aWhere = array();
	protected $tmp = array();
	public $list_size = 10;
			
	function __construct() {
	}
	
	function setKeyword($keyword)
	{
		$this->aWhere['keyword'] = ' item_title LIKE \'%'.  addslashes($keyword).'%\' ';
	}
	
	function getCountCode1_2()
	{
		$q = '
			SELECT COUNT(*) as cnt FROM
			'._db_product_item_.'
			WHERE product_category_code = 1
			'.$this->getWhere(' AND ') ;
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchColumn();
	}
	
	function getCountCode3_4()
	{
		$q = '
			SELECT COUNT(*) as cnt FROM
			'._db_product_item_.' 
			WHERE product_category_code = 3
			'.$this->getWhere(' AND ') ;
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchColumn();
	}
	
	function getListCode1_2($page=1)
	{
		$startNum = ($page-1)*$this->list_size ;
		$no = $this->getCountCode1_2() - $startNum ;
		
		$ret = array();
			
		$q = '
			SELECT * FROM '._db_product_item_.'
			WHERE product_category_code = 1 '.$this->getWhere(' AND ').'
			ORDER BY regdate desc
			LIMIT '.$startNum.','.$this->list_size;
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch() )
		{
			$a['item_title']	= unserialize($a['item_title']);
			$a['item_summary']	= unserialize($a['item_summary']);
			$a['item_img_list_alt']	= unserialize($a['item_img_list_alt']);
			$a['item_img_view_alt']	= unserialize($a['item_img_view_alt']);
			$a['item_img_rel_alt']	= unserialize($a['item_img_rel_alt']);
			$a['item_feature']	= unserialize($a['item_feature']);
			$a['item_component'] = unserialize($a['item_component']);
			$a['item_use_way'] = unserialize($a['item_use_way']);
			$a['item_all_component'] = unserialize($a['item_all_component']);
			
			$a['no'] = $no-- ;
			$ret[$a['item_idx']] = $a;
		}
		
		return $ret;
	}
	
	function getListCode3_4($page)
	{
		$startNum = ($page-1)*$this->list_size ;
		$no = $this->getCountCode3_4() - $startNum ;
		
		$ret = array();
			
		$q = '
			SELECT * FROM '._db_product_item_.'
			WHERE product_category_code = 3  '.$this->getWhere(' AND ').'
			LIMIT '.$startNum.','.$this->list_size;
	
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch() )
		{
			$a['item_title']	= unserialize($a['item_title']);
			$a['item_summary']	= unserialize($a['item_summary']);
			$a['item_img_list_alt']	= unserialize($a['item_img_list_alt']);
			$a['item_img_view_alt']	= unserialize($a['item_img_view_alt']);
			$a['item_img_rel_alt']	= unserialize($a['item_img_rel_alt']);
			$a['item_feature']	= unserialize($a['item_feature']);
			$a['item_component'] = unserialize($a['item_component']);
			$a['item_use_way'] = unserialize($a['item_use_way']);
			$a['item_all_component'] = unserialize($a['item_all_component']);
			
			$a['no'] = $no-- ;
			$ret[$a['item_idx']] = $a;
		}
		
		return $ret;
	}
	
	
	function getAllList()
	{
		$q = '
			SELECT * FROM '._db_product_item_.'
			 '.$this->getWhere().'
			ORDER BY regdate desc';
	
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch() )
		{
			$a['item_title']	= unserialize($a['item_title']);
			$a['item_summary']	= unserialize($a['item_summary']);
			$a['item_img_list_alt']	= unserialize($a['item_img_list_alt']);
			$a['item_img_view_alt']	= unserialize($a['item_img_view_alt']);
			$a['item_img_rel_alt']	= unserialize($a['item_img_rel_alt']);
			$a['item_feature']	= unserialize($a['item_feature']);
			$a['item_component'] = unserialize($a['item_component']);
			$a['item_use_way'] = unserialize($a['item_use_way']);
			$a['item_all_component'] = unserialize($a['item_all_component']);
			
		//	$a['no'] = $no-- ;
			$ret[$a['item_idx']] = $a;
		}
		
		return $ret;
	}
	
	
	function setWhereCode_in($code1, $code2)
	{
		if( is_numeric($code1) ){
			$this->tmp['code1'][$code1] = $code1;
			$this->aWhere['item_category_code_1'] = ' item_category_code_1 IN ( '.implode(",", $this->tmp['code1']).' ) ';
		}
			
		if( is_numeric($code2) ){
			$this->tmp['code2'][$code2] = $code2;
			$this->aWhere['item_category_code_2'] = ' item_category_code_2 IN ( '.implode(",", $this->tmp['code2']).' ) ';
		}
	}
	
	function setItemCategoryCode_1($code)
	{
		if( is_numeric($code) )
			$this->aWhere['item_category_code_1'] = ' item_category_code_1 = '.$code;
	}
	
	function setItemCategoryCode_2($code)
	{
		if( is_numeric($code) )
			$this->aWhere['item_category_code_2'] = ' item_category_code_2 = '.$code;
	}
	
	function setUse_yn($yn)
	{
		if( $yn == 'Y' || $yn == 'N' )
			$this->aWhere['is_use'] = ' is_use = \''.$yn.'\'';
	}
	
	function getCount()
	{
		$q = '
			SELECT COUNT(*) as cnt FROM
			'._db_product_item_.'
			'.$this->getWhere().'
			';
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchColumn();
	}
	
	function getWhere( $def = ' WHERE ' )
	{
		$sWhere = '  ';
		if( count($this->aWhere) >= 1 )
			$sWhere = $def.implode(' AND ', $this->aWhere);
		return $sWhere;
	}
	
	function getList($page=1,$code1='',$code2='')
	{
		$this->setUse_yn('Y');
		if(is_numeric($code1)) $this->setItemCategoryCode_1 ($code1);
		if(is_numeric($code2)) $this->setItemCategoryCode_2 ($code2);
		
		
		$startNum = ($page-1)*$this->list_size ;
		$no = $this->getCount() - $startNum ;
		
		$ret = array();
			
		$q = '
			SELECT * FROM '._db_product_item_.'
			 '.$this->getWhere().'
			ORDER BY regdate desc
			LIMIT '.$startNum.','.$this->list_size;

		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch() )
		{
			$a['item_title']	= unserialize($a['item_title']);
			$a['item_summary']	= unserialize($a['item_summary']);
			$a['item_img_list_alt']	= unserialize($a['item_img_list_alt']);
			$a['item_img_view_alt']	= unserialize($a['item_img_view_alt']);
			$a['item_img_rel_alt']	= unserialize($a['item_img_rel_alt']);
			$a['item_feature']	= unserialize($a['item_feature']);
			$a['item_component'] = unserialize($a['item_component']);
			$a['item_use_way'] = unserialize($a['item_use_way']);
			$a['item_all_component'] = unserialize($a['item_all_component']);
			
			$a['no'] = $no-- ;
			$ret[$a['item_idx']] = $a;
		}
		
		return $ret;
	}
}

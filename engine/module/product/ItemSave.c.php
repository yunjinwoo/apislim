<?php
/**
 * @author 윤진우
 */
class ItemSave {
	protected $aData = array();
			
	function __construct($aPost) {
		$this->setData($aPost);
	}
	
	function setData($aPost)
	{
		$this->aData = $aPost;
	}
	
	function save($product_category_code)
	{
		$w = '';
		$nItemIdx = A::number($this->aData, 'item_idx',0);
		if(empty($nItemIdx) && $nItemIdx < 1)
		{
			$q = ' INSERT INTO '._db_product_item_.' ';
		}else{
			$q = ' UPDATE '._db_product_item_.' ';
			$w = ' WHERE item_idx = :item_idx ';
		}
		
		$f = '
				SET
					item_category_code_1	= :item_category_code_1
				,	item_category_code_2	= :item_category_code_2
				,	product_category_code	= :product_category_code
				
				,	item_title				= :item_title
				,	item_size				= :item_size
				,	item_summary			= :item_summary
				,	item_icon				= :item_icon
				
				,	item_img_list_path			= :item_img_list_path
				,	item_img_list_alt			= :item_img_list_alt
				,	item_img_view_path			= :item_img_view_path
				,	item_img_view_alt			= :item_img_view_alt
				,	item_img_rel_path			= :item_img_rel_path
				,	item_img_rel_alt			= :item_img_rel_alt
				
				,	is_use					= :is_use
				
				,	item_feature			= :item_feature
				,	item_component			= :item_component
				,	item_use_way			= :item_use_way
				,	item_all_component		= :item_all_component
				
				,	regdate	= :regdate
			' ;
		$q = $q.$f.$w;
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':item_category_code_1',A::str($this->aData, 'item_category_code_1'), PDO::PARAM_INT);
		$stmt->bindValue(':item_category_code_2',A::str($this->aData, 'item_category_code_2'), PDO::PARAM_INT);
		$stmt->bindValue(':product_category_code',$product_category_code, PDO::PARAM_INT);
		
		$stmt->bindValue(':item_title',			A::str($this->aData, 'item_title'));
		$stmt->bindValue(':item_size',			A::str($this->aData, 'item_size'));
		$stmt->bindValue(':item_summary',		A::str($this->aData, 'item_summary'));
		$stmt->bindValue(':item_icon',			A::str($this->aData, 'item_icon'));
		
		$stmt->bindValue(':item_img_list_path',		A::str($this->aData, 'item_img_list_path'));
		$stmt->bindValue(':item_img_list_alt',		A::str($this->aData, 'item_img_list_alt'));
		$stmt->bindValue(':item_img_view_path',		A::str($this->aData, 'item_img_view_path'));
		$stmt->bindValue(':item_img_view_alt',		A::str($this->aData, 'item_img_view_alt'));
		$stmt->bindValue(':item_img_rel_path',		A::str($this->aData, 'item_img_rel_path'));
		$stmt->bindValue(':item_img_rel_alt',		A::str($this->aData, 'item_img_rel_alt'));
		
		$stmt->bindValue(':is_use',				A::str($this->aData, 'is_use'));
		
		$stmt->bindValue(':item_feature',		A::str($this->aData, 'item_feature'));
		$stmt->bindValue(':item_component',		A::str($this->aData, 'item_component'));
		$stmt->bindValue(':item_use_way',		A::str($this->aData, 'item_use_way'));
		$stmt->bindValue(':item_all_component',	A::str($this->aData, 'item_all_component'));
		
		
		$stmt->bindValue(':regdate',	F::datetime(A::str($this->aData, 'regdate')) );
		if(!(empty($nItemIdx) && $nItemIdx < 1))
		{
			$stmt->bindValue(':item_idx',	$nItemIdx, PDO::PARAM_INT);
		}
		
		stmtExecute($stmt);
	}
}

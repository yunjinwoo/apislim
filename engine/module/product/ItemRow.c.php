<?php

class ItemRowTmp
{
	public $row ;
	function setRow($idx)
	{
		if(!is_numeric($idx))
			ErrorMsg::exitErrorFront (12);
		$q = '
			SELECT * FROM '._db_product_item_.'
			WHERE item_idx = :item_idx
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':item_idx', $idx, PDO::PARAM_INT);
		stmtExecute($stmt);
		
		$a = $stmt->fetch();
		
		
		if( !empty($a['item_img_list_alt']) ) $a['item_img_list_alt']			= unserialize($a['item_img_list_alt']);
		if( !empty($a['item_img_view_alt']) ) $a['item_img_view_alt']			= unserialize($a['item_img_view_alt']);	
		if( !empty($a['item_img_rel_alt']) ) $a['item_img_rel_alt']			= unserialize($a['item_img_rel_alt']);
		
		if( !empty($a['item_title']) ) $a['item_title']			= unserialize($a['item_title']);
		if( !empty($a['item_summary']) ) $a['item_summary']			= unserialize($a['item_summary']);
		if( !empty($a['item_feature']) ) $a['item_feature']			= unserialize($a['item_feature']);
		if( !empty($a['item_component']) ) $a['item_component']		= unserialize($a['item_component']);
		if( !empty($a['item_use_way']) ) $a['item_use_way']			= unserialize($a['item_use_way']);
		if( !empty($a['item_all_component']) ) $a['item_all_component']	= unserialize($a['item_all_component']);
	
		$this->row = $a;
	}
	
	function data($field, $sub = '', $subsub = '', $index = '')
	{
		$s = '';
		switch($field)
		{
			case 'idx' : case 'item_idx' : 
				$s = $this->row['item_idx']; 
				break;
			
			//case 'item_category_code_1' : break;
			//case 'item_category_code_2' : break;
			case 'item_title' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_title'][$sub];
							break;
					}
				break;
			case 'item_size' : 
				$s = $this->row['item_size'];
				break;
			case 'item_summary' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_summary'][$sub];
							break;
					}
				break;
			case 'item_icon' : 
					$s = $this->row['item_icon'];
				break;
			case 'item_img_list_path' : 
					$s = $this->row['item_img_list_path'];
				break;
			case 'item_img_view_path' : 
					$s = $this->row['item_img_view_path'];
				break;
			case 'item_img_rel_path' : 
					$s = $this->row['item_img_rel_path'];
				break;
			case 'item_img_list_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_img_list_alt'][$sub];
							break;
					}
				break;
			case 'item_img_view_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset($this->row['item_img_view_alt'][$sub])?$this->row['item_img_view_alt'][$sub]:'';
							break;
					}
				break;
			case 'item_img_rel_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset($this->row['item_img_rel_alt'][$sub])?$this->row['item_img_rel_alt'][$sub]:'';
							break;
					}
				break;
			case 'is_use' :
					$s = $this->row['is_use'] == "Y" ? "Y" : "N" ;
				break;
			
			case 'features_cnt' : 
				$c = count($this->row['item_feature']['title']['ko']);
				if( $c >= 1 ) $s = $c;
				else $s = 1;
				break;
			case 'component_cnt' :  
				$c = count($this->row['item_component']['title']['ko']);
				if( $c >= 1 ) $s = $c;
				else $s = 1;
				break;			
			case 'item_feature' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_feature'][$subsub][$sub][$index];
							break;
						
						case 'all' :
							$s = $this->row['item_feature'];
							break;
					}
				break;	
			case 'item_feature_img' : 
				if( isset($this->row['item_feature']['img_path'][$sub]) )
					$s = $this->row['item_feature']['img_path'][$sub];				
				break;
			case 'item_component' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_component'][$subsub][$sub][$index];
							break;
						case 'all' :
							$s = $this->row['item_component'];
							break;
					}
				break;
			case 'item_use_way' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_use_way'][$sub];
							break;
					}
				break;
			case 'item_all_component' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = $this->row['item_all_component'][$sub];
							break;
					}
				break;
			case 'icon_list' : 
					$s = explode(',', $this->row['item_icon']);
				break;
			
			case 'update_date' :
				$s = $this->row['update_date'];
				break;
			case 'regdate' :
				$s = $this->row['regdate'];
				break;
			default :
				$s = isset($this->row[$field])?$this->row[$field] : '';
				break;
			
		}
		
		return $s;
	}
}

class ItemRow
{
	static $row ;
	static function setRow($idx)
	{
		//console::$logCnt = 3 ;
		//console::log($idx);
		if(!is_numeric($idx))
			ErrorMsg::exitErrorFront (12);
		$q = '
			SELECT * FROM '._db_product_item_.'
			WHERE item_idx = :item_idx
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':item_idx', $idx, PDO::PARAM_INT);
		stmtExecute($stmt);
		
		$a = $stmt->fetch();
		
		
		if( !empty($a['item_img_list_alt']) ) $a['item_img_list_alt']			= unserialize($a['item_img_list_alt']);
		if( !empty($a['item_img_view_alt']) ) $a['item_img_view_alt']			= unserialize($a['item_img_view_alt']);	
		if( !empty($a['item_img_rel_alt']) ) $a['item_img_rel_alt']			= unserialize($a['item_img_rel_alt']);
		
		if( !empty($a['item_title']) ) $a['item_title']			= unserialize($a['item_title']);
		if( !empty($a['item_summary']) ) $a['item_summary']			= unserialize($a['item_summary']);
		if( !empty($a['item_feature']) ) $a['item_feature']			= unserialize($a['item_feature']);
		if( !empty($a['item_component']) ) $a['item_component']		= unserialize($a['item_component']);
		if( !empty($a['item_use_way']) ) $a['item_use_way']			= unserialize($a['item_use_way']);
		if( !empty($a['item_all_component']) ) $a['item_all_component']	= unserialize($a['item_all_component']);
	
		self::$row = $a;
	}
	
	static function data($field, $sub = '', $subsub = '', $index = '')
	{
		$s = '';
		switch($field)
		{
			case 'idx' : case 'item_idx' : 
				$s = self::$row['item_idx']; 
				break;
			
			//case 'item_category_code_1' : break;
			//case 'item_category_code_2' : break;
			case 'item_title' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = self::$row['item_title'][$sub];
							break;
					}
				break;
			case 'item_size' : 
				$s = self::$row['item_size'];
				break;
			case 'item_summary' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = self::$row['item_summary'][$sub];
							break;
					}
				break;
			case 'item_icon' : 
					$s = self::$row['item_icon'];
				break;
			case 'item_img_list_path' : 
					$s = self::$row['item_img_list_path'];
				break;
			case 'item_img_view_path' : 
					$s = self::$row['item_img_view_path'];
				break;
			case 'item_img_rel_path' : 
					$s = self::$row['item_img_rel_path'];
				break;
			case 'item_img_list_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = self::$row['item_img_list_alt'][$sub];
							break;
					}
				break;
			case 'item_img_view_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset(self::$row['item_img_view_alt'][$sub])?self::$row['item_img_view_alt'][$sub]:'';
							break;
					}
				break;
			case 'item_img_rel_alt' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset(self::$row['item_img_rel_alt'][$sub])?self::$row['item_img_rel_alt'][$sub]:'';
							break;
					}
				break;
			case 'is_use' :
					$s = self::$row['is_use'] == "Y" ? "Y" : "N" ;
				break;
			
			case 'features_cnt' : 
				$c = count(self::$row['item_feature']['title']['ko']);
				if( $c >= 1 ) $s = $c;
				else $s = 1;
				break;
			case 'component_cnt' :  
				$c = count(self::$row['item_component']['title']['ko']);
				if( $c >= 1 ) $s = $c;
				else $s = 1;
				break;			
			case 'item_feature' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset(self::$row['item_feature'][$subsub][$sub][$index])?self::$row['item_feature'][$subsub][$sub][$index]:'';
							break;
						
						case 'all' :
							$s = self::$row['item_feature'];
							break;
					}
				break;	
			case 'item_feature_img' : 
				if( isset(self::$row['item_feature']['img_path'][$sub]) )
					$s = self::$row['item_feature']['img_path'][$sub];				
				break;
			case 'item_component' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = isset(self::$row['item_component'][$subsub][$sub][$index])?self::$row['item_component'][$subsub][$sub][$index]:'';
							break;
						case 'all' :
							$s = self::$row['item_component'];
							break;
					}
				break;
			case 'item_use_way' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = self::$row['item_use_way'][$sub];
							break;
					}
				break;
			case 'item_all_component' : 
					switch($sub){
						default :
							$sub = 'ko';
						case 'ko' : case 'en' : case 'cn' : 
							$s = self::$row['item_all_component'][$sub];
							break;
					}
				break;
			case 'icon_list' : 
					$s = explode(',', self::$row['item_icon']);
				break;
			
			case 'update_date' :
				$s = self::$row['update_date'];
				break;
			case 'regdate' :
				$s = self::$row['regdate'];
				break;
			default :
				$s = isset(self::$row[$field])?self::$row[$field] : '';
				break;
			
		}
		
		return $s;
	}
	
	
	function delete()
	{
		$a = self::$row;
		
		$aImgField = array('item_img_list_path', 'item_img_view_path', 'item_img_rel_path');
		foreach( $aImgField as $v )
		{
			$path = $_SERVER['DOCUMENT_ROOT'].$a[$v];
			if( is_file($path) )
				unlink($path) ;
		}
		
		$list = $this->data('item_feature','all');
		foreach($list['title']['ko'] as $k => $v){
			 if( !empty($list['img_path'][$k]) ) {
				$path = $_SERVER['DOCUMENT_ROOT'].$list['img_path'][$k];
				if( is_file($path) )
					unlink($path) ;
			 }
		}
			
							
		
		$q = '
			DELETE FROM '._db_product_item_.'
			WHERE item_idx = :item_idx
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':item_idx', self::$row['item_idx'], PDO::PARAM_INT);
		stmtExecute($stmt);
		
	}
	
	
	
	
	function updateField( $field, $value, $item_idx , $is_number = false )
	{
		$q = 'UPDATE '._db_product_item_.'
			SET '.$field.' = :value 
			WHERE item_idx = :item_idx
			';
		
		$stmt = db()->prepare($q);
		if( $is_number ){
			$stmt->bindValue(':value', $value, PDO::PARAM_INT);
		}else{
			$stmt->bindValue(':value', $value);
		}
		
		$stmt->bindValue(':item_idx', $item_idx, PDO::PARAM_INT);		
		stmtExecute($stmt);
	}
}


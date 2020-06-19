<?php


class CarList extends SaveCommon
{
	protected $row;
	protected $limit;
	protected $Order;
	
	protected $table = 'car_list';
	protected $car_option_list;
	protected $car_name_detail;
			
	function getPrimaryField() {
		return 'car_seq';
	}
	
	function getCount($company, $country, $keyword = '', $is_all = ''){
		$w = $this->_getWhere($company, $country, $keyword, $is_all);
		$q = 'SELECT COUNT(*) as cnt FROM car_list  '.$w;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		$a = $stmt->fetch();
		return $a['cnt'];
	}
	
	
	
	function _getWhere($company = "" , $country = '',  $keyword = '', $is_view = ''){
		$w = array() ;
		$s = '';
		if( $company == 'korea_other' ){
			$s = ' company IN (\'samsung\', \'chevrolet\', \'ssangyong\') ';
			$this->addWhereStrFull('company', $s);
			$w[] = $s;
		}else if( $company == 'etc' ){
			$s = ' company NOT IN (\'bmw\',\'benz\',\'audi\',\'volks\') ';
			$this->addWhereStrFull('company', $s);
			$w[] = $s;
		}else if( !empty($company) ){
			$s = ' company IN (\''.addslashes($company).'\') ';
			$this->addWhereStrFull('company', $s);
			$w[] = $s;
		}

		if( !empty($country) ){
			$s = ' country IN (\''.addslashes($country).'\') ';
			$this->addWhereStrFull('country', $s);
			$w[] = $s;
		}
		if( !empty($keyword) ){
			$s = ' (car_name LIKE \'%'.addslashes($keyword).'%\' '
					. 'OR car_list_con LIKE \'%'.addslashes($keyword).'%\' '
					. 'OR car_list_con2 LIKE \'%'.addslashes($keyword).'%\' )';
			$this->addWhereStrFull('car_name', $s);
			$w[] = $s;
		}
		
		if( $is_view == 'Y' || $is_view == 'N' ){
			$s = ' is_view = \''.$is_view.'\' ';
			$this->addWhere('is_view', $is_view);
			$w[] = $s;
		}

		if( count($w) >= 1 )
			$w = ' WHERE '. implode( ' AND ', $w );
		else $w = '';
		
		return $w;
	}
	
	/**
	 * 에휴... 모가 이리 복잡해 18
	 */
	function _row_replace($a) {
		$a = __car__row($a);
		
		$a['car_name_detail'] = '';
		if( $a['country'] != '' && $a['country'] != '' && $a['country'] != '' ){
			$a['car_name_detail'] = $this->getClassCarCompany($a['country'],$a['company'],$a['car_name']);
		}
		
		$a['car_rent'] = $this->getClassCarOption($a['car_seq'], 'car_rent');
		$a['car_lease'] = $this->getClassCarOption($a['car_seq'], 'car_lease');
		$min_rent = 10000;
		$max_rent = 0;
		foreach( $a['car_rent'] as $k => $r ){
			if( $min_rent > $r['option_value'] ){
				$min_rent = $r['option_value'];
			}
			if( $max_rent < $r['option_value'] ){
				$max_rent = $r['option_value'];
			}
		}
		$min_lease = 10000;
		$max_lease = 0;
		foreach( $a['car_lease'] as $k => $r ){
			if( $min_lease > $r['option_value'] ){
				$min_lease = $r['option_value'];
			}
			if( $max_lease < $r['option_value'] ){
				$max_lease = $r['option_value'];
			}
		}

		$min_price = 1000000000000000 ;
		$max_price = 0 ;
		if( count($a['car_name_detail']) != 0 ){
			$min_price = 1000000000000000;
			$max_price = 0 ;
			foreach( $a['car_name_detail'] as $k => $r ){
				if( !empty($r['car_price']) && $min_price > $r['car_price'] ){
					$min_price = $r['car_price'];
				}
				if( !empty($r['car_price']) && $max_price < $r['car_price'] ){
					$max_price = $r['car_price'];
				}
			}
		}
		
		$a['min_price'] = $min_price;
		$a['min_rent'] = $min_rent;						
		$a['min_lease'] = $min_lease;
		
		$a['max_price'] = $max_price;
		$a['max_rent'] = $max_rent;
		$a['max_lease'] = $max_lease;
		
		
		if( $a['car_name_print'] == '' ){
			$a['car_name_print'] = $a['car_name'];
		}
		
		$t = $min_rent < $min_lease ? $min_rent : $min_lease ;
		//나중에 48개월 나눌수도 있을것 같아서....
		$a['min_price_print'] = $t  * $min_price;
		$a['min_rent_print'] = $min_rent  * $min_price;
		$a['min_lease_print'] = $min_lease  * $min_price;
		
		return $a;
	}
	function getList()
	{
		$q = $this->getListQuery();
		//pre($q);
		$stmt = db()->prepare($q);
		if( !empty($type) ){
		//	$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);
		}

		stmtExecute($stmt);

		$ret = array();
		while($a = $stmt->fetch())
		{
			$ret[] = $this->_row_replace($a);
		}
		
		return $ret;
	}

	function getClassCarOption($seq, $option_name){
		if( !isset($this->car_option_list[$seq][$option_name]) ){
			$CarOption = new CarOption($seq, $option_name);
			$this->car_option_list[$seq][$option_name] = $CarOption->getOption();
			
			return $this->car_option_list[$seq][$option_name];
		}
		return $this->car_option_list[$seq][$option_name];
	}
	function getClassCarCompany($country, $company, $car_name){
		if( !isset($this->car_name_detail[$country][$company][$car_name]) ){
			$CarCompany = new CarCompany ;
			if( $country != '' ){
				$CarCompany->addWhere('country', $country);
			}
			if( $company != '' ){
				$CarCompany->addWhere('company', $company);
			}
			if( $car_name != '' ){
				$CarCompany->addWhere('car_name', $car_name);
			}
			$CarCompany->setOrder('country, company, car_sort desc');
			$this->car_name_detail[$country][$company][$car_name] = $CarCompany->getListAll();
			
			return $this->car_name_detail[$country][$company][$car_name];
		}
		return $this->car_name_detail[$country][$company][$car_name];
	}
	
	
	function getMainList($field = '', $orderBy = '' ,$limit='')
	{
		
		switch ($field){
			case 'is_main' : case 'is_main_bottom' :
				break;
			default:
				$field = 'is_main';
		}
		
		$w = array() ;
		$w[] = ' '.$field.' = \'Y\' = is_hide = \'Y\' ';

		if( count($w) >= 1 )
			$w = ' WHERE '. implode( ' AND ', $w );
		else $w = '';

		if( is_numeric($limit) ){
			$limit = ' LIMIT '.$limit;
		}
		if(empty($orderBy)){
			$orderBy = ' ORDER BY update_date desc ';
		}
		$q = 'SELECT * FROM car_list'.$w.' '.$orderBy.' '.$limit;
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);

		$ret = array();
		while($a = $stmt->fetch())
		{
			$ret[] = __car__row($a);
		}
		return $ret;
	}
}

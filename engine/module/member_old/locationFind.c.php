<?php


class LocationFind
{
	protected $member_type;
	
	protected $lat;
	protected $lng;
	
	protected $code_nationality_bit;
	protected $code_hope_sitter_age_bit;
	protected $code_career_bit;
	protected $location_length;
	protected $code_marry_bit;
	
	protected $code_children_number_bit;
	protected $code_week_day_bit_sum;
	protected $code_work_type_bit;
	protected $code_pay_type_bit;
			
	function __construct($member_type) {
		$this->member_type = $member_type;
	}
	
	function setLoction($lat, $lng)
	{
		$this->lat = $lat ;
		$this->lng = $lng ;
	}
	function setNationalityBit	($code_nationality_bit)	{$this->code_nationality_bit = $code_nationality_bit;}
	function setSitterAgeBit	($code_hope_sitter_age_bit)	{$this->code_hope_sitter_age_bit = $code_hope_sitter_age_bit;}
	function setCareerBit		($code_career_bit)		{$this->code_career_bit = $code_career_bit;}
	function setLocationLength	($location_length)		{$this->location_length = $location_length;}
	function setMarryBit		($code_marry_bit)		{$this->code_marry_bit = $code_marry_bit;}
	
	function setWeekDayBitSum	($code_week_day_bit_sum){$this->code_week_day_bit_sum = $code_week_day_bit_sum;}
	function setWorkTypeBit		($code_work_type_bit)	{$this->code_work_type_bit = $code_work_type_bit;}
	function setPayTypeBit		($code_pay_type_bit)	{$this->code_pay_type_bit = $code_pay_type_bit;}
	function setChildrenBit		($code_children_number_bit)	{$this->code_children_number_bit = $code_children_number_bit;}
	
	
//	
//sitter_age : 
//code_hope_sitter_age_bit : 
	
	function find()
	{
		$w = array(1);
		$f = '';
		$order = '';
		if( is_double($this->lat) && is_double($this->lng) )
		{
			$f = '(
							6371 * acos(
										 cos(radians('.$this->lat.'))
										*cos(radians(lat))
										*cos(radians(lng)-radians('.$this->lng.'))
										+sin(radians('.$this->lat.'))*sin(radians(lat))
									)
				) as locationDiff , ' ;
			$order = ' ORDER BY locationDiff asc ';
			
			if( is_numeric($this->location_length) )
				$w[] = ' locationDiff < '.$this->location_length;
		}
		
		
		if(is_numeric($this->code_hope_sitter_age_bit)){
			//@jinwoo 쪼개서 검색해야한다.
			//$w[] = ' code_hope_sitter_age_bit & '.$this->code_hope_sitter_age_bit;
		}	
		
		if(is_numeric($this->code_nationality_bit))	$w[] = ' code_nationality_bit & '.$this->code_nationality_bit;
		if(is_numeric($this->code_career_bit))	$w[] = ' code_career_bit & '.$this->code_career_bit;
		// location_length lat, lng 와 같이
		if(is_numeric($this->code_marry_bit))	$w[] = ' code_marry_bit & '.$this->code_marry_bit;
		
		if(is_numeric($this->code_week_day_bit_sum))	$w[] = ' code_week_day_bit_sum & '.$this->code_week_day_bit_sum;
		if(is_numeric($this->code_work_type_bit))	$w[] = ' code_work_type_bit & '.$this->code_work_type_bit;
		if(is_numeric($this->code_pay_type_bit))	$w[] = ' code_pay_type_bit & '.$this->code_pay_type_bit;
		if(is_numeric($this->code_children_number_bit))	$w[] = ' code_children_number_bit & '.$this->code_children_number_bit;
		
		if( $this->member_type == 'M' ){
			$q = '
				SELECT '.$f.'* 
				FROM member_mother m LEFT JOIN member t
				ON m.member_member_idx = t.member_idx 
				where '.implode(' AND ', $w).$order;
		}else{
			$q = '
				SELECT '.$f.'* 
				FROM member_babysitter m LEFT JOIN member t
				ON m.member_member_idx = t.member_idx 
				where '.implode(' AND ', $w).$order;
		}
		
		console::log($q);
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
		$list = array();
		while($r = $stmt->fetch(PDO::FETCH_ASSOC)){
			$list[$r['member_idx']] = $r;
		}
		
		return $list;
	}
}
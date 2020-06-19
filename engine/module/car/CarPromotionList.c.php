<?php


class CarPromotion extends SaveCommon
{
	protected $row;
	protected $limit;
	protected $Order;
	
	protected $table = 'car_promotion_list';
	protected $car_option_list;
	protected $car_name_detail;
	
	function __construct() {
		// 일단 없는 테이블..
		$this->table_field = array(
			'promotion_seq' => '고유번호' 
		,	'car_img' => '차량이미지' 
		,	'is_promotion1' => '출력여부1' 
		,	'is_promotion2' => '출력여부2' 
		,	'is_promotion3' => '출력여부3' 
		,	'is_promotion4' => '출력여부4'
			
		,	'promotion_field1' => '입력필드1' 
		,	'promotion_field2' => '입력필드2' 
		,	'promotion_field3' => '입력필드3' 
		,	'promotion_field4' => '입력필드4' 
		,	'promotion_field5' => '입력필드5' 
		,	'promotion_field6' => '입력필드6' 
		,	'promotion_field7' => '입력필드7' 
		,	'promotion_field8' => '입력필드8' 
			
		,	'promotion_sort' => '정렬' 
		,	'reg_date' => '등록일' 
		,	'update_date' => '수정일' 
		);
		// 테이블 업로드필드
		$this->table_field_file = array(
			'car_img' => '차량이미지'
		);
	}
	
	function getPrimaryField() {
		return 'promotion_seq';
	}
	
}

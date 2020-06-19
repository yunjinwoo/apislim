<?php
class CarAuction extends SaveCommon {
	protected $table = 'car_auction';
	
	function __construct() {
		parent::__construct($this->table);
		
		
		// 테이블 필드
		$this->table_field = array(
			'auc_seq' => '기본키'
		,	'rent_name' => '렌트사이름'
		,	'rent_logo' => '렌트사로고'
		,	'rent_phone' => '대표전화'
		,	'rent_title' => '간단정보'
		,	'rent_info' => '상세정보'
		,	'rent_sort' => '출력순서'
		,	'is_view' => '출력여부'		
				
		,	'rent_per' => '기본수수료'
		,	'is_rent' => '기본렌트업테여부'
		,	'is_lease' => '기본리스업체여부'
					
			
		,	'editor_session_key' => '에디터키'
		,	'reg_date' => '등록일'		
		,	'update_date' => '수정일'
		);
		// 테이블 업로드필드
		$this->table_field_file = array(
			'rent_logo' => '렌트사로고'
		);
	}

	function getPrimaryField(){
		return 'auc_seq';
	}
	
	
}

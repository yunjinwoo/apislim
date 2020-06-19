<?php

class Notice extends BoardTotal {
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'notice');
		
		
		$this->table_field['print_site'] = '출력할 사이트';
	}
	
	static $cate_name = ['차량정보','신차혜택','이벤트','차고소식', '가이드','공지사항'];
	
	function _row_replace($r){
		//pre($r);
		$r = parent::_row_replace($r);
		$r['print_site_arr'] = explode(',', $r['print_site']);
		$r['print_site_arr_han'] = [];
		foreach($r['print_site_arr']  as $v ){
			$r['print_site_arr_han'][$v] = A::str(__get_board_print_site_list(), $v, $v);
		}
		//pre($r['print_site']);
		return $r;
	}
}
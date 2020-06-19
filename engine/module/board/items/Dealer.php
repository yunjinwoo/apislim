<?php

class Dealer extends BoardTotal {
	public $list_file = 'board_list_dealer.php';
	public $view_file = 'board_view_dealer.php';
	public $write_file = 'board_write_dealer.php';
	
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'dealer');
		
		//$this->table_field['answer_text'] = '답변';
		//$this->table_field['print_sort'] = '출력순서';
		$this->table_field['dealer_user_idx'] = '연결된딜러';
		
			
	}
}
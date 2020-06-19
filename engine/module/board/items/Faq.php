<?php

class Faq extends BoardTotal {
	
	public $list_file = 'board_list_faq.php';
	public $view_file = 'link_move';
	public $write_file = 'board_write_faq.php';
	
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'faq');
		
		$this->table_field['answer_text'] = '답변';
		$this->table_field['print_sort'] = '출력순서';
		
			
	}
}
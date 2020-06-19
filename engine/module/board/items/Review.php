<?php

class Review extends BoardTotal {
	public $list_file = 'board_list_review.php';
	public $view_file = 'board_view_review.php';
	public $write_file = 'board_write_review.php';
	
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'review');
		
		$this->table_field['answer_text'] = '답변';
		$this->table_field['dealer_form_cnt'] = '비교견적';
		$this->table_field['write_name'] = '작성자';
		
			 
	}
}
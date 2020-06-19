<?php

class Qna extends BoardTotal {
	public $list_file = 'board_list_qna.php';
	public $view_file = 'board_view_qna.php';
	public $write_file = 'board_write_qna.php';
	
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'qna');
		
		$this->table_field['board_phone1'] = '전화번호앞자리';
		$this->table_field['board_phone2'] = '전화번호중간자리';
		$this->table_field['board_phone3'] = '전화번호끝자리';
		
		$this->table_field['is_answer'] = '답변출력여부';
		$this->table_field['answer_text'] = '답변내용';
	}
	
	function updateAnswer($board_idx, $is_answer, $answer_text){
		$Qna = new Qna;
		$Qna->addWhere('board_idx', $board_idx);
		$Qna->addData('is_answer', $is_answer);
		$Qna->addData('answer_text', $answer_text);
		
		$Qna->updateCommon($Qna->db());
	}
}
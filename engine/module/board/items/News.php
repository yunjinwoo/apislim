<?php

class News extends BoardTotal {
	public $list_file = 'board_list_news.php';
	public $view_file = 'link_move';
	public $write_file = 'board_write_news.php';
	
	function __construct() {
		parent::__construct();
		$this->addWhere('board_name', 'news');
		
		$this->table_field['board_link'] = '게시글타사이트링크';
	}
}
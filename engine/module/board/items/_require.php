<?php
//require_once dirname(__FILE__).'/../_require.php';
load('board');

function getBoardLimit($board_name, $limit = 5){
	$Board = new BoardTotal;
	$Board->addWhere('board_name', $board_name);
	if(!is_numeric($limit)){
		$limit = 5;
	}
	$Board->setLimit($limit);
	
	return $Board->getList();
}
<?php
//require_once _PATH_lib_.'/page.f.php';


class UserCode{
	static $group = array(
		 '50' => '플래티넘'
		,'10' => '골드'
		,'5' => '베스트'
		,'1' => '일반'
	);
	
	
	static $area = array(
		 '1' => '전체'
		,'2' => '서울'
		,'4' => '경기'
		,'8' => '인천'
		
		,'16' => '전라·광주'
		,'32' => '강원·충청·대전'
		,'64' => '대구·경북'
		
		,'128' => '부산·울산·경남'
		,'512' => '제주도'
	);

	static function getGroupText($code){
		return isset(self::$group[$code])? self::$group[$code] : '';
	}
}
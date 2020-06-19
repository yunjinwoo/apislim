<?php

require_once dirname(__FILE__).'/MemberDealer.c.php';

define('_db_member_company_'		, _db_fix_.'member_company');
define('_db_member_company_log_'	, _db_fix_.'member_company_log');
define('_db_member_dealer_'		, _db_fix_.'member_dealer');
define('_db_member_dealer_log_'	, _db_fix_.'member_dealer_log');
define('_db_member_user_'			, _db_fix_.'member_user');


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
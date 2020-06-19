<?php
/**
CREATE TABLE `jin_user_item_code` (
	`user_item_code_idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` VARCHAR(45) NOT NULL,
	`item_code` VARCHAR(45) NOT NULL,
	`item_key` VARCHAR(45) NOT NULL,
	`item_value` VARCHAR(200) NOT NULL,
	`item_value2` VARCHAR(200) NOT NULL,
	`reg_date` DATETIME NOT NULL,
	PRIMARY KEY (`user_item_code_idx`),
	INDEX `admin_id` (`admin_id`),
	INDEX `item_code` (`item_code`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

 **/
define('_db_user_item_code_', _db_fix_.'user_item_code') ; 

/**
 * 코드로 저장된 데이타 가져오기 <br />
 * DB : my_vatech_code 테이블 이용<br />
 * 싱글톤 방법
 *
 * @author 윤진우
 */

$___code_user = null;
function getCodeUser(){
	global $___code_user;
	if( $___code_user === null ){
		$___code_user = new CodeUser;
		$___code_user->setUserid(member_row_view('user_id'));
	}
	
	return $___code_user;
}

class CodeUser extends SaveCommon {
	protected $table = _db_user_item_code_;
	private $user_id;
	
	function setUserid($user_id){
		$this->user_id = $user_id;
		return $this;
	}
	
	
	function deleteRow($item_code, $item_key){
		$CodeUser = new CodeUser;
		$CodeUser->addWhere('user_id', $this->user_id);
		$CodeUser->addWhere('item_code', $item_code);
		$CodeUser->addWhere('item_key', $item_key);
		
		$CodeUser->deleteCommon($this->db());
	}
	
	
	function find($item_code){
		$CodeUser = new CodeUser;
		$CodeUser->addWhere('user_id', $this->user_id);
		$CodeUser->addWhere('item_code', $item_code);
		
		return $CodeUser->getListCommon('item_key');
	}
	
	
	function insertRow($item_code, $item_key, $item_value, $item_value2){
		$CodeUser = new CodeUser;
		$CodeUser->addData('user_id', $this->user_id);
		$CodeUser->addData('item_code', $item_code);
		$CodeUser->addData('item_key', $item_key);
		$CodeUser->addData('item_value', $item_value);
		$CodeUser->addData('item_value2', $item_value2);
		
		$CodeUser->addData('reg_date', date('Y-m-d H:i:s'));		
		$CodeUser->insertCommon($this->db());
	}
	
	function updateRow($user_item_code_idx, $item_code, $item_key, $item_value, $item_value2){
		$CodeUser = new CodeUser;
		$CodeUser->addWhere('user_item_code_idx', $user_item_code_idx);
						
		$CodeUser->addData('user_id', $this->user_id);
		$CodeUser->addData('item_code', $item_code);
		$CodeUser->addData('item_key', $item_key);
		$CodeUser->addData('item_value', $item_value);
		$CodeUser->addData('item_value2', $item_value2);
			
		$CodeUser->updateCommon($this->db());
	}
}

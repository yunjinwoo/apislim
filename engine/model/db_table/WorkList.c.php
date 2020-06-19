<?php

/*
CREATE TABLE `jin_work_list` (
	`work_list_idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`admin_id` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '아이디',
	`work_title` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '제목',
	`work_title_detail` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '제목2',
	`date_str_start` DATE NULL DEFAULT NULL COMMENT '시작일',
	`date_str_end` DATE NULL DEFAULT NULL COMMENT '종료일',
	`week_start` TINYINT(4) NULL DEFAULT NULL COMMENT '시작주차',
	`week_end` TINYINT(4) NULL DEFAULT NULL COMMENT '끝주차',
	`reg_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
	`update_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`work_list_idx`),
	INDEX `admin_id` (`admin_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=18
;

 *
 * @author jinwoo
 */

class WorkList extends SaveCommon
{
	protected $table = 'jin_work_list';
	function __construct() {
		$this->table_field = array(
			'work_title' => '제목'
		,	'work_title_detail' => '제목2'
		,	'date_str_start' => '시작일'
		,	'date_str_end' => '종료일'
		,	'week_start' => '시작주차'
		,	'week_end' => '끝주차'
		);
	}
		
	function save($aSave){
		$WorkList = new WorkList;
		foreach( $this->table_field as $k => $v ){
			if( A::str($aSave, $k) != '' ){
				$WorkList->addData($k, A::str($aSave, $k));
			}
		}
		
		if( A::str($aSave, 'work_list_idx') == '' ){
			$WorkList->addData('admin_id' , MyInfo::getUserID());
			$WorkList->addData('reg_date' , date('Y-m-d H:i:s'));
			$idx = $WorkList->insertCommon($WorkList->db());
		}else{
			$idx = A::number($aSave, 'work_list_idx');
			$WorkList->addData('admin_id' , MyInfo::getUserID());
			$WorkList->addWhere('work_list_idx' , $idx);
			$WorkList->updateCommon($WorkList->db());
		}
		
		return $idx;
	}
	
	function delete($idx){
		$WorkList = new WorkList();
		$WorkList->addWhere('work_list_idx', $idx);
		$WorkList->deleteCommon($WorkList->db());
	}
}

<?php
/**
CREATE TABLE `jin_timer` (
	`timer_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`timer_category` VARCHAR(100) NOT NULL DEFAULT '',
	`timer_min` VARCHAR(50) NULL DEFAULT NULL,
	`timer_input` VARCHAR(100) NULL DEFAULT NULL,
	`date_str` date NULL DEFAULT '0000-00-00',
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`timer_idx`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1
;



 * @author 넌임마
 */
class DartFss extends db_table{
	protected $table = 'jin_dart_fss';
	
	function __construct() {
		parent::__construct($this->table);
		
		$this->setOrder(' dart_no desc');
		
		// 테이블 필드
		$this->table_field = array(
			'fss_idx' => '기본키'
			
		,	'dart_no' => 'dart key'
		,	'corp_code' => '회사코드'
		,	'corp_name' => '회사명'
		,	'corp_cls' => '시작소속'
			 
		,	'stock_code' => '종목코드'
		,	'dart_title' => '공시 제목'
		,	'dart_writer' => '공시 작성자'
			
		,	'dart_date' => '등록일'
		,	'dart_info' => '비고'
			 
		,	'push_msg' => '푸시메시지'
			 
		,	'reg_date' => '등록일 Y-m-d H:i:s'
		,	'mod_date' => '수정일 Y-m-d H:i:s'
		
		);
		
		// 테이블 업로드필드
		$this->table_field_file = array(
		);
	}

	function getPrimaryField(){
		return 'fss_idx';
	}
	
	function _row_replace($row) {
		$row['corp_cls_han'] = $row['corp_cls'];
		switch( $row['corp_cls']){
			case 'Y' : $row['corp_cls_han'] = '코스피'; break;
			case 'K' : $row['corp_cls_han'] = '코스닥'; break;
			case 'N' : $row['corp_cls_han'] = '코넥스'; break;
			case 'E' : $row['corp_cls_han'] = '기타'; break;
		}
		return $row;
	}
}



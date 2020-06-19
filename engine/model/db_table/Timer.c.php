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
class Timer extends db_table{
	protected $table = 'jin_timer';
	
	function __construct() {
		parent::__construct($this->table);
		

		// 테이블 필드
		$this->table_field = array(
			'timer_idx' => '기본키'
			
		,	'timer_category' => '제목'
		,	'timer_min' => '설명글'			
		,	'timer_input' => '타이틀이미지'
			
		,	'date_str' => '등록일 Y-m-d'
		,	'reg_date' => '등록일 Y-m-d H:i:s'
		);
		
		// 테이블 업로드필드
		$this->table_field_file = array(
		);
	}

	function getPrimaryField(){
		return 'timer_idx';
	}
	
	function getListByDateStr(){
		$aList = $this->getListCommon();
		$ret = [];
		$print_time='';
		foreach( $aList as $k => $r ){
			$date_str = $r['date_str'];
			unset($r['date_str']);
			if( $print_time == '' ){
				$r['time_diff'] = '';
			}else{
//				$r['time_diff_1'] = $r['reg_date'];
//				$r['time_diff_2'] = $print_time;
				$diff = ((strtotime($print_time) - strtotime($r['reg_date'])) / 60);
				if( $diff >= 60 ){
					$r['time_diff'] = floor($diff/60).'시 '.floor($diff%60).'분';
				}else{
					$r['time_diff'] = floor($diff).'분';
				}
			}
			
			$print_time = $r['reg_date'];
			$ret[$date_str][] = $r;
		}
		return $ret;
	}
	
}

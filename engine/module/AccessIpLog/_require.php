<?php


/*
CREATE TABLE `jin_admin_member` (
	`admin_idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '관리자 번호',
	`admin_group` VARCHAR(30) NOT NULL COMMENT '부서',
	`admin_id` VARCHAR(45) NOT NULL COMMENT '관리자 아이디',
	`admin_pw` VARCHAR(60) NOT NULL COMMENT '관리자 비밀번호',
	`admin_name` VARCHAR(45) NOT NULL COMMENT '관리자 이름',
	`admin_phone` VARCHAR(20) NULL DEFAULT NULL COMMENT '관리자 전화번호',
	`admin_level` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '관리자 레벨',
	`admin_owner` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT '관리자 권한 - 임시로 만듬',
	`reg_date` DATETIME NOT NULL COMMENT '등록일',
	`update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
	PRIMARY KEY (`admin_idx`),
	UNIQUE INDEX `admin_id_UNIQUE` (`admin_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=2;

 **/
define('_db_admin_', _db_fix_.'member_admin');

class adminCode{
	static $group = array(
		'call' => '상담원'
	//	,'team' => '팀장'
		,'dist' => '분배자'
		,'admin' => '관리자'
	);
	
	static function getGroupText($code){
		return isset(self::$group[$code])? self::$group[$code] : '';
	}
}

function admin_row_view($field, $sub = "")
{
	$Session = new Session;
	$user_id = $Session->getUserId();
	return admin_row_view_select($user_id, $field, $sub);
}

$adminRowList = null;
function admin_row_view_select($user_id, $field, $sub = "")
{
	global $adminRowList;
	if( $adminRowList == null || !isset($adminRowList[$user_id]) )
	{
		$adminRowList[$user_id] = new adminRow;
		$adminRowList[$user_id]->setRow($user_id);
	}
	return $adminRowList[$user_id]->data($field, $sub);
}


$adminFindList = null;
function admin_find_view_selecet( $team_seq = '' , $team_sub = '')
{
	global $adminFindList;
	if( is_numeric($team_seq) )
	{
		if( $adminFindList == null || !isset($adminFindList['team_seq'][$team_seq]) )
		{
			$tmp = new adminList;
			$tmp->addWhere('team_seq', $team_seq);
			$tmp->addWhere('level', 25);

			$a = $tmp->getTeamMemberList($team_seq);
			list(  , $aRow ) = each($a);
			
			$adminFindList['team_seq'][$team_seq] = $aRow;
		}
		
		if( empty($team_sub) ){
			return $adminFindList['team_seq'][$team_seq]->data('name');
		}else{
			if(is_object($adminFindList['team_seq'][$team_seq]) ){				
				return $adminFindList['team_seq'][$team_seq]->data($team_sub);
			}else{
				return '';
			}
		}
	}
}
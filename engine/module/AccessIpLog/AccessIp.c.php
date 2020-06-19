<?php

/**
 * 관리자 접속 허용 아이피 설정
 * 
CREATE TABLE `jin_access_ip` (
	`ip` VARCHAR(40) NOT NULL COMMENT '허용할 IP',
	`ip_info` VARCHAR(100) NULL DEFAULT NULL COMMENT 'IP 정보',
	`is_use` CHAR(1) NULL DEFAULT 'Y',
	`reg_date` DATETIME NULL DEFAULT NULL COMMENT '등록일',
	`update_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`ip`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

 * @version 1
 */
class AccessIp extends SaveCommon{
	
	function __construct() {
		$this->table = _db_access_ip_;
	}
	
	/**
	 * 관리자 접속 허용 아이피 저장
	 * 
	 * @param string $ip ip 주소
	 * @param string $ipinfo 추가정보
	 * @return int last_insert_id 값
	 */
	function insert($ip, $ip_info, $admin_id)
	{
		$aIp = $this->getRow($ip) ;
		if( count2($aIp) >= 1 ) return ;
		
		$AccessIp = new AccessIp;
		$AccessIp->addData('ip', $ip);
		$AccessIp->addData('ip_info', $ip_info);
		$AccessIp->addData('reg_admin_id', $admin_id);
		$AccessIp->addData('reg_date', date('Y-m-d H:i:s'));
		
		return $AccessIp->replaceCommon($this->db());
	}
	
	/**
	 * 관리자 접속 허용 아이피 저장
	 * 
	 * @param string $ip ip 주소
	 * @param string $ipinfo 추가정보
	 * @return int last_insert_id 값
	 */
	function update_use($ip, $is_use, $mod_admin_id)
	{
		$aIp = $this->getRow($ip) ;
		if( count2($aIp) < 1 ) return ;
		
		
		$AccessIp = new AccessIp;
		$AccessIp->addWhere('ip', $ip);
		$AccessIp->addData('is_use', $is_use);
		$AccessIp->addData('mod_admin_id', $mod_admin_id);
		$AccessIp->updateCommon($this->db());
	}
	
	/**
	 * 관리자 접속 허용 아이피 완전삭제
	 * 
	 * @param string $ip ip 주소
	 * @return int 삭제된 row 갯수
	 */
	function delete($ip)
	{
		$AccessIp = new AccessIp;
		$AccessIp->addWhere('ip', $ip);
		$AccessIp->deleteCommon($this->db());
	}
	
	
	/**
	 * 관리자 접속 허용 아이피 리스트
	 * 
	 * @return array fatchAll PDO::FETCH_ASSOC
	 */
	function getList()
	{
		$this->setOrder('is_use DESC, ip asc');
		
		return $this->getListCommon('ip');
	}
	
	/**
	 * 관리자 접속 허용 아이피 검색
	 * LIKE 검색 192.168.0.* 같은 형태도 가능하다
	 * 
	 * @param string $ip ip 주소
	 * @return bool true or false
	 */
	function isFind($ip)
	{
		$ipSection	= preg_replace("/([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})/", "\\1.\\2.\\3.%", $ip);
		
		$AccessIp = new AccessIp;
		$AccessIp->addWhere('is_use', 'Y');
		$AccessIp->addWhereLike('ip', $ipSection);
		
//		pre($ipSection);
//		pre($AccessIp->getListQuery());
//		exit;
		$aList = $AccessIp->getListCommon('ip');
		foreach( $aList as $ip => $a ){
			if( strpos( $a['ip'], '*' ) !== false ){
				return true ;
			}
			
			if( $a['ip'] == $ip ){
				return true ;
			}
		}
		
		return false;
	}
	
	function getRow($ip)
	{
		$AccessIp = new AccessIp;
		$AccessIp->addWhere('ip', $ip);
		
		return $AccessIp->getListCommonOne('ip');
	}
}

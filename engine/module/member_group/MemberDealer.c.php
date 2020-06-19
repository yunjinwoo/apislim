<?php

class MemberDealer extends SaveCommon {
	protected $table = _db_member_dealer_;
	
	
	static $group_level = array(
			'50' => '플래티넘'
		,	'10' => '골드'
		,	'5' => '베스트'
		,	'1' => '일반'
	);
	
	static $point = array(
			'view' => '고객확인' // CPV
		,	'admin' => '관리자변경'
		,	'pay' => '결제'
		,	'msg' => '문자'
	);
	
	function getPointTypeList(){
		return array(
			
		);
	}
	function savelog($dealer_idx){
		
		$Dealer = new MemberDealer;
		$a = $Dealer->addWhere('dealer_idx',$dealer_idx)->getListCommonOne();
				  
		$a['dealer_log_idx'] = $dealer_idx;
		$a['log_date'] = date('Y-m-d H:i:s');
		$DealerLog = new MemberDealerLog;
		$DealerLog->save($a);
	}
	function __construct() {
		parent::__construct();
		
		$this->table_field = array(
			'dealer_idx' => '업체 기본키'
		,	'dealer_group' => '업체 구분'
		,	'is_use' => '로그인허용여부'
			
		,	'user_point' => '포인트'			
		,	'user_point_is_lock' => '포인트잠금'
			
		,	'dealer_id' => '업체 아이디'				
		,	'dealer_pw' => '업체 비밀번호'
			
		,	'dealer_name' => '담당자이름'
		,	'dealer_phone' => '담당자연락처'
		,	'dealer_email' => '담당자이메일'
			
		,	'dealer_addr_code' => '업체우편번호'
		,	'dealer_addr_1' => '업체주소1'
		,	'dealer_addr_2' => '업체주소2'
			
		,	'dealer_hp' => '업체 연락처'
			 
		,	'dealer_info1' => '한줄'
		,	'dealer_info2' => '200자'
		,	'dealer_info3' => '상세설명'
			
		,	'service_mailing' => '메일링서비스'
		,	'service_sms' => 'sms서비스'
			
		,	'reg_ip' => '등록아이피'
		,	'reg_admin_login_info' => '등록아이디'
		,	'reg_date' => '등록일'
		,	'update_admin_login_info' => '수정아이디'
		,	'update_date' => '수정일'
			 
		,	'last_login_date' => '마지막접속일'
		,	'last_login_date' => '마지막제안일'
			 
		);
		$this->table_hash_field['dealer_pw'] = '비밀번호';
	}
	
	function getPrimaryField(){
		return 'dealer_idx';
	}
	
	function _row_replace($aRow){ 
		return $aRow;
	}
}
<?php

class UserFormGallery extends SaveCommon {
	protected $table = _db_member_user_;
	
	function __construct() {
		$this->setOrder('idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'user_idx' => '회원 기본키'
		,	'user_group' => '회원 구분'
		,	'user_id' => '회원 아이디'				
		,	'user_pw' => '회원 비밀번호'
			
		,	'user_name' => '회원이름'
		,	'user_phone' => '회원연락처'
		,	'user_email' => '회원이메일'
			
		,	'user_addr_code' => '회원우편번호'
		,	'user_addr_1' => '회원주소1'
		,	'user_addr_2' => '회원주소2'
			
		,	'user_phone_sub' => '회원연락처2'
			
				
		,	'read_company_name' => '업체명'			
		,	'company_info_product' => '대표상품소개'
		,	'company_info1' => '한줄'
		,	'company_info2' => '200자'
		,	'company_info3' => '상세설명'
			
		,	'service_mailing' => '메일링서비스'
		,	'service_sms' => 'sms서비스'
			
				
				
		,	'reg_admin_login_info' => '등록아이디'
		,	'reg_date' => '등록일'
		,	'update_admin_login_info' => '수정아이디'
		,	'update_date' => '수정일'

		);
		
		$this->table_hash_field = array(
			'user_pw' => '회원 비밀번호'
		);
		$this->table_encode_field = array(
			'user_phone' => '회원연락처'
		);
	}
}
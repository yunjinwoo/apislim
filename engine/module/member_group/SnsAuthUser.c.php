<?php

class SnsAuthUser extends SaveCommon {
	protected $table = 'jin_sns_auth_user';
	
	function getPrimaryField() {
		return 'user_idx';
	}
	function __construct() {
		$this->setOrder('user_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'user_idx' => '회원 기본키'
		,	'is_use' => 'Y'
		,	'is_dealer' => '딜러여부'
			
		,	'kaccount_email' => '카카오이메일'				
		,	'kaccount_email_verified' => '카카오필드'
		,	'kakao_id' => '카카오키'
		
		,	'profile_image' => '카카오프로필이미지'
		,	'nickname' => '카카오닉네임'
		,	'thumbnail_image' => '카카오프로필'
			 
		,	'reg_date' => '등록일'
		,	'update_date' => '수정일'
			 
		,	'gps_x' => 'gps x'
		,	'gps_y' => 'gps y'
		,	'fcm_token' => 'fcm token'
		,	'fcm_update_date' => 'token 수정일'
		
		);
		
	}
}
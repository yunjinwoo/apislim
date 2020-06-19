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
			 
		,	'is_dealer' => '딜러여부'
		,	'is_use' => '사용여부'
		,	'is_admin_login_use' => '딜러로그인'
			
		,	'kaccount_email' => '카카오이메일'				
		,	'kaccount_email_verified' => '카카오필드'
		,	'kakao_id' => '카카오키'
		
		,	'profile_image' => '카카오프로필이미지'
		,	'nickname' => '카카오닉네임'
		,	'thumbnail_image' => '카카오프로필'
		
		,	'dealer_email' => '카카오프로필'
			 
		,	'dealer_phone' => '카카오프로필'
		,	'dealer_company' => '카카오프로필'
		,	'dealer_sido' => '카카오프로필'
		,	'dealer_zipcode' => '카카오프로필'
		,	'dealer_addr' => '카카오프로필'
		,	'dealer_add2' => '카카오프로필'
		,	'dealer_img' => '카카오프로필'
		,	'dealer_info' => '카카오프로필'
			 
		,	'dealer_send_info' => '하고싶은말 - 기본 문구'
			  
			  
		,	'reg_date' => '등록일'
		,	'update_date' => '수정일'
			 
		,	'is_use' => 'Y'
		,	'user_type' => '가입구분'
		,	'user_name' => '딜러이름'
		,	'user_pw' => '비밀번호'
		,	'gps_x' => 'gps x'
		,	'gps_y' => 'gps y'
		,	'fcm_token' => 'fcm token'
		,	'fcm_update_date' => 'token 수정일'
		
		);
		
		$this->table_field_file['dealer_img'] = '차량이미지';
		
		$this->table_encode_field['user_pw'] = '비밀번호';
	}
	
	function _row_replace($aRow) {
		$aRow = parent::_row_replace($aRow);
		if(!empty($aRow['dealer_img'])){
			$aRow['dealer_img_path'] = $aRow['dealer_img'];
			$aRow['dealer_img'] = 'http://'._SITE_DOMAIN_CHAGO_.$aRow['dealer_img'];
		}
		
		if(empty($aRow['dealer_email'])){
			$aRow['dealer_email'] = $aRow['kaccount_email'];
		}
		if(empty($aRow['user_name'])){
			$aRow['user_name'] = $aRow['nickname'];
		}
		
		return $aRow;
	}
}
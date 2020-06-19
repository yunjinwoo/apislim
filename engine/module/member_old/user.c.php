<?php

 /**
  * 회원정보
  * 
  * @version 1
  */
class user {
	public $list_size = 15 ;
	protected $member_idx;
	protected $userid;
	protected $passwd;
	protected $agent;
	
	/**
	 * 
	 */
	function __construct() {
		;
	}	
	
	/**
	 * 회원 정보 저장
	 * 
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @return int last_insert_id 값
	 */
	function insert($row)
	{	
		$q = '
			INSERT INTO my_user
			SET 
				`member_type` = :member_type
			,	`is_use` = :is_use
			
			,	`user_id` = :user_id
			,	`user_name` = :user_name
			,	`passwd` = :passwd
			,	`email1` = :email1
			,	`email2` = :email2
			,	`phone_hand_ceil_1` = :phone_hand_ceil_1
			,	`phone_hand_ceil_2` = :phone_hand_ceil_2
			,	`phone_hand_ceil_3` = :phone_hand_ceil_3
			,	`address_code_ceil_1` = :address_code_ceil_1
			,	`address_code_ceil_2` = :address_code_ceil_2
			,	`address_text_1` = :address_text_1
			,	`address_text_2` = :address_text_2
			,	`reg_date` = :reg_date
			,	`update_date` = :update_date
			,	`push_phone_id` = :push_phone_id
			,	`push_google_id` = :push_google_id
			';
		$stmt = db()->prepare($q) ;
		
		$stmt ->bindValue(':member_type', $row['member_type']);
		$stmt ->bindValue(':is_use', $row['is_use']);
		$stmt ->bindValue(':user_id', $row['user_id']);
		$stmt ->bindValue(':user_name', $row['user_name']);
		$stmt ->bindValue(':passwd', $row['passwd']);
		$stmt ->bindValue(':email1', $row['email1']);
		$stmt ->bindValue(':email2', $row['email2']);
		$stmt ->bindValue(':phone_hand_ceil_1', $row['phone_hand_ceil_1']);
		$stmt ->bindValue(':phone_hand_ceil_2', $row['phone_hand_ceil_2']);
		$stmt ->bindValue(':phone_hand_ceil_3', $row['phone_hand_ceil_3']);
		$stmt ->bindValue(':address_code_ceil_1', $row['address_code_ceil_1']);
		$stmt ->bindValue(':address_code_ceil_2', $row['address_code_ceil_2']);
		$stmt ->bindValue(':address_text_1', $row['address_text_1']);
		$stmt ->bindValue(':address_text_2', $row['address_text_2']);
		$stmt ->bindValue(':reg_date', date('Y-m-d H:i:s'));
		$stmt ->bindValue(':update_date', date('Y-m-d H:i:s'));
		$stmt ->bindValue(':push_phone_id', $row['push_phone_id']);
		$stmt ->bindValue(':push_google_id', $row['push_google_id']);
		
		stmtExecute($stmt) ;
		
		$idx = db()->lastInsertId() ;
		
		if( $row['member_type'] == 'B'){			
			$q = 'INSERT INTO member_babysitter
				SET
					member_member_idx = :member_member_idx
				';
		}else{			
			$q = 'INSERT INTO member_mother
				SET
					member_member_idx = :member_member_idx
				';
		}
		$stmt2 = db()->prepare($q) ;
		$stmt2 ->bindValue(':member_member_idx', $idx);
		stmtExecute($stmt2) ;
		
		$token = $this->updateToken($row['user_id']);
		return $token;
	}
	
	function updateToken($userid)
	{
		$q = '
			UPDATE my_user 
			SET token = :token
			WHERE 
				user_id = :user_id
			';
		
		$str = crypt($userid.''.$_SERVER['HTTP_USER_AGENT'].microtime(true));
		$stmt = db()->prepare($q);
		$stmt->bindValue(':token', $str);
		$stmt->bindValue(':user_id', $userid);
		
		stmtExecute($stmt);
		
		return $str;
	}
	
	/**
	 * 관리자 접속 정보 수정
	 * 
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @return int last_insert_id 값
	 */
	function update_member( $userid, $token,
				$phone_hand_ceil_1 ,$phone_hand_ceil_2 ,$phone_hand_ceil_3
				,$is_use="Y"
				,$email1="",$email2=""
				,$address_code_ceil_1="" ,$address_code_ceil_2="" 
				,$address_text_1="" ,$address_text_2=""
				,$push_phone_id="" ,$push_google_id=""
			)
	{
		$enum_is_use = array('Y','N');
		if( !in_array($is_use, $enum_is_use) )
			$is_use = $enum_is_use[0];
		
		$row = $this->row_member($userid, $token);
		
		$q = '
			UPDATE my_user
			SET 
				`is_use` = :is_use
			,	`email1` = :email1
			,	`email2` = :email2
			,	`phone_hand_ceil_1` = :phone_hand_ceil_1
			,	`phone_hand_ceil_2` = :phone_hand_ceil_2
			,	`phone_hand_ceil_3` = :phone_hand_ceil_3
			,	`address_code_ceil_1` = :address_code_ceil_1
			,	`address_code_ceil_2` = :address_code_ceil_2
			,	`address_text_1` = :address_text_1
			,	`address_text_2` = :address_text_2
			,	`update_date` = :update_date
			
			WHERE member_idx = :member_idx
			';
		$stmt = db()->prepare($q) ;
		
		$stmt ->bindValue(':is_use', $is_use);
		$stmt ->bindValue(':email1', $email1);
		$stmt ->bindValue(':email2', $email2);
		$stmt ->bindValue(':phone_hand_ceil_1', $phone_hand_ceil_1);
		$stmt ->bindValue(':phone_hand_ceil_2', $phone_hand_ceil_2);
		$stmt ->bindValue(':phone_hand_ceil_3', $phone_hand_ceil_3);
		$stmt ->bindValue(':address_code_ceil_1', $address_code_ceil_1);
		$stmt ->bindValue(':address_code_ceil_2', $address_code_ceil_2);
		$stmt ->bindValue(':address_text_1', $address_text_1);
		$stmt ->bindValue(':address_text_2', $address_text_2);
		
		$stmt ->bindValue(':update_date', date('Y-m-d H:i:s'));
		
		$stmt ->bindValue(':member_idx', $row['member_idx']);
		
		stmtExecute($stmt) ;
		
	}
	
	
	function row_member($user_id)
	{
		$q = '
			SELECT * from my_user 
			where user_id = :user_id' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id', $user_id);
		stmtExecute($stmt);
		$a = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $a;
		
	}
	
	
	/**
	 * 관리자 접속 정보 수정
	 * 
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @return int last_insert_id 값
	 */
	function update_babysitter( $aPost , $member_member_idx)
	{		
		$q = '
			UPDATE member_babysitter
			SET 
				`code_nationality_bit` = :code_nationality_bit
			,	`sitter_age` = :sitter_age
			,	`code_career_bit` = :code_career_bit
			,	`code_marry_bit` = :code_marry_bit
			,	`code_children_number_bit` = :code_children_number_bit
			,	`code_work_type_bit` = :code_work_type_bit
			,	`code_week_day_bit_sum` = :code_week_day_bit_sum
			,	`code_work_time_bit_sum` = :code_work_time_bit_sum
			,	`code_pay_type_bit` = :code_pay_type_bit
			,	`update_date` = :update_date
			
			WHERE member_member_idx = :member_idx
			';
		$stmt = db()->prepare($q) ;
		
		$stmt ->bindValue(':code_nationality_bit', $aPost['code_nationality_bit']);
		$stmt ->bindValue(':sitter_age', $aPost['sitter_age']);
		$stmt ->bindValue(':code_career_bit', $aPost['code_career_bit']);
		$stmt ->bindValue(':code_marry_bit', $aPost['code_marry_bit']);
		$stmt ->bindValue(':code_children_number_bit', $aPost['code_children_number_bit']);
		$stmt ->bindValue(':code_work_type_bit', $aPost['code_work_type_bit']);
		$stmt ->bindValue(':code_week_day_bit_sum', $aPost['code_week_day_bit_sum']);
		$stmt ->bindValue(':code_work_time_bit_sum', $aPost['code_work_time_bit_sum']);
		$stmt ->bindValue(':code_pay_type_bit', $aPost['code_pay_type_bit']);
		
		$stmt ->bindValue(':update_date', date('Y-m-d H:i:s'));
		
		$stmt ->bindValue(':member_idx', $member_member_idx);
		
		stmtExecute($stmt) ;
		
	}
	
	/**
	 * 관리자 접속 정보 수정
	 * 
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @param array $ip admin_id, admin_pw, admin_name, admin_phone, admin_owner
	 * @return int last_insert_id 값
	 */
	function update_mother( $aPost , $member_member_idx)
	{		
		$q = '
			UPDATE member_mother
			SET 
				`code_sex_bit` = :code_sex_bit
			,	`code_baby_age_bit` = :code_baby_age_bit 
			,	`code_brother_type_bit` = :code_brother_type_bit
			,	`code_house_type_bit` = :code_house_type_bit
			,	`code_work_type_bit` = :code_work_type_bit
			,	`code_pay_type_bit` = :code_pay_type_bit
			,	`code_career_bit` = :code_career_bit
			,	`code_nationality_bit` = :code_nationality_bit
			,	`code_hope_sitter_age_bit` = :code_hope_sitter_age_bit
			,	`code_week_day_bit_sum` = :code_week_day_bit_sum
			,	`code_work_time_bit_sum` = :code_work_time_bit_sum
			,	`update_date` = :update_date
			
			WHERE member_member_idx = :member_idx
			';
		$stmt = db()->prepare($q) ;
		
		$stmt ->bindValue(':code_sex_bit', $aPost['code_sex_bit']);
		$stmt ->bindValue(':code_baby_age_bit', $aPost['code_baby_age_bit']);
		$stmt ->bindValue(':code_brother_type_bit', $aPost['code_brother_type_bit']);
		$stmt ->bindValue(':code_house_type_bit', $aPost['code_house_type_bit']);
		$stmt ->bindValue(':code_work_type_bit', $aPost['code_work_type_bit']);
		$stmt ->bindValue(':code_pay_type_bit', $aPost['code_pay_type_bit']);
		$stmt ->bindValue(':code_career_bit', $aPost['code_career_bit']);
		$stmt ->bindValue(':code_nationality_bit', $aPost['code_nationality_bit']);
		$stmt ->bindValue(':code_hope_sitter_age_bit', $aPost['code_hope_sitter_age_bit']);
		$stmt ->bindValue(':code_week_day_bit_sum', $aPost['code_week_day_bit_sum']);
		$stmt ->bindValue(':code_work_time_bit_sum', $aPost['code_work_time_bit_sum']);
		
		$stmt ->bindValue(':update_date', date('Y-m-d H:i:s'));
		$stmt ->bindValue(':member_idx', $member_member_idx);
		
		stmtExecute($stmt) ;
	}
	
	
	function update_member_location( $user_id, $address, $lat, $lng )
	{
		$q = '
			UPDATE member
			SET
				address = :address
			,	lat = :lat
			,	lng = :lng
			WHERE
				user_id = :user_id
		' ;
		$stmt = db()->prepare($q);
		$stmt ->bindValue(':address', $address);
		$stmt ->bindValue(':lat', $lat);
		$stmt ->bindValue(':lng', $lng);
		$stmt ->bindValue(':user_id', $user_id);
		
		stmtExecute($stmt);
		
	}
	
	function login_auto($user_id, $token)
	{
		$row = $this->row_member($user_id);
		if( $row['token'] == $token ){
			return true;
		}else{
			return false;
		}
	}
	function login($user_id, $user_passwd)
	{
		$row = $this->row_member($user_id);
		if( $row['passwd'] == $user_passwd )
			return true;
		else
			return false;
		
	}
}


<?php

 /**
  * 회원정보
  * 
  * @version 1
  */
class member {
	public $list_size = 15 ;
	protected $member_idx;
	protected $userid;
	protected $passwd;
	protected $agent;
	
	
	function updateToken($userid)
	{
		$q = '
			UPDATE member 
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


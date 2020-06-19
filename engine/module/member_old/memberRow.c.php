<?php

class memberRow {
	protected $row;
	
	function __construct($row=array()) {
		if(count($row) >= 1)
		{
			
		}
	}
	
	function passwd_update($user_id, $pass_pw)
	{
		
		$q = '
			UPDATE '._db_member_.'
			SET
				user_pw = :user_pw
			where user_id = :user_id ' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id' , $user_id);
		$stmt->bindValue(':user_pw' , $pass_pw);
		
		stmtExecute($stmt);
		return $stmt->rowCount();
	}
	
	function setRow($user_id = '' , $user_idx = '')
	{
		$w = '';
		if( !empty($user_id) ) $w = ' WHERE user_id = :user_id ';
		if( is_numeric($user_idx) )$w = ' WHERE user_idx = :user_idx ';
		
		if( empty($w) ) return ;
		
		$q = '
			SELECT * FROM '._db_member_.'
			' . $w ;
		$stmt = db()->prepare($q);
		
		if( !empty($user_id) )
			$stmt->bindValue (':user_id', $user_id);
		if( is_numeric($user_idx) )
			$stmt->bindValue (':user_idx', $user_idx, PDO::PARAM_INT);
		
		stmtExecute($stmt);
		
		$this->row = $stmt->fetch();
		
	}
	
	function data($field, $sub = '')
	{
		$s = '';
		switch ($field)
		{
			case 'email' :
				$s = $this->row[$field] ; 
				$t = explode('@', $this->row[$field]);
				if(is_numeric($sub))
					$s = isset($t[$sub]) ? $t[$sub] : '' ;
				break;
			case 'hp_phone' : case 'home_phone' : case 'zip_code' : case 'job_num' : case 'job_phone':
				$t = explode('-', $this->row[$field]);
				if(is_numeric($sub))
					$s = isset($t[$sub]) ? $t[$sub] : '' ;
				else $s = $this->row[$field];
				break;
			case 'member_addr' : 
				$s = $this->row['zip_code'].' '.$this->row['address_1'].' '.$this->row['address_2'] ;
				break;
			case 'member_age' : 
				$t = explode('-', $this->row['birth']);
				$s = date('Y') - $t[0] ;
				break;
			case 'member_type.han' : 
				$s = $this->row['member_type'] == 'user' ? '일반회원' : '전문가회원';
				break;
			case 'birth' : 
				$t = explode('-', $this->row['birth']);
				if( $sub == 'year'){
					$s = $t[0];
				}else if( $sub == 'month' ){
					$s = $t[1];						
				}else if( $sub == 'day' ){
					$s = $t[2];						
				}else{
					$s = $this->row['birth'];
				}
				break;
			default : 
				$s = isset($this->row[$field]) ? $this->row[$field] : '';
		}
		return $s;
	}
	
	private function postToArr($aPost)
	{
		$aPost['birth'] = G::post('year').'-'.G::post('month').'-'.G::post('day');
		$aPost['zip_code'] = G::post('zip_code_1').'-'.G::post('zip_code_2');
		$aPost['home_phone'] = G::post('phone_1').'-'.G::post('phone_2').'-'.G::post('phone_3');
		$aPost['email'] = G::post('email_1').'@'.G::post('email_2');
		
		
		$aPost['job_phone'] = G::post('job_phone_1').'-'.G::post('job_phone_2').'-'.G::post('job_phone_3');
		$aPost['job_num'] = G::post('job_num_1').'-'.G::post('job_num_2').'-'.G::post('job_num_3');
		
		return $aPost;
	}
	
	function user_insert($aPost)
	{
		$aPost = $this->postToArr($aPost);
		
		$Session = new Session();
		$type = $Session->getJoinType();
		$gender = $Session->getJoinUserGender();
		$name = $Session->getJoinUserName();
		$birth = $Session->getJoinBirth();
		switch( $type )
		{
			case 'user' : break;
			case 'job' : break;
			default: 
				ErrorMsg::exitErrorFront(14);
				break;
		}
		
		$aPost['birth'] = $birth;
		$aPost['type'] = $type;
		$aPost['gender'] = $gender;
		$aPost['name'] = $name;
		$aPost['is_job_use'] = 'N';
		$aPost['reg_date'] = date('Y-m-d H:i:s');
		
		$this->_insert($aPost);
		
	}
	
	function _insert($aPost)
	{
		$q = '
			INSERT INTO '._db_member_.'
			SET
				member_type = :member_type
			,	user_name = :user_name
			,	user_gender = :user_gender
			,	user_id = :user_id
			,	user_pw = :user_pw
			,	birth_type = :birth_type
			,	birth = :birth
			,	home_phone = :home_phone
			,	hp_phone = :hp_phone
			,	email = :email
			,	is_sms = :is_sms
			,	is_news = :is_news
			,	zip_code = :zip_code
			,	address_1 = :address_1
			,	address_2 = :address_2
			
			,	job_name = :job_name
			,	job_phone = :job_phone
			,	job_num = :job_num
			
			,	is_job_use = :is_job_use
			
			,	reg_date = :reg_date
			';
		
		$stmt = db()->prepare($q);
		
		$stmt->bindValue(':member_type',A::str($aPost, 'type'));
		$stmt->bindValue(':user_gender',A::str($aPost, 'gender'));
		$stmt->bindValue(':user_name',	A::str($aPost, 'name'));
		$stmt->bindValue(':user_id',	A::str($aPost, 'user_id'));
		$stmt->bindValue(':user_pw',	A::str($aPost, 'user_pw'));
		$stmt->bindValue(':birth_type',	A::str($aPost, 'birth_type'), PDO::PARAM_INT);
		$stmt->bindValue(':birth',		A::str($aPost, 'birth'));
		$stmt->bindValue(':home_phone',	A::str($aPost, 'home_phone'));
		$stmt->bindValue(':hp_phone',	A::str($aPost, 'hp_phone'));
		$stmt->bindValue(':email',		A::str($aPost, 'email'));
		$stmt->bindValue(':is_sms',		F::YN(A::str($aPost, 'is_sms'),'N'));
		$stmt->bindValue(':is_news',	F::YN(A::str($aPost, 'is_news'),'N'));
		$stmt->bindValue(':zip_code',	A::str($aPost, 'zip_code'));
		$stmt->bindValue(':address_1',	A::str($aPost, 'address_1'));
		$stmt->bindValue(':address_2',	A::str($aPost, 'address_2'));
		
		$stmt->bindValue(':job_name',	A::str($aPost, 'job_name'));
		$stmt->bindValue(':job_phone',	A::str($aPost, 'job_phone'));
		$stmt->bindValue(':job_num',	A::str($aPost, 'job_num'));
		
		$stmt->bindValue(':is_job_use',	F::YN(A::str($aPost, 'is_job_use'),'N'));
		
		$stmt->bindValue(':reg_date',	F::datetime(A::str($aPost, 'reg_date')));
		
		stmtExecute($stmt);
	}
	
	function user_field_update($user_idx, $field, $data){
		$q = '
			UPDATE '._db_member_.'
			SET	
				'.$field.' = \''.addslashes($data).'\' 
			WHERE user_idx = '.F::number($user_idx,0);
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
	}
	
	function user_update($aPost)
	{
		$aPost = $this->postToArr($aPost);
		$aPost['hp_phone'] = G::post('hp_phone1').'-'.G::post('hp_phone2').'-'.G::post('hp_phone3');
		
		$new_pw = $q_pw = '';
		if( G::post('user_new_pw') == G::post('user_pw_repeat') ){
			$new_pw = G::post('user_new_pw');
			if( !empty($new_pw) )
			{
				$q_pw = ' , user_pw = :user_pw ';
			}
		}
		$q = '
			UPDATE '._db_member_.'
			SET				
				birth_type = :birth_type
			,	home_phone = :home_phone
			,	hp_phone = :hp_phone
			,	email = :email
			,	is_sms = :is_sms
			,	is_news = :is_news
			,	zip_code = :zip_code
			,	address_1 = :address_1
			,	address_2 = :address_2
			
			,	job_name = :job_name
			,	job_phone = :job_phone
			,	job_num = :job_num
			'.$q_pw.'
			WHERE 
				user_id = :user_id
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id',	member_row_view('user_id'));
		$stmt->bindValue(':birth_type',	A::str($aPost, 'birth_type'), PDO::PARAM_INT);
		$stmt->bindValue(':home_phone',	A::str($aPost, 'home_phone'));
		$stmt->bindValue(':hp_phone',	A::str($aPost, 'hp_phone'));
		$stmt->bindValue(':email',		A::str($aPost, 'email'));
		$stmt->bindValue(':is_sms',		F::YN(A::str($aPost, 'is_sms'),'N'));
		$stmt->bindValue(':is_news',	F::YN(A::str($aPost, 'is_news'),'N'));
		$stmt->bindValue(':zip_code',	A::str($aPost, 'zip_code'));
		$stmt->bindValue(':address_1',	A::str($aPost, 'address_1'));
		$stmt->bindValue(':address_2',	A::str($aPost, 'address_2'));
		
		$stmt->bindValue(':job_name',	A::str($aPost, 'job_name'));
		$stmt->bindValue(':job_phone',	A::str($aPost, 'job_phone'));
		$stmt->bindValue(':job_num',	A::str($aPost, 'job_num'));
		
		if( !empty($new_pw) )
		{
			$stmt->bindValue(':user_pw',	$new_pw);
		}
		
		stmtExecute($stmt);
	}
	
	
	function job_update($aPost)
	{
		$q = '
			UPDATE '._db_member_.'
			SET	
				job_name = :job_name
			,	job_phone = :job_phone
			,	job_num = :job_num
			,	is_job_use = :is_job_use
			WHERE 
				user_id = :user_id
			';
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':user_id',	A::str($aPost, 'user_id'));
		$stmt->bindValue(':job_name',	A::str($aPost, 'job_name'));
		$stmt->bindValue(':job_phone',	A::str($aPost, 'job_phone'));
		$stmt->bindValue(':job_num',	A::str($aPost, 'job_num'));
		$stmt->bindValue(':is_job_use',	A::str($aPost, 'is_job_use'));
		
		stmtExecute($stmt);
	}
}

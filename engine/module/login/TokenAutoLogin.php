<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TokenAutoLogin
 *
 * @author Administrator
 */
class TokenAutoLogin {
	//put your code here
	
	function login($token)
	{
		$arr = $this->getLastLoginIdStatusN($token);
		$log = $this->filtering($arr);
		$session = new Session;
		if( !empty($log) ){ 
			$session->setSession('admin_token', '');
			$session->setSession('token_seq', '');
			return $log ;
		}
		
		if( $token == $session->getCookie('admin_token') ){
			$this->token_data_update_use(A::str($arr, 'token_seq'));
		}else{
			$this->token_data_update_first(A::str($arr, 'token_seq'));
		}
		
		$session->setSession('admin_token', $token);
		$session->setSession('token_seq', A::str($arr, 'token_seq'));
		return ;
	}
	
	function filtering($arr)
	{
		if( A::str($arr, 'token_seq') == '' ){
			return 'no token seq';
		}
		if( A::str($arr, 'admin_id') == '' ){
			return 'no token';
		}
		if( A::str($arr, 'reg_ip') != $_SERVER['REMOTE_ADDR']){
			return 'not equals reg_ip';
		}
		
		return '';
	}
	
	function token_data_update_first($token_seq)
	{
		$q = '
			UPDATE '._db_loan_token_login_.'
			SET
				use_is_status = :use_is_status
			,	use_time = :use_time
			,	use_browser = :use_browser
			,	use_time_update = :use_time_update
			,	use_cnt = :use_cnt
			WHERE token_seq = :token_seq
			' ;
		
		$date = date('Y-m-d H:i:s');
		$stmt = db()->prepare($q);
		$stmt->bindValue(':token_seq', $token_seq, PDO::PARAM_INT);
		$stmt->bindValue(':use_is_status', 'Y');
		$stmt->bindValue(':use_time', $date);
		$stmt->bindValue(':use_browser', $_SERVER['HTTP_USER_AGENT']);
		$stmt->bindValue(':use_time_update', $date);
		$stmt->bindValue(':use_cnt', 1, PDO::PARAM_INT);
		stmtExecute($stmt);
	}
	function token_data_update_use($token_seq)
	{
		$q = '
			UPDATE '._db_loan_token_login_.'
			SET
				use_time_update = :use_time_update
			,	use_cnt = use_cnt + 1
			WHERE token_seq = :token_seq
			' ;
		
		$date = date('Y-m-d H:i:s');
		$stmt = db()->prepare($q);
		$stmt->bindValue(':token_seq', $token_seq, PDO::PARAM_INT);
		$stmt->bindValue(':use_time_update', $date);
		stmtExecute($stmt);
	}
	
	function getLastLoginIdStatusN($token)
	{
		$q = '
			SELECT TOP 1 * FROM '._db_loan_token_login_.'
			WHERE token = :token
			AND use_is_status = :is_status
			ORDER BY token_seq desc
			' ;
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':is_status', 'N');
		stmtExecute($stmt);
		
		return $stmt->fetch();
	}
}

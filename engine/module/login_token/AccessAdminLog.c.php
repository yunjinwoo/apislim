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
 */ // _LINKED_ADMIN_
class AccessAdminLog extends ListCommon {
	//put your code here

	 
    function getRow($token){
		$q = '
			SELECT TOP 1 * FROM access_admin_log 
			WHERE access_admin_token = :token
			ORDER BY access_admin_log_idx DESC 
			';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':token', $token);
        
        stmtExecute($stmt);
        return $stmt->fetch();
	}
    
	function updateIdxGroup($log_idx, $login_group = ''){
		$q = '
			UPDATE access_admin_log
			SET
				access_admin_log_idx_group = :access_admin_log_idx_group
			WHERE
				access_admin_log_idx = :access_admin_log_idx
			';
		
		$stmt = msdb()->prepare($q);
		$stmt->bindValue(':access_admin_log_idx_group', $login_group);
		$stmt->bindValue(':access_admin_log_idx', $log_idx);
		
		stmtExecute($stmt);
	}
	
    function save($token, $login_id, $type = '', $url = ''){		
        $q = ' INSERT INTO access_admin_log 
                ( access_admin_id,	access_admin_token,	access_url
				, access_ip,		access_agent_type,	access_agent_full
				, reg_time ,access_admin_log_idx_group)
                VALUES 
                ( :access_admin_id, :access_admin_token, :access_url
				, :access_ip,		:access_agent_type, :access_agent_full
				, :reg_time, :access_admin_log_idx_group)
                ' ;

		$ip = A::str($_SERVER, 'REMOTE_ADDR');
		$agent_full = A::str($_SERVER, 'HTTP_USER_AGENT');
		$agent_type = getAgentType(A::str($_SERVER, 'HTTP_USER_AGENT'));
		if( empty($url) ){
			$url = A::str($_SERVER, 'REQUEST_URI');
		}
		if( !is_numeric($type) ){
			$type = 0;
		}
		
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':access_admin_id', $login_id);
        $stmt->bindValue(':access_admin_token',$token);
        $stmt->bindValue(':access_url',$url);
        $stmt->bindValue(':access_admin_log_idx_group',$type);
		
        $stmt->bindValue(':access_ip',$ip);
        $stmt->bindValue(':access_agent_type',$agent_type);
        $stmt->bindValue(':access_agent_full',$agent_full);
		
       // $stmt->bindValue(':access_admin_log_idx_group',$login_group);
        $stmt->bindValue(':reg_time', date('Y-m-d H:i:s'));
      
        stmtExecute($stmt);
		
		return mslast_insert_id();
    }
    
    function delete($reg_time = ''){
		if( !F::isDatetime($reg_time) ){
			$reg_time = date('Y-m-d H:i:s', stototime('-1 year') );
		}
        $q = ' DELETE FROM access_admin_log WHERE reg_time = :reg_time ';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':reg_time', $reg_time);
        
        stmtExecute($stmt);
    }
}

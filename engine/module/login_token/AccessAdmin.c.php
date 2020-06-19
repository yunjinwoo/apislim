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
class AccessAdmin extends ListCommon {
	protected $list = array();
    
    function getRow($login_id){
        if( isset($this->list[$login_id]) ){
            return $this->list[$login_id];
        }
        
		$q = 'SELECT * FROM access_admin WHERE login_id = :login_id';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_id', $login_id);
        
        stmtExecute($stmt);
		$a = $stmt->fetch();
		if( count($a) > 1 ){
			$this->list[$login_id] = $a;
			return A::arr($this->list, $login_id);
		}else{
			return array();
		}
		
	}
    
	function getList(){
		$q = '
            SELECT * FROM access_admin ORDER BY reg_date DESC';
        $stmt = msdb()->prepare($q);
        
        stmtExecute($stmt);
        $this->list = array();
        while( $a = $stmt->fetch() ){
            $this->list[$a['login_id']] = $a;
        }
        return $this->list;
	}
    
    function save($aSave, $login_id = ''){
		$check_row = $this->getRow($aSave['login_id']);
		if( count($check_row) > 1 ){
			$login_id = $aSave['login_id'];
		}
		
        if( empty($login_id) ){
			$date_field = 'reg_date';
            $q = ' INSERT INTO access_admin 
                ( login_name,  login_id,  login_pw, is_use, '.$date_field.' )
                VALUES 
                ( :login_name, :login_id  ,:login_pw, :is_use, :'.$date_field.' )
                ' ;
        }else{
			$date_field = 'update_date';
            $q = '
                UPDATE access_admin SET
                  is_use = :is_use
                , login_name = :login_name
                , login_pw = :login_pw
                , '.$date_field.' = :'.$date_field.'
                WHERE login_id = :login_id
                ' ;
        }
        
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_name'	,A::str($aSave, 'login_name'));
        $stmt->bindValue(':login_pw'	,A::str($aSave, 'login_pw'));
		
        $stmt->bindValue(':login_id'	,A::str($aSave, 'login_id'));
        $stmt->bindValue(':is_use'		,F::YN(A::str($aSave, 'is_use'),'Y'));
        $stmt->bindValue(':'.$date_field , date('Y-m-d H:i:s'));
        
        stmtExecute($stmt);
    }
    
    function delete($login_id){
        $q = ' DELETE FROM access_admin WHERE login_id = :login_id ';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_id', $login_id);
        
        stmtExecute($stmt);
    }
}

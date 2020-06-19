<?php
/**
 * Description of TokenAutoLogin
 *
 * @author Administrator
 */ // _LINKED_ADMIN_
class AccessAdminOption extends ListCommon {
	protected $list;
			
	function getUserOption($login_id){
		if( isset($this->list[$login_id]) ){
			return $this->list[$login_id];
		}
		
		$q = '
			SELECT * FROM access_admin_option 
			WHERE login_id = :login_id
			ORDER BY option_name, reg_date ASC
			';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_id', $login_id);
        
        stmtExecute($stmt);
		while( $a = $stmt->fetch() ){
			$this->list[$a['login_id']][$a['option_name']][$a['option_value']] = $a;
		}
		
		return A::arr($this->list, $login_id);
	}
	
    function insert($login_id, $option_name, $option_value){		
        $q = ' INSERT INTO access_admin_option 
                ( login_id,	option_name,	option_value,	reg_date)
                VALUES 
                ( :login_id, :option_name, :option_value,	:reg_date)
                ' ;
		
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_id', $login_id);
        $stmt->bindValue(':option_name',$option_name);
        $stmt->bindValue(':option_value',$option_value);
        $stmt->bindValue(':reg_date', date('Y-m-d H:i:s'));
      
        stmtExecute($stmt);
    }
    
    function delete($login_id){
        $q = ' DELETE FROM access_admin_option WHERE login_id = :login_id ';
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':login_id', $login_id);
        
        stmtExecute($stmt);
    }
}

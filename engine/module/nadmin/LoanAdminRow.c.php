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
class LoanAdminRow {
	//put your code here
	protected $link;
	
	function __construct() {
		$this->link = _LINKED_ADMIN_;
	}

	function row($userid, $status = 0 )
	{
		$q = '
            select top 1 * from '.$this->link.'tbAdmin 
                where vcAdminID = :userid 
                and tiDstatus = :status';
        
        $stmt = msdb()->prepare($q);
        $stmt->bindValue(':userid', $userid);
        $stmt->bindValue(':status', $status);
        
        stmtExecute($stmt);
        return $stmt->fetch();
	}
}

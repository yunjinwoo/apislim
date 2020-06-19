<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sms
 *
 * @author Administrator
 * 
 * 
 * 
 */
class SmsSend {
	protected $log;
	
	protected $phone_to;
	protected $phone_from;
	protected $sms_msg;
	protected $sms_date;
	protected $send_type;
	protected $result;
	
	function setParam($sms_send_id, $sms_to, $sms_from, $sms_msg, $sms_date, $send_type, $result){
		$this->sms_send_id	 = $sms_send_id;
		$this->phone_to		 = $sms_to;
		$this->phone_from	 = $sms_from;
		$this->sms_msg		 = $sms_msg;
		$this->sms_date		 = $sms_date;
		$this->send_type	 = $send_type;
		$this->result		 = $result;
		
		if( F::isDatetime($this->sms_date) ){ $this->sms_date = date('Y-m-d H:i:s');}
		return $this;
	} 
	
	function insertLog()
	{
		$q = '
			INSERT INTO [dbo].[sms_result]
					   ([sms_send_id]
					   ,[sms_type]
					   ,[phone_from]
					   ,[phone_to]
					   ,[msg]
					   ,[result]
					   ,[dtRegDate])
				 VALUES
					   (:sms_send_id
					   ,:sms_type
					   ,:phone_from
					   ,:phone_to
					   ,:msg
					   ,:result
					   ,getdate())
		' ;
		
		$stmt = db()->prepare($q);
		
		$stmt->bindValue(':sms_type', $this->send_type);
		$stmt->bindValue(':sms_send_id', $this->sms_send_id);
		$stmt->bindValue(':phone_from', $this->phone_from);
		$stmt->bindValue(':phone_to', $this->phone_to);
		$stmt->bindValue(':msg', $this->sms_msg);
		$stmt->bindValue(':result', $this->result);
		
		stmtExecute($stmt);
	}

	function getLog()
	{
		return $this->log ;
	}
}

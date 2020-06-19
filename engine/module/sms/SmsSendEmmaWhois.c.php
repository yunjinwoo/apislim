<?php



class SmsEmmaWhois extends SmsSend{
	private $SMS ;
	function __construct() {
		$sms_id = "test_sms_id";
		$sms_passwd = '비밀번호가...ㅠ';//"";

		$this->SMS = new EmmaSMS();
		$this->SMS->login($sms_id, $sms_passwd);
	}
	
	function send($sms_to, $sms_from, $sms_msg, $sms_date = '', $log_sms_type = '상담' ){
        $sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환
		$ret = $this->SMS->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);
		$this->setParam(admin_row_view('admin_id'), $sms_to, $sms_from, $sms_msg, $sms_date, $log_sms_type, print_r($ret, true))->insertLog();
		
		if(!$ret){
			$this->_setLog();
			return false;
		}else{
			return true;
		}
	}
	
	function point()
	{
		
//ini_set('display_errors', 'on');
//ini_set('display_startup_errors', 'on');
		$point = $this->SMS->point();

		if($point != false){
			return $point;
		}else{
			$this->_setLog();
			return -1;
		}
	}
	
	
	function statisticsList($year = '', $month = '' )
	{
		if(!is_numeric($year) && strlen($year) != 4 ){ $year = date('Y'); }
		if(!is_numeric($month) && !F::isBetween($month, 1, 12)){  $month = date('m'); }
		
		$retValue = $this->SMS->statistics ($year,$month);	// 2008년 11월
		if ($retValue) {
			return $retValue;
		}
		else {
			$this->_setLog();
			return -1;
		}
	}
	
	function _setLog(){
		$this->log = $this->SMS->errMsg;
	}
	
}

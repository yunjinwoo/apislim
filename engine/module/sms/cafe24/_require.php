<?php
define('_db_cafe24_list_', _db_fix_.'sms_list_cafe24');

$__cafe24_sms_vars = array();
function cafe24_sms_code_user_id(){
	return Code::getCodeStr('sms_cafe24_user_id');
}
function cafe24_sms_code_secure(){
	return Code::getCodeStr('sms_cafe24_secure');
}
	
function cafe24_sms_get_remain_count(){
	global $__cafe24_sms_vars;
	if( isset($__cafe24_sms_vars['remain_count']) ){
		//pre('recall');
		return $__cafe24_sms_vars['remain_count'];
	}
	// function 밖이면 안되는거지.. 181818

	$oCurl = curl_init();
    $url =  "https://sslsms.cafe24.com/sms_remain.php";
    $aPostData['user_id'] = cafe24_sms_code_user_id(); // SMS 아이디
    $aPostData['secure'] = cafe24_sms_code_secure(); // 인증키
	
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_POST, 1);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPostData);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($oCurl);
  
    curl_close($oCurl);
	
	$__cafe24_sms_vars['remain_count'] = $ret;
	return $ret;
}


function cafe24_sms_get_send_phone_list(){
	global $__cafe24_sms_vars;
	if( isset($__cafe24_sms_vars['send_phone_list']) ){
		//pre('recall');
		return $__cafe24_sms_vars['send_phone_list'];
	}
	
	$oCurl = curl_init();
    $url =  "https://sslsms.cafe24.com/smsSenderPhone.php";
    $aPostData['userId'] = cafe24_sms_code_user_id(); // SMS 아이디
    $aPostData['passwd'] = cafe24_sms_code_secure(); // 인증키
	
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_POST, 1);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $aPostData);
    curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
    $ret = curl_exec($oCurl);
    curl_close($oCurl);
	
	$__cafe24_sms_vars['send_phone_list'] = $ret;
	// JSON {"list":["1670-3820"],"total":1,"result":"Success"}
	return $ret;
}

function cafe24_sms_action_send($msg , $to_phone, $form_phone, $is_db_insert = true){
	/******************** 인증정보 ********************/
        //$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
        $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
		
		$oCurl = curl_init();
		$url = $sms_url;
		$sms = array();
		$sms['user_id'] = base64_encode(cafe24_sms_code_user_id()); // SMS 아이디
		$sms['secure'] = base64_encode(cafe24_sms_code_secure()); // 인증키
        $sms['msg'] = base64_encode(stripslashes($msg));
		if( strlen($msg) > 80 ){
			$sms['smsType'] = base64_encode('L'); // LMS일경우 L
            $sms['subject'] =  base64_encode('제목이있으면 어떻게되지??');
		}else{
			$sms['smsType'] = base64_encode('S'); 
		}
		$sms['testflag'] = Code::getCodeStr('sms_test_send');
		
		$sms['rphone'] = base64_encode($to_phone);
		$a = explode('-', $form_phone);
        $sms['sphone1'] = base64_encode(A::str($a, 0));
        $sms['sphone2'] = base64_encode(A::str($a, 1));
        $sms['sphone3'] = base64_encode(A::str($a, 2));
        $sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_POST, 1);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec($oCurl);
		curl_close($oCurl);

		$rMsg = explode(",", trim($ret));
		$Result= trim($rMsg[0]); //발송결과
		$Count= $rMsg[1]; //잔여건수
		
		if( $is_db_insert ){
			$SaveCommon = new SaveCommon(_db_cafe24_list_);
			$SaveCommon->addData('cafe24_id', cafe24_sms_code_user_id());
			$SaveCommon->addData('msg', $msg);
			$SaveCommon->addData('to_phone', $to_phone);
			$SaveCommon->addData('form_phone', $form_phone);
			$SaveCommon->addData('result_code', $Result);
			$SaveCommon->addData('result_cnt', $Count);
			$SaveCommon->addData('reg_var_ip', $_SERVER['REMOTE_ADDR']);
			$SaveCommon->addData('reg_var_page', $_SERVER['PHP_SELF']);
			$SaveCommon->addData('reg_var_agent', $_SERVER['HTTP_USER_AGENT']);
			$SaveCommon->saveCommon(db());
		}
		
		if( $Result=="success" || $Result=="Test Success!" ){
			return 'ok';
		}else{
			return $Result;
		}
		
//        $sms['rdate'] = base64_encode($_POST['rdate']);
//        $sms['rtime'] = base64_encode($_POST['rtime']);
//        $sms['returnurl'] = base64_encode($_POST['returnurl']);
//        $sms['testflag'] = base64_encode($_POST['testflag']);
//        $sms['destination'] = strtr(base64_encode($POST['destination']), '+/=', '-,');
//        $returnurl = $_POST['returnurl'];
//        $sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
//        $sms['repeatNum'] = base64_encode($_POST['repeatNum']);
//        $sms['repeatTime'] = base64_encode($_POST['repeatTime']);
        $nointeractive = 1; //$_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

        $host_info = explode("/", $sms_url);
        $host = $host_info[2];
        $path = $host_info[3]."/".$host_info[4];

        srand((double)microtime()*1000000);
        $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
        //print_r($sms);

        // 헤더 생성
        $header = "POST /".$path ." HTTP/1.0\r\n";
        $header .= "Host: ".$host."\r\n";
        $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

        // 본문 생성
        foreach($sms AS $index => $value){
            $data .="--$boundary\r\n";
            $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
            $data .= "\r\n".$value."\r\n";
            $data .="--$boundary\r\n";
        }
        $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

        $fp = fsockopen($host, 80);

        if ($fp) {
            fputs($fp, $header.$data);
            $rsp = '';
            while(!feof($fp)) {
                $rsp .= fgets($fp,8192);
            }
            fclose($fp);
            $msg = explode("\r\n\r\n",trim($rsp));
            $rMsg = explode(",", $msg[1]);
            $Result= $rMsg[0]; //발송결과
            $Count= $rMsg[1]; //잔여건수

            //발송결과 알림
            if($Result=="success") {
                $alert = "성공";
                $alert .= " 잔여건수는 ".$Count."건 입니다.";
            }
            else if($Result=="reserved") {
                $alert = "성공적으로 예약되었습니다.";
                $alert .= " 잔여건수는 ".$Count."건 입니다.";
            }
            else if($Result=="3205") {
                $alert = "잘못된 번호형식입니다.";
            }

            else if($Result=="0044") {
                $alert = "스팸문자는발송되지 않습니다.";
            }

            else {
                $alert = "[Error]".$Result;
            }
        }
        else {
            $alert = "Connection Failed";
        }

        if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
            echo "<script>alert('".$alert ."')</script>";
        }
        else if($nointeractive!="1") {
            echo "<script>alert('".$alert ."')</script>";
        }
        echo "<script>location.href='".$returnurl."';</script>";
	
}



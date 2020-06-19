<?php

function print_push_form_cargo($push_type, $field){
	// $push_type : chago , chagodealer
	?>
	
	<form name="admin_push" action="?act=push" method="post">
		<?php foreach( $field as $field => $value ): ?>
		<input type="hidden" name="<?=$field?>" value="<?=$value?>" />
		<?php endforeach; ?>
		<input type="hidden" name="push_type" value="<?=$push_type?>" />
		제목:<input name="push_title" placeholder="제목" value="김보성의 차고"/>
		<textarea name="push_msg" style="width:300px;height:70px" placeholder="내용"></textarea>
		<input type="submit" class="btn btn-register" value="저장"/>
	</form>
	<?php
}


define('__FCM_SERVER_KEY__','AAAAsHg3mLk:APA91bHuY9BQQG3x0rLKyOf3BnyBikUQ0gi5WQR97KQnj8rk8Pel0zVnhqXg87Cv_WZf9cLhuyqpTRhndpB3i0IJc2tpBjDc_IiJZxPIRK8G10b3_yMzS_qQ8hEx8R5A-1rLpJbdPHeV');


class SnsPushLog extends SaveCommon {
	protected $table = 'jin_sns_push_log';
	
	function getPrimaryField() {
		return 'log_idx';
	}
	function __construct() {
		$this->setOrder('user_idx DESC');
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'user_idx' => '회원 기본키'
		,	'user_idx' => '유저키'
		,	'fcm_token' => 'token'
			
		,	'device_type' => ''				
		,	'push_title' => ''
		,	'push_msg' => ''
		,	'push_link' => ''
		,	'send_user_idx' => '유저키'
		,	'result' => ''
		,	'reg_date' => '등록일'
		);
		
	}
	
	function dartLog($token, $msg, $link, $title){
		$this->save([
			 'user_idx' => 1,
			 'device_type' => 'android' ,
			 'fcm_token' => $token,
			 'push_title' => $title,
			 'push_msg' => $msg,
			 'push_link' => $link,
			 'result' => '',
			 'send_user_idx' => 'crontab',
		]);
	}

	function fcm_send_notification($tokens, $message, $link = '', $title = ''){
		global $push_link, $push_title;
		if( $link != '' ){ $push_link = $link; }
		if( $title != '' ){ $push_title = $title; }
		
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'registration_ids' => $tokens,
			"priority" => "high",
			'data' => array('link'=> $push_link, "title" => $push_title, "body" => $message)
		);
	//534274947549
		$headers = array(
			'Authorization:key='.__FCM_SERVER_KEY__,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);           
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		
		
//		pre($fields);
//		exit;
		
		return $result;
	}

	function fcm_send_data($tokens, $message){
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'registration_ids' => $tokens,
			"priority" => "high",
			'data' => $message
		);

		$headers = array(
			'Authorization:key='.__FCM_SERVER_KEY__,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);           
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}
}
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
}
<?php
class logLoginPage extends SaveCommon{
	private $login_idx;
	protected $table = _db_log_login_page_;
			
	function __construct($login_idx = '') {
		$this->bind_field['login_idx'] = ['login_idx', 'login_idx'];
		$this->bind_class['login_idx'] = new logLogin;	
	}
	function getPrimaryField() {
		return 'page_idx';
	}
}
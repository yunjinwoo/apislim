<?php
define('_db_user_point_', _db_fix_.'user_point');

class UserPoint extends SaveCommon{
	protected $table = _db_user_point_;
	
	function __construct() {
		parent::__construct();
	}
	
	
}
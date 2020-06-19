<?php

class MemberDealerLog extends MemberDealer {
	protected $table = _db_member_dealer_log_;
	
	function __construct() {
		parent::__construct();

		$this->table_field['dealer_log_idx'] = '이력변경 번호';
		$this->table_field['log_date'] = '이력정보 등록일';
		
		/*
		$this->bind_data = [];
		$this->bind_field = [];
		$this->bind_class = [];*/
		$this->bind_field['dealer_idx'] = 'dealer_idx';
		$this->bind_class['dealer_idx'] = new MemberDealer;
	}
	
	function getPrimaryField(){
		return 'dealer_log_idx';
	}
}
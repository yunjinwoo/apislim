<?php

class LoanLoannloanApiField
{
	/** 
	* 고유키
	*/
	public $black_seq;
	/** 
	* 상담번호
	*/
	public $advice_seq;
	/** 
	* 전송한 어드민
	*/
	public $admin_type;
	/** 
	* 고객 전화번호
	*/
	public $u_phone;
	/** 
	* 고객 이름
	*/
	public $u_name;
	/** 
	* 상담사 아이디
	*/
	public $admin_id;
	/** 
	* 상담사 이름
	*/
	public $admin_name;
	/** 
	* 전송 페이지 - 임시
	*/
	public $refer;
	/** 
	* 블락 등록/해제
	*/
	public $is_black;
	/** 
	* 등록일
	*/
	public $reg_date;
	/** 
	* 기타메모
	*/
	public $memo;
}


class LoanLoannloanApi extends SaveCommon 
{
	protected $table = 'jin_loannloan_loan_form';
	protected $table_log = 'black_list_log';

	function __construct(){
		$this->setOrder(' form_idx DESC ');
	}
	
	function db(){
		return db();
	}
	

	function save($aSave)
	{
		$LoanLoannloanApi = new LoanLoannloanApi();


		$LoanLoannloanApi->addData('reg_type'	, 'loannloan');
		$LoanLoannloanApi->addData('media'	, A::str($aSave, 'advice_seq'), PDO::PARAM_INT);
		$LoanLoannloanApi->addData('keyword'	, A::str($aSave, 'admin_type'), PDO::PARAM_INT);
		
		$LoanLoannloanApi->addData('loan_title'		, A::str($aSave, 'memo'));
		
		$LoanLoannloanApi->addData('loan_phone1'		, A::str($aSave, 'loan_phone1'));
		$LoanLoannloanApi->addData('loan_phone2'		, A::str($aSave, 'loan_phone2'));
		$LoanLoannloanApi->addData('loan_phone3'		, A::str($aSave, 'loan_phone3'));
		$LoanLoannloanApi->addData('loan_user_name'		, A::str($aSave, 'loan_user_name'));
		$LoanLoannloanApi->addData('reg_admin_id'		, A::str($aSave, 'admin_id').':'.A::str($aSave, "admin_name"));
		
		$LoanLoannloanApi->addData('reg_admin_login_info'			, '['.A::str($_SERVER, 'REMOTE_ADDR').']'.A::str($_SERVER, 'HTTP_REFERER'));
	
		
		$LoanLoannloanApi->addData('loan_amount'		, A::str($aSave, 'loan_amount'));
		$LoanLoannloanApi->addData('loan_job'		, A::str($aSave, 'loan_job'));
		$LoanLoannloanApi->addData('loan_age'		, A::str($aSave, 'loan_age'));
		$LoanLoannloanApi->addData('loan_gender'		, A::str($aSave, 'loan_gender'));
		
		$form_idx = A::str($aSave, 'black_seq');
		if( is_numeric($form_idx) ){
			$LoanLoannloanApi->addWhere('form_idx', $form_idx);
		}else{
			$LoanLoannloanApi->addData('reg_date'			, date('Y-m-d H:i:s') );
		}
	
		$insert_seq = $LoanLoannloanApi->saveCommon($LoanLoannloanApi->db());
		//$this->addLogSeq($insert_seq, 'insert');
	}

	function getList(){
		return $this->getListCommon('black_seq');
	}

	function del($aSave){
		$BlackList = new BlackList();
		$BlackList->addWhere('u_phone'			,  A::str($aSave, 'u_phone'));
		$a = $BlackList->getListCommon('black_seq');

		foreach( $a as $seq => $row ){
			$row['log_type'] = 'delete';
			$BlackList->addLog($row);
		}

		$BlackList->deleteCommon($this->db());
	}

	function addLogSeq($black_seq, $log_type){
		$BlackList = new BlackList();
		$BlackList->addWhere('black_seq'			, $black_seq);
		$a = $BlackList->getListCommon('black_seq');
		$aSave = A::arr($a, $black_seq);

		$aSave['log_type'] = $log_type;

		$BlackList->addLog($aSave);
	}

	function addLog($aSave){
		$SaveCommon = new SaveCommon($this->table_log);
		
		$SaveCommon->addData('log_type'		, A::str($aSave, 'log_type') );
		$SaveCommon->addData('log_reg_date'	, date('Y-m-d H:i:s'));
		$SaveCommon->addData('black_seq'		,A::str($aSave, 'black_seq'), PDO::PARAM_INT);

		$SaveCommon->addData('advice_seq'		, A::str($aSave, 'advice_seq'), PDO::PARAM_INT);
		$SaveCommon->addData('admin_type'	, A::str($aSave, 'admin_type'));
		$SaveCommon->addData('u_phone'		, A::str($aSave, 'u_phone'));
		$SaveCommon->addData('u_name'			, A::str($aSave, 'u_name'));
		$SaveCommon->addData('admin_id'		, A::str($aSave, 'admin_id'));
		$SaveCommon->addData('admin_name'	, A::str($aSave, 'admin_name'));
		$SaveCommon->addData('refer'				, A::str($_SERVER, 'HTTP_REFERER'));
		$SaveCommon->addData('addr_ip'			, A::str($_SERVER, 'REMOTE_ADDR'));
		$SaveCommon->addData('reg_date'			, A::str($aSave, 'reg_date'));
		$SaveCommon->addData('memo'			, A::str($aSave, 'memo'));
	
		$SaveCommon->saveCommon($this->db());
	}

	function getGroupCnt(){
		$q = '
			SELECT COUNT( admin_id ) as cnt , admin_id, admin_name FROM '.$this->table.'
			'.$this->getWhere().'
			GROUP BY admin_id, admin_name
		';

		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch()){
			$ret[$a['admin_id']] = $a;
		}

		return $ret;
	}

	
	function getCountLog(){
		$q = '
			SELECT count(*) as cnt 
			FROM '.$this->table_log.' 
				'.$this->getWhere().'
				'.$this->getGroup().'
			';
		
		$ret = array();
		$stmt = $this->db()->prepare($q);
		stmtExecute($stmt);
		
		return $stmt->fetchObject()->cnt ;
	}
}

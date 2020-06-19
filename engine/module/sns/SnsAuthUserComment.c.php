<?php

class SnsAuthUserComment extends SaveCommon {
	protected $table = 'jin_sns_auth_user_comment';
	
	function getPrimaryField() {
		return 'comment_idx';
	}
	function __construct() {
		$this->setOrder(' comment_idx DESC');
		
		
		// 일단 없는 테이블..
		$this->table_field = array(
			'comment_idx' => '기본키'
		,	'is_use' => 'Y'
		,	'user_idx' => '회원 기본키'
		,	'form_idx' => '회원 기본키'
		,	'preview_idx' => '회원 기본키'
		,	'write_user_idx' => '회원 기본키'
		,	'write_name' => '작성자이름'
			 
		,	'point_num' => '회원 기본키'
		,	'comment_text' => '회원 기본키'
			 
		,	'reg_date' => '등록일'
		,	'update_date' => '수정일'
		
		
		);
		
		//$this->table_field_file['dealer_img'] = '차량이미지';
		
	}
	
	function avgPrint($user_idx){
		$q = ' SELECT AVG(point_num) as avg_point FROM '.$this->table.' WHERE user_idx = '.$user_idx.' GROUP BY user_idx ';
		$stmt = $this->db()->query($q);
		
		//$a = $stmt->fetch();
		return $stmt['avg_point'];
	}
	
	function point_avg_cnt($user_idx){
		$q = ' 
		SELECT 
			ROUND(AVG(point_num),1) as point_avg
		,	COUNT(*) as point_cnt
		FROM '.$this->table.' WHERE is_use = \'Y\' AND user_idx = '.$user_idx.' GROUP BY user_idx ';
		//pre($q);
		$stmt = $this->db()->query($q);
		$a = $stmt->fetch();
		//pre($stmt);
		//pre($a);
		//$a = $stmt->fetch();
		return [
			'point_avg' => $a['point_avg']	
			,'point_cnt' => $a['point_cnt']	
		];
	}
	
	
	function point_num_group($user_idx){
		$q = ' 
		SELECT 
			COUNT(point_num) as sum_point_num
		,	point_num
		FROM '.$this->table.' WHERE is_use = \'Y\' AND user_idx = '.$user_idx.' GROUP BY point_num ';
		//pre($q);
		$stmt = $this->db()->query($q);
		$a = $stmt->fetchAll();
//		pre($stmt);
//		pre($a);
		//$a = $stmt->fetch();
		return $a;
	}
//	function _row_replace($aRow) {
//	}
	
	function update_dealer_point_data($user_idx){
		//딜러평점 업데이트
		$arr = $this->point_avg_cnt($user_idx);
		
		$SNS = new SnsAuthUser;
		$SNS->addWhere('user_idx', $user_idx);
		$SNS->addData('dealer_point_avg', round($arr['point_avg'],1));
		$SNS->addData('dealer_point_cnt', $arr['point_cnt']);
		$SNS->updateDefault();
	}
}
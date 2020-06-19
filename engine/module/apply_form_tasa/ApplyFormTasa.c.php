<?php

class ApplyFormTasa extends SaveCommon {
	protected $table = 'jin_apply_form_tasa';
	
	function getPrimaryField() {
		return 'form_idx';
	}
	function __construct() {
		$this->setOrder('form_idx DESC');
		
		$this->table_int_field = array(
			 'tasa_price' => '',
			 'admin_price' => '',
			 'month_size' => ''
		);
		// 일단 없는 테이블..
		$this->table_field = array(
				'form_idx' => '기본키'
			,	'reg_type' => '신청구분'
			,	'reg_admin_id' => '입력한 관리자'
			,	'is_use' => '삭제여부'
			,	'status_str' => '기타상태'
			
			,	'user_name' => '고객명'
			,	'apply_phone' => '고객전화번호' //안씀
			,	'apply_phone1' => '고객전화번호1'
			,	'apply_phone2' => '고객전화번호2'
			,	'apply_phone3' => '고객전화번호3'
			,	'apply_content' => '내용'
			
			,	'read_cnt' => '조회수'
			 
			,	'reg_ip' => '저장아이피'
			,	'reg_date' => '등록일'
			,	'update_time' => '수정일'
			
//			,	'refer_url' => '...'
//			,	'sell_car' => '...'

			,	'user_idx' => '회원번호'
//			,	'car_seq' => '신청페이지 차량 키'
//			,	'car_name' => '신청페이지 차량명'
			 
			 
			,	'tasa_price' => '타사견적가'
			,	'admin_price' => '가능견적가'
			,	'month_size' => '개월수'
			,	'diff_price' => '차이'
			 
			//,	'admin_upfile' => '관리자첨부파일'
			,	'admin_content' => '차고평가'
			,	'admin_reg_date' => '어드민답변등록일'
			 
			,	'car_country' => '국산/수입 구분'
			,	'car_company' => '제조사'
			,	'car_name' => '차량명'
			,	'car_name_detail' => '모델명'
			
			 
			,	'car_price' => '차량가'
			,	'car_option_price' => '옵션합계'
			 
			,	'car_option_0' => '옵션1'
			,	'car_option_1' => '옵션1'
			,	'car_option_2' => '옵션1'
			,	'car_option_3' => '옵션1'
			,	'car_option_4' => '옵션1'
			,	'car_option_5' => '옵션1'
			,	'car_option_6' => '옵션1'
			,	'car_option_7' => '옵션1'
			,	'car_option_8' => '옵션1'
			,	'car_option_9' => '옵션1'
		);
		
		$this->table_field_file['upfile'] = '사용자견적서';
		$this->table_field_file['admin_upfile'] = '어드민견적서';
		
		$this->table_hash_field = array(
			'user_pw' => '회원 비밀번호'
		);
		$this->table_encode_field = array(
			'user_phone' => '회원연락처'
		);
	}
	
	
	function setWhereRegDateSearch( $start_date, $end_date, $month = '' )
	{
		
		if( F::isDate($start_date) && F::isDate($end_date) ){
			$this->addWhereStr('reg_date', ' BETWEEN \''.$start_date.' 00:00:00\' AND \''.$end_date.' 23:59:59\' ' );
		}elseif( F::isDate($end_date) ){
			$this->addWhereStr('reg_date', ' <= \''.$end_date.' 23:59:59\' ' );
		}else if( F::isDate($start_date) ){
			$this->addWhereStr('reg_date', ' >= \''.$start_date.' 00:00:00\' ');
		}elseif(is_numeric ($month) ){
			// 날짜가 없으면 기본 6개월로...
			$start_date = date('Y-m-d', strtotime(' -'.$month.' month' ));
			$this->addWhereStr('reg_date', ' >= \''.$start_date.' 00:00:00\' ');
		}
	}
	
}
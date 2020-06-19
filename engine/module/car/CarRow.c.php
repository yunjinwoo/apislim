<?php


class CarRow extends CarList
{
	protected $row;

	function setRow($seq)
	{
		$q = '
			SELECT * FROM car_list
			WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);

		$a = $stmt->fetch();
		$this->row = __car__row($a);;
	}
	
	function setRowArr($arr, $isReplace = true){
		if( $isReplace ) {
			$this->row = __car__row($arr);
		}else{
			$this->row = $arr;
		}
	}

	function getTableTr( $table_name, $index = 1)
	{
		if(!is_numeric($index) || $index <= 0)
			$index = 1 ;
		
		if( isset($this->row[$table_name]['tr'.$index]) )
			return $this->row[$table_name]['tr'.$index];
		else {
			$a = array();
			if( $table_name == 'car_table1' )
			{
				if( $index == 1 ){
					$a = array('td1' => '0%',
						'td4' => '일반상품',
						'td5' => '정비불포함'); 
				}else if( $index == 2 ){
					$a = array('td1' => '20%',
						'td4' => '일반상품',
						'td5' => '정비불포함'); 
				}else if( $index == 3 ){
					$a = array('td1' => '30%',
						'td4' => '일반상품',
						'td5' => '정비불포함'); 
				}	
			}else if( $table_name == 'car_table2' )
			{
				if( $index == 1 ){
					$a = array(	'td1' => '보험조건',
								'td2' => '26세 이상%',
								'td3' => '무제한',
								'td4' => '1억',
								'td5' => '1억',
								'td6' => '가입'); 
				}
			}else if( $table_name == 'car_table3' ) {
				if( $index == 1 ){
					$a = array('td1' => '자차(면책금제도)',
						'td2' => '사고 건당 고객부담금 최대 30만원 ( 30만원 미만 발생시 실비납부 )'); 
				}
			}
		}
		return $a;
	}

	function data($field, $sub = '', $subsub = '')
	{
		$s = '';
		switch ($field)
		{
			case 'car_name_print' : 
				if( $this->row['car_name_print'] == '' ){
					$s = $this->row['car_name'];
				}else{
					$s = $this->row['car_name_print'];
				}
			break;
			case 'car_table1_cnt' : $s = count($this->row['car_table1']); $s = $s <= 3 ? 3 : $s ;break;
			case 'car_table2_cnt' : $s = count($this->row['car_table2']); break;
			case 'car_table3_cnt' : $s = count($this->row['car_table3']); break;
				
			case 'car_table1' : case 'car_table2' : case 'car_table3' : 
				$s = isset($this->row[$field]) && is_array($this->row[$field]) && count($this->row[$field]) >= 1 ?$this->row[$field]:array();
				
				if( count($s) == 0 )
				{
					if( $field == 'car_table1' )
					{
						$a = array();
						$a[] = array('td1' => '0%',
								'td4' => '일반상품',
								'td5' => '정비불포함'); 
						$a[] = array('td1' => '20%',
								'td4' => '일반상품',
								'td5' => '정비불포함'); 
						$a[] = array('td1' => '30%',
								'td4' => '일반상품',
								'td5' => '정비불포함'); 
					}else if( $field == 'car_table2' )
					{
						$a = array();
						$a[] = array(	'td1' => '보험조건',
									'td2' => '26세 이상%',
									'td3' => '무제한',
									'td4' => '1억',
									'td5' => '1억',
									'td6' => '가입'); 
						
					}else if( $field == 'car_table3' ) {
						$a = array();
						$a[] = array('td1' => '자차(면책금제도)',
							'td2' => '사고 건당 고객부담금 최대 30만원 ( 30만원 미만 발생시 실비납부 )'); 
					}

					$s = $a ;
				}

				break;
			default : 
				$s = isset($this->row[$field])? $this->row[$field] : '';
				break;
		}

		return $s;
	}

	function insert($row)
	{
		$q = '
			INSERT INTO car_list
				(	company,	car_name,	car_price,	car_img, 
					car_table1,	car_table2,		car_table3, 
					car_detail_img,	orderby,	is_view,
					reg_date,	car_list_img,	car_list_con,	country)
			VALUES
				( :company,		:car_name,	:car_price,	:car_img, 
				  :car_table1,	:car_table2,	:car_table3, 
				  :car_detail_img,	:orderby,	:is_view,
				  :reg_date,	:car_list_img,	:car_list_con,	:country)
			';

		$stmt = db()->prepare($q);
		$stmt->bindValue(':country',	A::str($row, 'country'));
		$stmt->bindValue(':company',	A::str($row, 'company'));
		$stmt->bindValue(':car_name',	A::str($row, 'car_name'));
		$stmt->bindValue(':car_price',	A::str($row, 'car_price'));
		$stmt->bindValue(':car_img',	A::str($row, 'car_img'));
		$stmt->bindValue(':car_table1',	A::str($row, 'car_table1'));
		$stmt->bindValue(':car_table2',	A::str($row, 'car_table2'));
		$stmt->bindValue(':car_table3',	A::str($row, 'car_table3'));
		$stmt->bindValue(':car_detail_img',	A::str($row, 'car_detail_img'));
		$stmt->bindValue(':orderby',	A::str($row, 'orderby'), PDO::PARAM_INT);
		$stmt->bindValue(':is_view',	A::str($row, 'is_view', 'N'));
		$stmt->bindValue(':reg_date',	date('Y-m-d H:i:s'));
		
		$stmt->bindValue(':car_list_img',	A::str($row, 'car_list_img'));
		$stmt->bindValue(':car_list_con',	A::str($row, 'car_list_con'));

		stmtExecute($stmt);
		return db()->lastInsertId();
	}

	function update($field, $value)
	{
		$seq = $this->data('car_seq');
		if(!is_numeric($seq))
		{
			exitJs(__FILE__.':: LINE '.__LINE__);
		}

		$q = '
			UPDATE car_list
			SET '.$field.' = :value
			WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':value', $value);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);
		
		
		$this->copy_dmz_rendcar($seq);
	}
	
	function update_cnt($cnt = 1 )
	{
		$seq = $this->data('car_seq');
		if(!is_numeric($seq))
		{
			exitJs(__FILE__.':: LINE '.__LINE__);
		}
		
		if(!is_numeric($cnt))
		{
			$cnt = 1 ;
		}
		

		$q = '
			UPDATE car_list
			SET user_update_cnt = user_update_cnt + '.$cnt.'
			WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);
	}
	
	function delete_update_cnt_zero()
	{
		$q = '
			DELETE FROM car_list WHERE IFNULL(user_update_cnt,0) = 0
			';
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	
	
	private function copy_dmz_rendcar($seq)
	{
		return ; // 안씀
		if( !is_numeric($seq) ) return ;
		
		$q = '
			DELETE FROM [DMZ_DB].[RentcarUser].[dbo].car_list 
			WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);
		
		$q = '
			insert into [DMZ_DB].[RentcarUser].[dbo].car_list 
			select * from [dbo].car_list WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);
	}
	
	
	
	
	function del_car($seq)
	{
		if( !is_numeric($seq) ) return ;
		
		$this->setRow($seq);

		$arr_img_field = array(
			 'car_img'
			,'car_list_img'
			,'car_table_img'
			,'car_detail_img'
		);
		foreach( $arr_img_field as $f ){
			$path = $_SERVER['DOCUMENT_ROOT'].$this->data($f);
			if( is_file($path) ){
				unlink($path);
			}
		}

		
		$q = '
			DELETE FROM car_list
			WHERE car_seq = :car_seq
			';
		$stmt = db()->prepare($q);
		$stmt->bindValue(':car_seq', $seq, PDO::PARAM_INT);

		stmtExecute($stmt);
	}
}
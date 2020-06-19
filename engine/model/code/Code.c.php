<?php

define('_db_site_code_', _db_fix_.'site_code') ; 


/**
 * 코드로 저장된 데이타 가져오기 <br />
 * DB : my_vatech_code 테이블 이용<br />
 * 싱글톤 방법
 *
 * @author 윤진우
 */
class Code {
	static $codeGroup = array() ;
	static $codeGroupList = array() ;
	
	/**
	 * code_name 리스트 가져오기
	 * 
	 * @return array code_key => row
	 */
	static function getCodeNameList()
	{
		$q = '
			SELECT code_name, count(code_name) as cnt FROM '._db_site_code_.'
			GROUP BY code_name
			ORDER BY code_name ' ;
		
		$stmt = db()->prepare($q);
		
		stmtExecute($stmt);
		$a = array();
		while($r = $stmt->fetch(pdo::FETCH_ASSOC)){
			$a[$r['code_name']] = $r['cnt'] ;			
		}
		return $a;
	}
	
	/**
	 * code_name 으로 저장된 데이타 가져오기
	 * 
	 * @param string code_name 값
	 * @return array code_key => row
	 */
	static function getCode($code, $is_use = 'Y' )
	{
		if( isset(self::$codeGroup[$code]) ) return self::$codeGroup[$code];
		
		$q = '
			SELECT * FROM '._db_site_code_.'
			WHERE code_name = :code_name 
			AND is_use = :is_use
			ORDER BY code_sort ' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code);
		$stmt->bindValue(':is_use', $is_use);
		
		stmtExecute($stmt);
		$a = array();
		while($r = $stmt->fetch(pdo::FETCH_ASSOC)){
			$a[$r['code_key']] = $r ;
		}
		
		self::$codeGroup[$code] = $a ;		
		return self::$codeGroup[$code] ;
	}
	
	
	/**
	 * code_name 으로 저장된 데이타 가져오기
	 * 
	 * @param string code_name 값
	 * @return array code_key => row
	 */
	static function getCodeAll($code)
	{
		if( isset(self::$codeGroup[$code]) ) return self::$codeGroup[$code];
		
		$q = '
			SELECT * FROM '._db_site_code_.'
			WHERE code_name = :code_name 
			ORDER BY code_sort ' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code);
		
		stmtExecute($stmt);
		$a = array();
		while($r = $stmt->fetch(pdo::FETCH_ASSOC)){
			$a[$r['code_key']] = $r ;
		}
		
		self::$codeGroup[$code] = $a ;		
		return self::$codeGroup[$code] ;
	}
	
	/**
	 * code_name 으로 저장된 데이타 가져오기
	 * 
	 * @param string code_name 값
	 * @return array code_key => row
	 */
	static function getCodeAssoc($code, $key_field = 'code_value')
	{
		if( isset(self::$codeGroup[$code.'_assoc']) ) return self::$codeGroup[$code.'_assoc'];
		
		$q = '
			SELECT * FROM '._db_site_code_.'
			WHERE code_name = :code_name 
			ORDER BY code_sort ' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code);
		
		stmtExecute($stmt);
		$a = array();
		while($r = $stmt->fetch(pdo::FETCH_ASSOC))
			$a[$r[$key_field]] = $r ;
		
		self::$codeGroup[$code.'_assoc'] = $a ;		
		return self::$codeGroup[$code.'_assoc'] ;
	}
	
	
	/**
	 * code_name 으로 저장된 데이타 가져오기
	 * 
	 * @param string code_name 값
	 * @return array code_key => row
	 */
	static function getCodeKV($code,$key="code_key",$field="code_value")
	{
		$codeGroupKey = $code.'_'.$key.':'.$field ;
		if( isset(self::$codeGroup[$codeGroupKey]) ) return self::$codeGroup[$codeGroupKey];
		
		$arr = self::getCode($code);
		$a = array() ;
		foreach( $arr as $k => $r)
			$a[$r[$key]] = $r[$field] ;
		
		self::$codeGroup[$codeGroupKey] = $a ;		
		return self::$codeGroup[$codeGroupKey] ;
	}
	
	/**
	 * code_name 으로 저장된 데이타 가져오기
	 * 
	 * @param string code_name 값
	 * @return string code_value
	 */
	static function getCodeStr($code,$field="code_value")
	{
		// 다시 작업하지.... getCode 이용하는걸로다가..
		if( isset(self::$codeGroup[$code.'_'.$field]) ) return self::$codeGroup[$code.'_'.$field];
		
		$q = '
			SELECT code_key,'.$field.' FROM '._db_site_code_.'
			WHERE code_name = :code_name 
			ORDER BY code_sort LIMIT 1' ;
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code);
		
		stmtExecute($stmt);
		$r = $stmt->fetch(PDO::FETCH_ASSOC);
		
		self::$codeGroup[$code.'_'.$field] = $r[$field] ;		
		return self::$codeGroup[$code.'_'.$field] ;
	}
	
	/**
	 * code_name 으로 데이타 저장하기
	 * 
	 * @param string code_name 값
	 * @param string code_value 값
	 */
	static function setCodeStr($code_name,$code_value)
	{
		$q = '
			UPDATE '._db_site_code_.'
				SET code_value = :code_value
			WHERE code_name = :code_name 
			AND code_key = 1 LIMIT 1' ;
		
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code_name);
		$stmt->bindValue(':code_value', $code_value);
		
		stmtExecute($stmt);
	}
	
	/**
	 * code_name 으로 데이타 저장하기
	 * 
	 * @param string code_name 값
	 * @param string code_value 값
	 */
	static function setCode($code_name,$code_value,$code_value2='',$code_key=1,$is_use='',$code_sort='')
	{
		$f = '';
		if( is_numeric($code_sort) )
			$f .= ', code_sort = '.$code_sort.' ';
		if( $is_use == 'Y' || $is_use == 'N' )
			$f .= ', is_use = \''.$is_use.'\' ';
		if( !empty($code_value2) )
			$f .= ', code_value2 = \''.addslashes ($code_value2).'\' ';
		
		
		$q = '
		REPLACE INTO '._db_site_code_.'
			SET code_value = :code_value
			, code_name = :code_name 
			, code_key = :code_key
			'.$f.' ' ;
		
		
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':code_name', $code_name);
		$stmt->bindValue(':code_value', $code_value);
		if( !is_numeric($code_key) ) $code_key = 1 ;
		$stmt->bindValue(':code_key', $code_key, PDO::PARAM_INT);
		
		stmtExecute($stmt);
	}
	
	/**
	 * code_name 으로 데이타 저장하기
	 * 
	 * @param string code_name 값
	 * @param string code_value 값
	 */
	static function getCodeValueFind($code,$field)
	{
		$field = trim($field);
		$s = '';
		foreach( self::getCode($code) as $k => $row ){
			if( $row['code_value'] == $field )
				$s = $row['code_value2'] ;
		}
		return $s;
	}
}

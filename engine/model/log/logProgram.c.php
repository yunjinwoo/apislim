<?php
/**CREATE TABLE `jin_log_program` (
	`my_log_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`log_type` VARCHAR(100) NULL DEFAULT NULL,
	`log` VARCHAR(2000) NULL DEFAULT NULL,
	`msg` TEXT NULL,
	`page_url` VARCHAR(200) NULL DEFAULT NULL,
	`refer_url` VARCHAR(1000) NULL DEFAULT NULL,
	`reg_ip` VARCHAR(20) NULL DEFAULT NULL,
	`http_agent` VARCHAR(200) NULL DEFAULT NULL,
	`reg_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`my_log_idx`),
	INDEX `log_type` (`log_type`)
)
COMMENT='프로그램 실행오류 정보'
COLLATE='utf8_general_ci'
;



 */
class logProgram
{
	static $table = _db_log_program_;
	static function putError($msg){
		$a = debug_backtrace();
		$log  = 'LINE '.$a[0]['line'].' '.$a[0]['file'].PHP_EOL;
		$log .= 'LINE '.$a[1]['line'].' '.$a[1]['file'] ;

		self::put('error', $msg.PHP_EOL.$log);
	}
	
	static function put($log_type, $msg, $log = ''){
		
		$q = ' INSERT INTO '.self::$table
				. ' SET '
				. '	log_type = :log_type '
				. ',log = :log '
				. ',reg_ip	 = :reg_ip '				
				. ',http_agent	 = :http_agent '
				
				. ',page_url = :page_url '
				. ',refer_url	 = :refer_url '
				
				. ',msg	 = :msg ' ;
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':log_type', $log_type);
		$stmt->bindValue(':log', $log);
		$stmt->bindValue(':reg_ip', A::str($_SERVER, 'REMOTE_ADDR'));
		$stmt->bindValue(':http_agent', A::str($_SERVER, 'HTTP_USER_AGENT'));
		$stmt->bindValue(':page_url', A::str($_SERVER, 'REQUEST_URI') );
		$stmt->bindValue(':refer_url', A::str($_SERVER, 'HTTP_REFERER') );
		$stmt->bindValue(':msg', $msg);
		
		
		stmtExecute($stmt);
		
		return db()->lastInsertId();
	}
	
	static function delete($time = '-2 month'){
		
		$reg_date = date('Y-m-d H:i:s', strtotime($time));
		
		$q = ' DELETE FROM '.self::$table
				. ' WHERE reg_date < \''.$reg_date.'\' ';
		
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
		
	}
	
	
	static function getRows($log_type, $limit = '50'){
		
		$q = ' SELECT * FROM '.self::$table. '
			WHERE log_type = :log_type
			ORDER BY my_log_idx desc
			LIMIT :limit
			
			' ;
		
		$stmt = db()->prepare($q);
		$stmt->bindValue(':log_type', $log_type);
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		
		
		stmtExecute($stmt);
		
		return $stmt->fetchAll();
	}
}




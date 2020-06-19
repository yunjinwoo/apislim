<?php
class dbConnector extends PDO
{	
	function getStatement( $sql )
	{
		return $this->prepare($sql) ;
	}
	
	function getList( $sql , $executeArr = array() )
	{
		$stm = $this->getStatement($sql) ;
		
		$stm->execute($executeArr);
		return $stm->fetchAll();		
	}
	
	function exec_($q)
	{
		$startTime = microtime(true) ;
		$stmt = db()->prepare($q);
		stmtExecute($stmt);
	}
	
	// http://www.php.net/manual/en/pdo.begintransaction.php#109753
    protected $transactionCounter = 0; 
    function beginTransaction() 
    { 
        if(!$this->transactionCounter++) 
            return parent::beginTransaction(); 
       return $this->transactionCounter >= 0; 
    } 

    function commit() 
    { 
       if(!--$this->transactionCounter) 
           return parent::commit(); 
       return $this->transactionCounter >= 0; 
    } 

    function rollback() 
    { 
        if($this->transactionCounter >= 0) 
        { 
            $this->transactionCounter = 0; 
            return parent::rollback(); 
        } 
        $this->transactionCounter = 0; 
        return false; 
    } 
	
}


function stmtExecute( &$stmt , $aParam = array() )
{
	try{
		//VarGroup::addStr('stmtExecute-count', VarGroup::getStr('stmtExecute-count')+1 );
		if( count($aParam) >= 1 )
			$stmt->execute($aParam) ;
		else
			$stmt->execute() ;
		if( $stmt->errorCode() != '00000' )
		{
			//print_r( $stmt->errorInfo() );
			//console::$logCnt = 3;
			//console::error( print_r( $stmt->errorInfo() , true ) );
			$result = array() ;
			$result['result'] = '9999';
			$put = 0;
			$err_list = debug_backtrace();
			
//			foreach( $err_list as $k => $r ){
//				if( isset($r['object']) ){
//					unset($r['object']);
//				}
//				
//				$err_list[$k] = $r;
//			}
			$msg = str_replace("\n", "\r\n", print_r( $stmt->errorInfo() , true ).print_r($err_list,true));
			if( _error_msg_print_ ){
				jslog(print_r( $err_list , true ));
				jslog(print_r( $stmt->errorInfo() , true ));
			}
			
			$put = logProgram::put('query', $msg."\r\n");
			exitJs('관리자에게 문의 바랍니다.['.$put.']['.VarGroup::getStr('stmtExecute-count').']');
			
			//ErrorMsg::exitError(9999);
		}
	}catch(Exception $e){
		pre($e->getMessage());
		pre(debug_backtrace());
	}
}

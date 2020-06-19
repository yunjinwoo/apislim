<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Advice
 *
 * @author Administrator
 */
class AdviceDayList {
    
	function getCntByTeam($like_date, $end_date = '')
	{
		if( empty($like_date) ){ return array(); }
		$w = '';
		if( empty($end_date) ){
			$w = ' yyyymmdd LIKE \''.$like_date.'%\' ';
		}else{
			$w = ' yyyymmdd BETWEEN \''.$like_date.'%\' AND \''.$end_date.'%\' ';
		}
		
		if( !empty($w) ){
			$w = ' WHERE '.$w ;
		}
		
		$q = '
			SELECT 
				sum(db_cnt) as totalCnt
			,	sum(ok_price) as priceTotal
			,	sum(sun_price) as sunTotal
			,	team_seq
			FROM '._LINKED_ANALYSIS_.'advice_day_list
			'.$w.'
			GROUP BY team_seq
			ORDER BY team_seq
			' ;
		
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while( $a = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$ret[$a['team_seq']] = $a ;
		}
		
		return $ret;
	}
	
	function getDay($day = '')
	{
		$day = F::date($day);
		$where = 'CONVERT(CHAR(10), yyyymmdd, 23) = \''.$day.'\'';
		return $this->getList($where);
	}
	
	function getMonth( $day = '' )
	{
		$day = F::date($day);	
		$where = 'CONVERT(CHAR(7), yyyymmdd, 23) = \''.substr($day,0,7).'\'';
		
		return $this->getList($where);
	}
	
	private function getList( $where )
	{
		$q = '
			SELECT * 
			FROM '._LINKED_ANALYSIS_.'advice_day_list
			WHERE '.$where.'
			order by yyyymmdd,ok_price
			' ;
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while( $a = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$ret[$a['list_seq']] = $a ;
		}
		
		return $ret;
	}
	
	function getCntByAdminId($start_date = '', $end_date = '')
	{
		$w = array();
		if( F::isDate($start_date ) ){
			$w[] = ' yyyymmdd >= \''.$start_date.'\' ';
		}
		if( F::isDate($start_date ) ){
			$w[] = ' yyyymmdd <= \''.$end_date.'\' ';
		}
		
		if( count($w) >= 1 ){
			$w = ' WHERE '.implode(' AND ', $w) ;
		}else{ $w = ''; }
		
		$q = '
			SELECT 
				sum(db_cnt) as totalCnt
			,	sum(ok_price) as priceTotal
			,	admin_id
			FROM '._LINKED_ANALYSIS_.'advice_day_list
			'.$w.'
			GROUP BY admin_id
			ORDER BY admin_id
			' ;
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while( $a = $stmt->fetch(PDO::FETCH_ASSOC) )
		{
			$ret[$a['admin_id']] = $a ;
		}
		
		return $ret;
	}
	
}

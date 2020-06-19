<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Where
 *
 * @author Administrator
 */
class Where {
	protected $aWhere;
			
	function addWhere($field, $value, $isInt = false)
	{
		// 클래스마다 바뀔 예정이다...
		switch ($field)
		{
			default: 
				if( $isInt ){
					$this->aWhere[$field] = ' '.$field.' = '.$value.' ' ; 
				}else{
					$this->aWhere[$field] = ' '.$field.' = \''.$value.'\'' ; 
				}
				break;
		}
	}
	
	function addWhereStr($field, $value)
	{
		$this->aWhere[$field] = ' '.$field.' '.$value.' ' ; 
	}
	
	function getWhere( $pix = ' WHERE ' )
	{
		if( count($this->aWhere) >= 1 ){
			return $pix.implode(' AND ', $this->aWhere);
		}else{
			return '';
		}
		
	}
}

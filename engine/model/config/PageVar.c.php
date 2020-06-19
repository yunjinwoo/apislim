<?php


Class PageVar
{
	protected $page_size = 10 ;
	protected $list_size = 10 ;
	protected $log = '';
			
	function __construct($page_size = 0,$list_size = 0) {
		$this->page_size = (is_numeric($list_size) && $list_size > 0) ? $page_size : 7;
		$this->list_size = (is_numeric($list_size) && $list_size > 0) ? $list_size : 10 ;
	}
	
	/**
	 * 로그 기록
	 * @return string 
	 */
	function getLog(){ return $this->log;}
	
	/**
	 * 페이지 수 반환
	 * @return int 페이지 수
	 */
	function getInfoPageSize(){ return $this->page_size;}
	
	/**
	 * 리스트 수 반환
	 * @return int 리스트 수
	 */
	function getInfoListSize(){ return $this->list_size;}
	
	
	/**
	 * 페이지 수 설정
	 * @param int 페이지 수
	 */
	function setInfoPageSize($page_size){ $this->page_size = $page_size;}
	
	/**
	 * 리스트 수 설정
	 * @param int 리스트 수
	 */
	function setInfoListSize($list_size){ $this->list_size = $list_size;;}
}
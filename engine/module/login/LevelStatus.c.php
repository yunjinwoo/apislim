<?php

/**
 * Description of LevelStatus
 *
 * @author Administrator
 */
class LevelStatus {
	private $teamOwnerIdList = array();
	public $isTeam = false;
	public $isChife = false;
	public $isDirector = false;
	public $isPresident = false;
	
	function __construct($user_id = '')
	{
		$adminList = new adminList;
//		$tmp = $adminList->getTeamOwnerList();
//		foreach( $tmp as $k => $arr ){
//			$this->teamOwnerIdList[$arr['admin_id']] = $k;
//		}
		if( !empty($user_id) ){
			$this->levelSet($user_id);
		}
	}
	
	function isLevelAdmin(){
		
		$admin_group = member_row_view('admin_group');
		return $admin_group == 'admin' ;
		
		if( $this->isTeam === true 
				|| $this->isChife === true 
				|| $this->isDirector === true 
				|| $this->isPresident === true ){
			return true;
		}else{
			return false;
		}
	}
	
	function isLevel(){
		$admin_group = member_row_view('admin_group');
		return $admin_group == 'admin' || $admin_group == 'dist';
		
		if( $this->isTeam === true 
				|| $this->isChife === true 
				|| $this->isDirector === true 
				|| $this->isPresident === true ){
			return true;
		}else{
			return false;
		}
	}
	
	
	function isChifeUp(){
		if(  $this->isChife === true 
				|| $this->isDirector === true 
				|| $this->isPresident === true ){
			return true;
		}else{
			return false;
		}
	}
	
	function isDirectorUp(){
		if(  $this->isDirector === true 
				|| $this->isPresident === true ){
			return true;
		}else{
			return false;
		}
	}
	
	function levelSet($user_id)
	{
		$this->isTeam = false;
		foreach( $this->teamOwnerIdList as $id => $teamSeq ){
			if( $user_id == $id ){
				$this->isTeam = true;
				break;
			}
		}
		$this->isTeam = true;
		
		if( $user_id == 'jshuh' || $user_id == 'jinwoo' ){
			$this->isChife = true;
		}else{
			$this->isChife = false;
		}
				
		if( $user_id == 'thkim' ){
			$this->isDirector = true;
		}else{
			$this->isDirector = false;
		}		
		
		if( $user_id == 'sckim' ){
			$this->isPresident = true;
		}else{
			$this->isPresident = false;
		}
	}
}

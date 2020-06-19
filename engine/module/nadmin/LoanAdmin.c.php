<?php

class LoanAdmin extends ListCommon {
	protected $list = array();
	protected $link;
	
	function __construct() {
		$this->link = _LINKED_ADMIN_;
	}
	
	
	function getList($status = '0' )
	{
		if( count($this->list)>=1 ){
			return $this->list;
		}
		if( $status == '0' || $status = '1' ){ 
			$this->addWhere('tiDstatus', $status);
		}
		
		$q = '
			SELECT * FROM '.$this->link.'tbAdmin
			' . $this->getWhere().' ORDER BY vcName ' ;
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		while($a = $stmt->fetch() )
		{
			$this->list[$a['vcAdminID']] = $a;
		}
		
		return $this->list;
	}
	
	function data($team_seq, $field)
	{
		if( !isset($this->list[$team_seq]) ){
			return '';
		}
		$s = '';
		switch ($field)
		{
			case 'team_seq' : case 'seq' : 
				$s = $this->list[$team_seq]['iTeamSeq'];
				break;
			case 'name' : case 'team_name' : 
			default :
				$s = $this->list[$team_seq]['vcName'];
		}
		return $s;
	}

	function addWhereTeamGroup($team_group){
		$this->addWhereStr('iTeamSeq', ' IN (
			SELECT iTeamSeq FROM '.$this->link.'tbTeam
			WHERE iTeamGroup = '.$team_group.' ) ');
	}
	
	function addWhereTeamGroup_sales(){
		$this->addWhereStr('iTeamSeq', ' IN (
			SELECT iTeamSeq FROM '.$this->link.'tbTeam
			WHERE ISNULL(iTeamGroup, 0) <> 0 ) ');
	}
}

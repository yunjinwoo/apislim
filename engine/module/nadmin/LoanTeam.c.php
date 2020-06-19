<?php

class LoanTeam extends ListCommon {
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
			SELECT * FROM '.$this->link.'tbTeam
			' . $this->getWhere().' ORDER BY vcName ' ;
		//pre($q);
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		while($a = $stmt->fetch() )
		{
			$this->list[$a['iTeamSeq']] = $a;
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
}

<?php
/**
 * Description of Advice
 *
 * @author Administrator
 */
class LoanAdvice extends ListCommon {
	public $field ;
	protected $aList ;
	protected $link;
	
	function __construct() {
		$this->link = _LINKED_ADMIN_;
	}
	
	
	function addWhereTeamGroup($team_group){
		$this->addWhereStr('d.iTeamSeq', ' IN (
			SELECT iTeamSeq FROM '.$this->link.'tbTeam
			WHERE iTeamGroup = '.$team_group.' ) ');
	}
	
	function addWhereTeamGroup_sales(){
		$this->addWhereStr('d.iTeamSeq', ' IN (
			SELECT iTeamSeq FROM '.$this->link.'tbTeam
			WHERE ISNULL(iTeamGroup,0) <> 0 ) ');
	}
	/**
	 * 2015-01-02 chart.php 그리기 용으로 추가함
	 */
	function getCntByTeam()
	{
		$q = '
			SELECT 
				count(a.iAdviceSeq) as cnt, 
				iTeamSeq as team_seq
			FROM '.$this->link.'tbAdvice a 
			JOIN '.$this->link.'tbAdviceDetail d
			ON a.iAdviceSeq = d.iAdviceSeq
			'.$this->getWhere().'
			GROUP BY iTeamSeq
		';
		//pre($q);
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch(PDO::FETCH_ASSOC))
		{						
			$ret[$a['team_seq']] = $a['cnt'];
		}
		
		return $ret;
	}
	
	/**
	 * 2015-01-07 chart.php 그리기 용으로 추가함
	 */
	function getCntByUser()
	{
		$q = '
			SELECT cnt,admin_id,team_seq,vcName as admin_name
			FROM '.$this->link.'tbAdmin b
			LEFT JOIN (
				SELECT
						count(a.iAdviceSeq) as cnt, 
						vcAdminID as admin_id,
						iTeamSeq as team_seq
				FROM '.$this->link.'tbAdvice a 
				JOIN '.$this->link.'tbAdviceDetail d
				ON a.iAdviceSeq = d.iAdviceSeq
				'.$this->getWhere().'
				GROUP BY vcAdminID,iTeamSeq ) a
			ON a.admin_id = b.vcAdminID
			-- WHERE b.tiDstatus=0
			order by team_seq, admin_id
		';
		//pre($q);
		$stmt = msdb()->prepare($q);
		stmtExecute($stmt);
		
		$ret = array();
		while($a = $stmt->fetch(PDO::FETCH_ASSOC))
		{						
			$ret[$a['admin_id']] = $a;
		}
		
		return $ret;
	}
}

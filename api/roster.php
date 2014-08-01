<?php

include_once("base.php");

class Roster extends Base
{
	public $teamID;
	public $year;

	public function __construct()
	{
		parent::__construct();
		$this->setTeam();
		$this->setYear();
		$this->setPlayerList();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function setTeam() 
	{
		if(isset($_GET['term'])) {
			$this->teamID = $_GET['term'];
		} else {
			$this->teamID = 0;
		}
	}

	public function setYear() 
	{
		if(isset($_GET['year'])) {
			$this->year = $_GET['year'];
		} else {
			$this->year = date("Y");
		}
	}

	public function setPlayerList()
	{
		if($this->teamID > 0) {
			$strTeamClause = "TeamID = " . $this->teamID . " AND ";
		} else {
			$strTeamClause = "TeamID != 17 AND ";
		}
		$sql = "SELECT p.ID AS PlayerID, CONCAT(p.FirstName,' ',p.LastName) AS PlayerName, p.Position, p.RosterNumber, p.Height_Feet, p.Height_Inches, p.Birthplace, p.Citizenship, p.Weight, p.DOB, m.TeamID, SUM(IF(m.TimeOff>0,1,0)) AS GP, SUM(IF(m.TimeOff>0,IF(m.TimeOn=0,1,0),0)) AS GS, SUM(TimeOff-TimeOn) AS Minutes, SUM(Ejected) AS Ejections, MAX(IF(m.TimeOff>0,MatchTime,0)) AS LastPlayed "
		."FROM tbl_gameminutes m "
		."INNER JOIN tbl_games g ON m.GameID = g.ID "
		."INNER JOIN tbl_players p ON m.PlayerID = p.ID "
		."WHERE " . $strTeamClause . " YEAR(MatchTime) = " . $this->year . " "
		."GROUP BY m.PlayerID, m.TeamID "
		."ORDER BY Weight DESC, Minutes DESC, LastName, FirstName";
	
		$rs = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

		while($row = mysqli_fetch_assoc($rs)) {
			$this->recordset[] = $row;
		}
	}

}

$obj = new Roster;

?>

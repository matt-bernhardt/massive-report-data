<?php

include_once("base.php");

class Career extends Base
{

	public $playerID;

	public function __construct()
	{
		parent::__construct();
		$this->getData();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function getData()
	{
		$sql = "SELECT g.ID, DATE_FORMAT(g.MatchTime,'%c/%e/%y') AS MatchDate, UNIX_TIMESTAMP(g.MatchTime) AS SortDate, YEAR(MatchTime) AS MatchYear, DATE_FORMAT(g.MatchTime,'%l:%i %p') AS MatchHour, DATE_FORMAT(g.MatchTime,'%H%i') AS SortHour, t.matchtype, t.CompetitionType, IF(g.HTeamID = 11,'Home','Away') AS HomeAway, IF(g.HTeamID = 11,a.teamname,h.teamname) AS Opponent, "
		."IF(g.HTeamID = 11, "
		."	IF(g.HScore>g.AScore,'Win',IF(g.HScore=g.AScore,'Draw','Loss')), "
		."	IF(g.AScore>g.HScore,'Win',IF(g.HScore=g.AScore,'Draw','Loss')) "
		.") AS Result, "
		."h.teamname AS HomeTeam, g.HTeamID, g.HScore, a.teamname AS AwayTeam, g.ATeamID, g.AScore, g.VenueID, v.VenueName, Duration, Attendance "
		."FROM tbl_games g " 
		."INNER JOIN tbl_teams h ON g.HTeamID = h.ID "
		."INNER JOIN tbl_teams a ON g.ATeamID = a.ID "
		."INNER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID "
		."INNER JOIN tbl_venues v ON g.VenueID = v.ID "
		."WHERE (HTeamID = 11 OR ATeamID = 11) AND t.official = 1 "
		."ORDER BY MatchTime ASC";
		
		$rs = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

		while($row = mysqli_fetch_assoc($rs)) {
			$this->recordset[] = $row;
		}
	}

}

$obj = new Career;

?>

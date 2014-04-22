<?php

include_once("base.php");

class Career extends Base
{

	public $playerID;
	public $games = array();

	public function __construct()
	{
		parent::__construct();
		$this->setPlayer();
		$this->setDateRange();
		$this->renderJSON();		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function setPlayer() 
	{
		if(isset($_GET['term'])) {
			$this->playerID = $_GET['term'];
		} else {
			$this->playerID = '1556';
		}
	}

	public function setDateRange()
	{
		$sql = "SELECT m.GameID, m.TeamID, m.TimeOn, m.TimeOff, m.Ejected, DATE_FORMAT(g.MatchTime,'%c/%e/%y') AS MatchTime "
		."FROM tbl_gameminutes m "
		."LEFT OUTER JOIN tbl_games g ON m.GameID = g.ID "
		."WHERE PlayerID = " . $this->playerID . " "
		."ORDER BY g.MatchTime ASC";
		
		$rs = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

		while($row = mysqli_fetch_assoc($rs)) {
			$this->recordset[] = $row;
		}
	}

}

$obj = new Career;

?>

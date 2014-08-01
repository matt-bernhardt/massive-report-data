<?php

include_once("base.php");

class Roster extends Base
{

	public $playerTerm;

	public function __construct()
	{
		parent::__construct();
		$this->setPlayer();
		$this->setDateRange();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function setPlayer() 
	{
		if(isset($_GET['term'])) {
			$this->playerTerm = '%' . $_GET['term'] . '%';
		} else {
			$this->playerTerm = '%';
		}
	}

	public function setDateRange()
	{
		$sql = "SELECT ID AS value, CONCAT(FirstName,' ',LastName) AS label, Position, Citizenship, DOB "
			."FROM tbl_players "
			."WHERE LastName LIKE '" . $this->playerTerm ."' "
			."ORDER BY LastName ASC, FirstName ASC";
		
		$rs = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

		while($row = mysqli_fetch_assoc($rs)) {
			$this->recordset[] = $row;
		}
	}

}

$obj = new Roster;

?>

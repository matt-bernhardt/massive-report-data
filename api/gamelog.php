<?php

class Career
{
	private $debug = false;
	private $connection;
	public $playerID;
	public $games = array();

	/**
	 * Not sure if I should be running everything through the constructor function, but it seems easy enough to do
	 */
	public function __construct()
	{
		$this->setDebug();
		$this->dbOpen();
		$this->setPlayer();
		$this->setDateRange();
		$this->renderJSON();
	}

	public function __destruct()
	{
		$this->dbClose();
	}

	protected function dbClose()
	{
		mysqli_close($this->connection);		
	}

	protected function dbOpen() 
	{
		if ($_SERVER['HTTP_HOST'] == 'mrdata') {
			//local settings
			$db_host = 'localhost:3306';
			$db_database = 'scouting';
			$db_username = 'root';
			$db_password = 'jvct120sx';
		} else {
			//production settings
			$db_host = 'massivereportdatacom.fatcowmysql.com:3306';
			$db_database = 'scouting';
			$db_username = 'website';
			$db_password = 'gEya6hak';
		}
		//connect to db
		$this->connection = mysqli_connect($db_host, $db_username, $db_password);
		mysqli_select_db($this->connection,$db_database) or die('Could not select database');
	}

	public function renderJSON() 
	{
		$data = json_encode($this->games);

		if(array_key_exists('callback',$_GET)) {

			header('Content-Type: text/javascript; charset=utf8');
		    header('Access-Control-Allow-Origin: http://www.example.com/');
		    header('Access-Control-Max-Age: 3628800');
		    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

		    $callback = $_GET['callback'];
		    echo $callback.'('.$data.');';		

		} else {
			header('Content-Type: application/json; charset=utf8');

			echo $data;
		}

	}

	public function setDebug()
	{
		if(isset($_GET['debug'])) {
			$this->debug = true;
		} else {
			$this->debug = false;
		}
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
			$this->games[] = $row;
		}
	}

	public function setProp($new)
	{
		$this->prop1 = $new;
	}

	public function getProp()
	{
		return $this->prop1 . "<br/>";
	}
}

$obj = new Career;

?>

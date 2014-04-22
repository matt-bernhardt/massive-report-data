<?php

class Base
{
	private $debug = false;
	protected $connection;
	protected $recordset = array();

	public function __construct()
	{
		$this->setDebug();
		$this->dbOpen();
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
		$data = json_encode($this->recordset);

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
}

?>

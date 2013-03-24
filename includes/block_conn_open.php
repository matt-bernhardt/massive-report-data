<?php
	//look up where we're connecting from
	$strHost = $_SERVER['HTTP_HOST'];

	if ($strHost == 'mrdata') {
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
	$connection = mysql_connect($db_host, $db_username, $db_password);
	mysql_select_db('scouting',$connection) or die('Could not select database');
?>
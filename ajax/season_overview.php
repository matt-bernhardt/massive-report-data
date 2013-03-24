<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT Summary ";
	$sql .= "FROM tbl_yearsummaries ";
	$sql .= "WHERE TeamID = 11 AND Year = ".$intSeason;
	$summary = mysql_query($sql, $connection) or die(mysql_error());

	while($row = @mysql_fetch_array($summary,MYSQL_ASSOC)) {
		print $row['Summary'];
	}

	include_once("../includes/block_conn_close.php");
?>

<?php
	if(isset($_GET['player'])){
		$intPlayerID = $_GET['player'];
	} else {
		$intPlayerID = 2938;
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT FirstName, LastName ";
	$sql .= "FROM tbl_players ";
	$sql .= "WHERE ID = ".$intPlayerID;

	$player = mysql_query($sql, $connection) or die(mysql_error());

	while($row = @mysql_fetch_array($player,MYSQL_ASSOC)) {
		$strPlayerName = $row['FirstName']." ".$row['LastName'];
		$strLineChartImage = $row['LastName']."_".$row['FirstName']."_".$intPlayerID.".gif";
	}

?>
<p><img id="linechart" src="/images/line_charts/<?php echo $strLineChartImage; ?>" alt="Line Chart of games for <?php echo $strPlayerName; ?>" /></p>
<?php
	include_once("../includes/block_conn_open.php");
?>
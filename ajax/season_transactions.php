<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}
	include_once("../includes/block_conn_open.php");

	$sql = "SELECT DATE_FORMAT(c.SigningDate,'%b %D') AS DisplayDate, c.LastTeamID AS PrevTeamID, pt.teamname AS PrevTeam, c.TeamID AS CurrTeamID, ct.teamname AS CurrTeam, c.PlayerID, concat(p.FirstName,' ',p.LastName) AS PlayerName, s.ContractName, Notes ";
	$sql .= "FROM tbl_contracts c ";
	$sql .= "LEFT OUTER JOIN tbl_teams pt ON c.LastTeamID = pt.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams ct ON c.TeamID = ct.ID ";
	$sql .= "INNER JOIN tbl_players p ON c.PlayerID = p.ID ";
	$sql .= "INNER JOIN lkp_contractstatus s ON c.ContractType = s.ID ";
	$sql .= "WHERE (c.LastTeamID = 11 OR c.TeamID = 11) AND Year(SigningDate) = ".$intSeason." ";
	$sql .= "ORDER BY c.SigningDate ASC";
	$transactions = mysql_query($sql, $connection) or die(mysql_error());
?>
<table>
<thead>
	<tr>
		<th scope="col">Date</th>
		<th scope="col">Player</th>
		<th scope="col">Contract</th>
		<th scope="col">Notes</th>
	</tr>
</thead>
<tbody>	
<?php
	while($row = @mysql_fetch_array($transactions,MYSQL_ASSOC)) {
		if($row['PrevTeamID'] == 11 && $row['CurrTeamID'] == 11){
			// Player stays with Columbus
			$strTransaction = "<br />New Contract";
		} elseif($row['PrevTeamID'] == 11) {
			// Player left Columbus
			if($row['CurrTeamID'] == 0) {
				// unknown / unspecified team
				$strTransaction = '';
			} else {
				$strTransaction = "<br />To ".$row['CurrTeam'];
			}
		} elseif($row['CurrTeamID'] == 11) {
			// Player joined Columbus
			if($row['PrevTeamID'] == 0){
				// unknown / unspecified team
				$strTransaction = '';
			} else {
				$strTransaction = "<br />From ".$row['PrevTeam'];
			}
		} else {
			// Not sure
			$strTransaction = '';
		}

/*
		if($row['PrevTeam']<>"Columbus Crew"){
			$strTransaction = "<br />From ".$row['PrevTeam'];
		} elseif($row['CurrTeam']<>"Columbus Crew") {
			$strTransaction = "<br />To ".$row['CurrTeam'];
		} else {
			$strTransaction = "";
		}
*/
?>
	<tr>
		<td nowrap="nowrap"><?php print $row['DisplayDate']; ?></td>
		<td nowrap="nowrap"><a href="/player/<?php print $row['PlayerID']; ?>"><?php print $row['PlayerName']; ?></a><?php print $strTransaction; ?></td>
		<td nowrap="nowrap"><?php print $row['ContractName']; ?></td>
		<td><?php print $row['Notes']; ?></td>
	</tr>
<?php
	}

	include_once("../includes/block_conn_close.php");
?>

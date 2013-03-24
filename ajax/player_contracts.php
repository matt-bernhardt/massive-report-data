<?php
	if(isset($_GET['player'])){
		$intPlayerID = $_GET['player'];
	} else {
		$intPlayerID = 2938;
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT c.TeamID, t.Teamname, c.RosterNumber, TimeStamp(c.SigningDate) AS SortDate, date_format(c.SigningDate,'%c/%e/%y') AS SigningDate , s.ContractName, c.ContractLength, c.TransferFee, c.Notes, c.RelatedID, c.DesignatedPlayer ";
	$sql .= "FROM tbl_contracts c ";
	$sql .= "LEFT OUTER JOIN tbl_teams t ON c.TeamID = t.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams pt ON c.LastTeamID = pt.ID ";
	$sql .= "LEFT OUTER JOIN lkp_contractstatus s ON c.ContractType = s.ID ";
	$sql .= "WHERE playerID = ".$intPlayerID." ";
	$sql .= "ORDER BY SortDate ASC";

	$contracts = mysql_query($sql, $connection) or die(mysql_error());
?>
<table>
	<thead>
		<tr>
			<th scope="col">Date</th>
			<th scope="col">Team</th>
			<th scope="col">Notes</th>
		</tr>
	</thead>
	<tbody>
<?php
	while($row = @mysql_fetch_array($contracts,MYSQL_ASSOC)) {
?>
		<tr>
			<td><?php print $row['SigningDate']; ?></td>
			<td><?php print $row['Teamname']; ?></td>
			<td><?php print $row['Notes']; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<?php
	include_once("../includes/block_conn_open.php");
?>
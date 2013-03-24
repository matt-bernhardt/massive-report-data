<?php
	if(isset($_GET['player'])){
		$intPlayerID = $_GET['player'];
	} else {
		$intPlayerID = 2938;
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT s.Year, s.TeamID, t.teamname, t.team3ltr, p.MatchType, s.GP, s.GS, s.Min, s.G, s.A, s.Sht, s.SOG, s.OF, s.CK, s.FC, s.FS, s.C, s.E ";
	$sql .= 'FROM tbl_statsyear s ';
	$sql .= 'LEFT OUTER JOIN tbl_teams t ON s.TeamID = t.ID ';
	$sql .= "LEFT OUTER JOIN lkp_matchtypes p on s.CompetitionID = p.ID ";
	$sql .= 'WHERE s.PlayerID = '.$intPlayerID.' ';
	$sql .= 'ORDER BY Year, teamname, Competition';

	$statsyear = mysql_query($sql, $connection) or die(mysql_error());
?>
<table class="statsyear">
	<thead>
		<tr>
			<th scope="col" class="tablet monitor phone">Year</th>
			<th scope="col" class="tablet monitor">Team</th>
			<th scope="col" class="phone">Team</th>
			<th scope="col" class="phone tablet monitor" title="Competition">Comp</th>
			<th scope="col" class="phone tablet monitor" title="Games Played">GP</th>
			<th scope="col" class="tablet monitor" title="Games Started">GS</th>
			<th scope="col" class="tablet monitor" title="Minutes Played">Min</th>
			<th scope="col" class="phone tablet monitor" title="Goals">G</th>
			<th scope="col" class="tablet monitor" title="Assists">A</th>
			<th scope="col" class="monitor" title="Shots">Sht</th>
			<th scope="col" class="monitor" title="Shots On Goal">SOG</th>
			<th scope="col" class="monitor" title="Offside">Off</th>
			<th scope="col" class="monitor" title="Corner Kicks">CK</th>
			<th scope="col" class="monitor" title="Fouls Committed">FC</th>
			<th scope="col" class="monitor" title="Fouls Suffered">FS</th>
			<th scope="col" class="monitor" title="Cautions">C</th>
			<th scope="col" class="monitor" title="Ejections">E</th>
		</tr>
	</thead>
	<tbody>
<?php
	$boolNewPlayer = FALSE;
	$strLastYear = '';
	$boolOdd = TRUE;
	while($row = @mysql_fetch_array($statsyear,MYSQL_ASSOC)) {
		switch($row['MatchType']) {
			case 'MLS League':
				$strComp = "League";
				break;
			case 'MLS Playoffs':
				$strComp = "Playoffs";
				break;
			case 'US Open Cup':
				$strComp = "Open Cup";
				break;
			case 'CONCACAF Champions Cup':
				$strComp = "International";
				break;
			case 'CONCACAF Giants Cup':
				$strComp = "International";
				break;
			case 'CONCACAF Champions League':
				$strComp = "International";
				break;
			default:
				$strComp = "";
		}
		if($strLastYear<>$row['Year']){
			$strClass="newplayer";
			if($boolOdd){
				$strClass .= " even";
				$boolOdd = FALSE;
			} else {
				$strClass .= " odd";
				$boolOdd = TRUE;
			}
		} else {
			$strClass="";
			if($boolOdd){
				$strClass = "odd";
			} else {
				$strClass = "even";
			}
		}
		

?>
		<tr class="<?php print $strClass; ?>">
			<td class="tablet monitor phone"><span class="name"><?php print $row['Year']; ?></span></td>
			<td class="tablet monitor"><?php print $row['teamname']; ?></td>
			<td class="phone"><?php print $row['team3ltr']; ?></td>
			<td class="phone tablet monitor"><?php print $strComp; ?></td>
			<td class="phone tablet monitor"><?php print $row['GP']; ?></td>
			<td class="tablet monitor"><?php print $row['GS']; ?></td>
			<td class="tablet monitor"><?php print $row['Min']; ?></td>
			<td class="phone tablet monitor" title="Goals"><?php print $row['G']; ?></td>
			<td class="tablet monitor" title="Assists"><?php print $row['A']; ?></td>
			<td class="monitor" title="Shots"><?php print $row['Sht']; ?></td>
			<td class="monitor" title="Shots On Goal"><?php print $row['SOG']; ?></td>
			<td class="monitor" title="Offside"><?php print $row['OF']; ?></td>
			<td class="monitor" title="Corner Kicks"><?php print $row['CK']; ?></td>
			<td class="monitor" title="Fouls Committed"><?php print $row['FC']; ?></td>
			<td class="monitor" title="Fouls Suffered"><?php print $row['FS']; ?></td>
			<td class="monitor" title="Cautions"><?php print $row['C']; ?></td>
			<td class="monitor" title="Ejections"><?php print $row['E']; ?></td>
		</tr>
<?php
		$strLastYear = $row['Year'];
	}
?>
	</tbody>
</table>


<table>
	<thead>
		<tr>
			<th scope="col">Year</th>
			<th scope="col">Team</th>
			<th scope="col">Competition</th>
			<th scope="col">GP</th>
			<th scope="col">GS</th>
			<th scope="col">Min</th>
			<th scope="col">G</th>
		</tr>
	</thead>
	<tbody>
<?php
	while($row = @mysql_fetch_array($statsyear,MYSQL_ASSOC)) {
?>
		<tr>
			<td><?php print $row['Year']; ?></td>
			<td><?php print $row['teamname']; ?></td>
			<td><?php print $row['MatchType']; ?></td>
			<td><?php print $row['GP']; ?></td>
			<td><?php print $row['GS']; ?></td>
			<td><?php print $row['Min']; ?></td>
			<td><?php print $row['G']; ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<script type="text/javascript">
		$(function() {
			$(".newplayer").prev().addClass("last");
		});
</script>
<?php
	include_once("../includes/block_conn_open.php");
?>
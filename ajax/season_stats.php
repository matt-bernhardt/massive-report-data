<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}
	include_once("../includes/block_conn_open.php");

	$sql = "SELECT s.PlayerID, p.LastName, concat(p.FirstName,' ',p.LastName) AS PlayerName, p.Position, t.MatchType, s.GP, s.GS, s.Min, s.G, s.A, s.Sht, s.SOG, s.OF, s.CK, s.FC, s.FS, s.C, s.E ";
	$sql .= "FROM tbl_statsyear s ";
	$sql .= "LEFT OUTER JOIN tbl_players p ON s.PlayerID = p.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t on s.CompetitionID = t.ID ";
	$sql .= "WHERE TeamID = 11 AND Year = ".$intSeason." AND Position <> 'Goalkeeper' ";
	$sql .= "ORDER BY p.LastName, p.FirstName, Competition";
	$statlines = mysql_query($sql, $connection) or die(mysql_error());
?>
<div id="container" class="clearfix">
	<table class="statsyear">
		<thead>
			<tr>
				<th scope="col" class="tablet monitor">Player Name</th>
				<th scope="col" class="phone">Player</th>
				<th scope="col" class="tablet monitor">Position</th>
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
	$strLastPlayer = '';
	$boolOdd = TRUE;
	while($row = @mysql_fetch_array($statlines,MYSQL_ASSOC)) {
		switch($row['Position']) {
			case 'Goalkeeper':
				$strPos = "GK";
				break;
			case 'Defender':
				$strPos = "D";
				break;
			case 'Midfielder':
				$strPos = "M";
				break;
			case 'Forward':
				$strPos = "F";
				break;
		}

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
		if($strLastPlayer<>$row['PlayerName']){
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
				<td class="tablet monitor"><span class="name"><a href="/player/<?php print $row['PlayerID']; ?>"><?php print $row['PlayerName']; ?></a></span></td>
				<td class="phone"><span class="name"><a href="/player/<?php print $row['PlayerID']; ?>"><?php print $row['LastName']; ?> (<?php print $strPos; ?>)</a></span></td>
				<td class="tablet monitor"><span class="position"><?php print $row['Position']; ?></span></td>
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
		$strLastPlayer = $row['PlayerName'];
	}
?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
		$(function() {
			$(".newplayer").prev().addClass("last");
		});
</script>
<?php
	include_once("../includes/block_conn_close.php");
?>

<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}
	include_once("../includes/block_conn_open.php");

	$sql = "SELECT g.ID, date_format(MatchTime,'%Y') AS MatchYear, date_format(MatchTime,'%c/%e/%Y') AS MatchDate, h.teamname, a.teamname, t.id, t.MatchType, if(HteamID = 11, a.team3ltr, concat('@ ',h.team3ltr)	) AS Opp, Concat(HScore,' - ',Ascore) AS Score ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE (HteamID = 11 OR AteamID = 11) AND Year(MatchTime) = ".$intSeason." ";
	$sql .= "ORDER BY MatchTime ASC";
	$games = mysql_query($sql, $connection) or die(mysql_error());
?>
<div id="container" class="clearfix" style="margin-left: -10px; width:980px;">
<?php
	while($row = @mysql_fetch_array($games,MYSQL_ASSOC)) {

		switch($row['id']) {
			case '21':
				$strCompetition = "league";
				$strShowCompetition = "League";
				break;
			case '4':
				$strCompetition = "playoff";
				$strShowCompetition = "Playoffs";
				break;
			case '5':
				$strCompetition = "playoff";
				$strShowCompetition = "MLS Cup";
				break;
			case '14':
			case '22':
				$strCompetition = "opencup";
				$strShowCompetition = "Open Cup";
				break;
			case '23':
				$strCompetition = "international";
				$strShowCompetition = "Champs Lg";
				break;
			case '24':
				$strCompetition = "international";
				$strShowCompetition = "Giants Cup";
				break;
			case '25':
				$strCompetition = "international";
				$strShowCompetition = "Champs Cup";
				break;
			case '1':
				$strCompetition = "friendly";
				$strShowCompetition = "Friendly";
				break;
			case '3':
				$strCompetition = "preseason";
				$strShowCompetition = "Preseason";
				break;
			default:
				$strCompetition = "other";
				$strShowCompetition = "Other";
		}
		$intSeason = $row['MatchYear'];
?>
	<a class="element <?php print $strCompetition;?>" data-symbol="<?php print $row['Opp']; ?>" data-category="transition" href="/game/<?php print $row['ID']; ?>">
		<span class="competition"><?php print $strShowCompetition; ?></span>
		<span class="opponent"><?php print $row['Opp']; ?></span>
		<span class="score"><?php print $row['Score']; ?></span>
		<span class="date"><?php print $row['MatchDate']; ?></span>
	</a>
<?php
	}
?>
</div>
<?php
	include_once("../includes/block_conn_close.php");
?>

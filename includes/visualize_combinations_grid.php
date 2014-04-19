<?php

	$cellPairing = '';
	$cellPlus = '';
	$cellMinus = '';

	if(isset($_GET['mode'])){
		$strMode = $_GET['mode'];
	} else {
		$strMode = "together";
	}

	$arrPlayers = array();
	$arrPlayersAlt = array();
	$sql = "SELECT p.ID, CONCAT(FirstName,' ',LastName) AS PlayerName, LastName "
	."FROM tbl_players p "
	."INNER JOIN lnk_players_combos l ON p.ID = l.PlayerID "
	."INNER JOIN tbl_combos_stats s ON l.ComboID = s.ComboID "
	."WHERE Year = ".$intYear." "
	."GROUP BY p.ID "
	."ORDER BY LastName, FirstName";
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)){
		$arrPlayers[] = $row;
		$arrPlayersAlt[] = $row;
	}

	// Get overall season data (minutes, goals for, goals against)
	$intMinutes = 0;
	$intGoalsFor = 0;
	$intGoalsAgainst = 0;
	$sql = "SELECT IF(g.HTeamID = 11,g.HScore,g.AScore) AS CrewScore, IF(g.HTeamID = 11,g.AScore,g.HScore) AS OppScore, MAX(m.TimeOff) AS GameLength "
	."FROM tbl_games g "
	."INNER JOIN tbl_gameminutes m ON g.ID = m.GameID "
	."WHERE "
	."	(YEAR(MatchTime) = ".$intYear." AND MatchTime < NOW()) "
	."	AND "
	." 	g.MatchTypeID = 21 "
	."	AND "
	."	(HTeamID = 11 OR ATeamID = 11) "
	."GROUP BY g.ID "
	."ORDER BY MatchTime ASC";
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)){
		$intMinutes += (int) $row['GameLength'];
		$intGoalsFor += (int) $row['CrewScore'];
		$intGoalsAgainst += (int) $row['OppScore'];
	}
	$intRateFor = intval($intMinutes*10 / $intGoalsFor)/10;
	$intRateAgainst = intval($intMinutes*10 / $intGoalsAgainst)/10;

	if($strMode=="together"){
		$arrPlayersAlt = array_reverse($arrPlayersAlt);
	}
?>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  		<style type="text/css">
			body {
				font-family: 'Arial', sans-serif;
			}
			#container {
				width: 90%;
				margin: 1em auto;
				background: #fff;
				border: 1px solid #ddd;
				padding: 1em;
			}
			#container h1 {
				font-size: 1.5em;
				font-weight: bold;
				margin-bottom: 0.66em;
			}
			#container p {
				margin-bottom: 1em;
			}

			nav {
				padding-bottom: 0.5em;
				border-bottom: 1px solid #ddd;
				margin-bottom: 0.5em;
			}
			nav ul li {
				display: inline;
				padding: 0.5em;
			}
			nav ul li.active {
				background: #ccc;
			}
			nav ul li a {
				text-decoration: none;
			}
			nav ul li a:hover,
			nav ul li a:focus {
				text-decoration: underline;
			}
			strong {
				font-weight: bold;
			}
			table#grid {
				table-layout: fixed;
				padding-bottom: 0.5em;
				border-bottom: 1px solid #ddd;
				margin-bottom: 0.5em;
				font-size: 12px;
				background-color: #404040;
				border-collapse: separate;
			}
			table#grid thead th {
				height: 115px;
			}
			table#grid th div {
				position: absolute;
				text-align:left;
				width: 115px;
				-webkit-transform: rotate(-90deg) translateX(-50%);
				-webkit-transform-origin: 1em 100%;
				-o-transform: rotate(-90deg);
				transform: rotate(90deg);
			}
			table#grid tbody th {
				padding: 0.5em;
			}
			table#grid td {
				min-width: 2em;
				padding: 0.4em;
			}
			table#grid .callout {
				background: #ccc;
			}
			table#grid tbody tr:hover th {
				background: #eceaea; 
				color: #1b1b1b;
			}
			table#grid td:hover {
				background: #ccc;
				border: 1px solid #ccc !important;
			}
			table#grid tbody tr:hover a {
				color: #4C4301;
			}
		</style>
<?php

	$longModeSwitcher = '<ul class="inline clearfix">'
	.'<li';
	if($strMode=="separate"){ $longModeSwitcher .= ' class="active"';}
	$longModeSwitcher .= '><a href="?mode=separate">Separate</a></li>'
	.'<li';
	if($strMode=="together"){ $longModeSwitcher .= ' class="active"';}
	$longModeSwitcher .= '><a href="?mode=together">Together</a></li>'
	."</ul>";

	switch($strMode){
		case 'separate': 
			$longPageContent .= '<p>The grid lists how often each player has appeared <strong>separate</strong> from his teammates. The player listed on each row is illustrated, separate from the player in each column.</p>';
			break;
		default:
			$longPageContent .= '<p>The grid lists how often each player has appeared <strong>together</strong> with his teammates, or how often each pair of players has both been unused. Please see each cell\'s tooltip for more information.</p>';
			break;
	}

	$longPageContent .= '<p class="data-summary highlight">Over <span class="minutes">'.$intMinutes.'</span> minutes, the Crew:<br>'
	.'Scored '.$intGoalsFor.' goals - one every <span class="ratefor">'.$intRateFor.'</span> minutes<br>'
	.'Allowed '.$intGoalsAgainst.' goals - one every <span class="rateagainst">'.$intRateAgainst.'</span> minutes'
	.'</p>';
	// Data table
	$longPageContent .= '<table id="grid"><thead><th>'.$longModeSwitcher.'</th>';	

	for($x=0;$x<count($arrPlayersAlt);$x++){
		$longPageContent .= '<th id="'.$arrPlayersAlt[$x]["ID"].'" scope="col"><div>'.$arrPlayersAlt[$x]["LastName"].'</div></th>';
	}

	$longPageContent .= '</thead><tbody>';

	for($x=0;$x<count($arrPlayers);$x++){
		// get combinations for this player
		$arrCombos = array();
		$sql = "SELECT s.ComboID, GROUP_CONCAT(l.PlayerID,'_',Exclude ORDER BY PlayerID ASC) AS Combo, s.Min, s.Plus, s.Minus "
		."FROM tbl_combos_stats s "
		."INNER JOIN lnk_players_combos l ON s.ComboID = l.ComboID "
		."WHERE s.Year = ".$intYear." "
		."GROUP BY s.ComboID "
		."HAVING Combo LIKE '%".$arrPlayers[$x]["ID"]."_%'";
		// echo $sql;
		$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
		while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)){
			$arrCombos[] = $row;
		}

		$longPageContent .= '<tr>';
		$longPageContent .= '<th scope="row"><a href="/player/'.$arrPlayers[$x]["ID"].'">'.$arrPlayers[$x]["PlayerName"].'</a></th>';

		for($y=0;$y<count($arrPlayersAlt);$y++){
			// build fakey Combo ID ($strCombo) for this cell
			if($strMode=="separate"){
				$boolX = 1;
				$boolY = 0;
				$titleComparator = " separate from ";
			} else {
				if($arrPlayers[$x]["LastName"]<$arrPlayersAlt[$y]["LastName"]){
					$boolX = 0;
					$boolY = 0;
					$titleComparator = " playing with ";
				} else {
					$boolX = 1;
					$boolY = 1;
					$titleComparator = " on the bench with ";
				}
			}
			if($arrPlayers[$x]["ID"]<$arrPlayersAlt[$y]["ID"]) {
				$strCombo = $arrPlayers[$x]["ID"]."_".$boolX.",".$arrPlayersAlt[$y]["ID"]."_".$boolY;
			} else {
				$strCombo = $arrPlayersAlt[$y]["ID"]."_".$boolY.",".$arrPlayers[$x]["ID"]."_".$boolX;
			}

			$cellClass = $arrPlayersAlt[$y]["ID"];
			if($arrPlayers[$x]["ID"]!=$arrPlayersAlt[$y]["ID"]){
				for($i=0;$i<count($arrCombos);$i++){
					if($arrCombos[$i]["Combo"]==$strCombo){
						$cellPairing = $arrPlayers[$x]["LastName"].$titleComparator.$arrPlayersAlt[$y]["LastName"];
						$cellPlus = $arrCombos[$i]["Plus"];
						$cellMinus = $arrCombos[$i]["Minus"];
						$cellValue = $arrCombos[$i]["Min"];
						$title = ' title="a"';
						break; // break out of the for loop (break does not apply to if statements)
					}
				}
			} else {
				$cellPairing = '';
				$cellPlus = '';
				$cellMinus = '';
				$cellValue = '';
				$title = '';
			}

			// Don't do anything for conditions less than 10% of the season
			if($intMinutes*0.1 < $cellValue){

				// compare team value ($intRateFor) to pair value (cellValue/cellPlus)
				// $intRateFor = intval($intMinutes*10 / $intGoalsFor)/10;
				// player value smaller = better
				$intPairRateFor = intval($cellValue*10 / $cellPlus)/10; // player rate
				$intPairDifference = ($intRateFor / $intPairRateFor) - 1;

				$cellGreen = intval($intPairDifference*100); // (22)
				$cellComment = $cellGreen;
				$cellGreen = intval($cellGreen/5)*5; // (20)
				$cellGreen = 64 - $cellGreen; // (44)
				if($cellGreen < 0) {$cellGreen = 0;}
				$cellBG = $cellGreen.",64,64";

				// $cellGreen = ($cellPlus > 0) ? 128-intval($cellValue / $cellPlus) : (int)128-$intRateFor;
				// if ($cellGreen < 0) { $cellGreen = 0;}
				$cellText = "#fff";
			} else {
				$cellComment = "";
				$cellGreen = 0;
				$cellText = "#bfbfbf";
				$cellBG = "27,27,27";
			}
			$longPageContent .=  '<td class="'.$cellClass.'" data-comment="'.$cellComment.'" data-pairing="'.$cellPairing.'" data-plus="'.$cellPlus.'" data-minus="'.$cellMinus.'" '.$title.' style="background-color: rgb('.$cellBG.');border:1px solid rgb('.$cellBG.');color:'.$cellText.'">';
			$longPageContent .=  $cellValue;
			$longPageContent .=  '</td>';

		}
		$longPageContent .=  '</tr>';
	}

	$longPageContent .= '</tbody></table>';
?>				

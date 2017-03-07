<?php

	// everything is coming in via intopponentID - need to split from there
	if($intOpponentID == ''){
		// need to add an opponent browse
		$intOpponentID = 12;
	}
	if(strpos($intOpponentID,"?") != false) {
		$intOpponentID = substr($intOpponentID,0,strpos($intOpponentID,"?"));
	}

	$boolHome = false;
	$boolAway = false;
	if(isset($_GET['home'])){
		$boolHome = true;
	}
	if(isset($_GET['away'])){
		$boolAway = true;
	}
	if(isset($_GET['startyear'])){
		$intStartYear = $_GET['startyear'];
	} else {
		$intStartYear = 1996;
	}
	if(isset($_GET['endyear'])){
		$intEndYear = $_GET['endyear'];
	} else {
		$intEndYear = date('Y');
		$intEndYear = 2015;
	}
	
	$strHomeSelected = '';
	$strAwaySelected = '';
	if($boolHome && $boolAway){
		$strWhereClause = "((HTeamID = 11 AND ATeamID = ".$intOpponentID.") OR (HTeamID = ".$intOpponentID." AND ATeamID = 11))";
		$strHomeSelected = ' checked="checked"';
		$strAwaySelected = ' checked="checked"';
	} elseif($boolHome) {
		$strWhereClause = "(HTeamID = 11 AND ATeamID = ".$intOpponentID.")";
		$strHomeSelected = ' checked="checked"';
	} elseif($boolAway) {
		$strWhereClause = "(HTeamID = ".$intOpponentID." AND ATeamID = 11)";
		$strAwaySelected = ' checked="checked"';
	} else {
		$strWhereClause = "((HTeamID = 11 AND ATeamID = ".$intOpponentID.") OR (HTeamID = ".$intOpponentID." AND ATeamID = 11))";
		$strHomeSelected = ' checked="checked"';
		$strAwaySelected = ' checked="checked"';
	}

	$strMenuGroup = "";

	$strJavascript = "block_opponent.js";

	$boolInputSlider = TRUE;
	
	//Get Opponent Name
	$sql = "SELECT teamname FROM tbl_teams WHERE ID = ".$intOpponentID;
	// echo $sql;
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while( $row = mysqli_fetch_assoc( $rs ) ) {
		$strOpponentName = $row['teamname'];
	}

	$longPageContent = "<h1>".$strOpponentName."</h1>";
	$strPageTitle .= " > ".$strOpponentName;
	
	$sql = "SELECT g.ID, MatchTime, DATE_FORMAT(MatchTime,'%c/%e/%y') AS MatchDate, HTeamID, HScore, ATeamID, AScore, Attendance, VenueID, VenueName, MatchTypeID, m.MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes m ON g.MatchTypeID = m.id ";
	$sql .= "LEFT OUTER JOIN tbl_venues v ON g.VenueID = v.ID ";
	$sql .= "WHERE ".$strWhereClause." AND MatchTime < now() AND m.Official = 1 AND year(MatchTime) >= ".$intStartYear." AND year(MatchTime) <= ".$intEndYear." ";
	$sql .= "ORDER BY MatchTime ASC";
	// echo $sql;
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent .= '<section id="controls">';
	$longPageContent .= '<p>Use the controls below to filter the games visible in this summary.</p>';
	$longPageContent .= '<form action="" id="filter-season">';
	$longPageContent .= '<fieldset>';
	$longPageContent .= '<input type="submit" value="Refresh">';
	$longPageContent .= '<label for="home">Home: <input id="home" type="checkbox" name="home" '.$strHomeSelected.'/></label>';
	$longPageContent .= '<label for="away">Away: <input id="away" type="checkbox" name="away" '.$strAwaySelected.'/></label>';
	$longPageContent .= '<label for="startyear">Start Date:';
	$longPageContent .= '<select id="startyear" name="startyear">';
	$intDefault = 1996;
	for($x=1996;$x<=2015;$x++){
		if($x==$intStartYear){
			$strSelected = ' selected="selected"';
		} else {
			$strSelected = '';
		}
		$longPageContent .= '<option'.$strSelected.'>'.$x.'</option>';
	}
	$longPageContent .= '</select>';
	$longPageContent .= '</label>';
	$longPageContent .= '<label for="endyear">End Date:';
	$longPageContent .= '<select id="endyear" name="endyear">';
	$intDefault = 2015;
	for($x=1996;$x<=2015;$x++){
		if($x==$intEndYear){
			$strSelected = ' selected="selected"';
		} else {
			$strSelected = '';
		}
		$longPageContent .= '<option'.$strSelected.'>'.$x.'</option>';
	}
	$longPageContent .= '</select>';
	$longPageContent .= '</label>';
	$longPageContent .= '</fieldset>';
	$longPageContent .= '</form>';
	// list of games
	$longPageContent .= '<div>';
	
	$longResultsTable = '<h2>Games</h2>';
	$longResultsTable .= '<table><thead><tr>';
	$longResultsTable .= '<th scope="col">Date</th>';
	$longResultsTable .= '<th scope="col">Competition</th>';
	$longResultsTable .= '<th scope="col">Stadium</th>';
	$longResultsTable .= '<th scope="col">Result</th>';
	$longResultsTable .= '<th scope="col">Attendance</th>';
	$longResultsTable .= '</tr></thead><tbody>';
	$intWin = 0;
	$intTie = 0;
	$intLoss = 0;
	$intGF = 0;
	$intGA = 0;
	$strGameList = '';
	while( $row = mysqli_fetch_assoc( $rs ) ) {
		$HTeam = $row['HTeamID'];
		$HScore = $row['HScore'];
		$AScore = $row['AScore'];
		if($HTeam == 11){
			$strResult = $HScore.' - '.$AScore;
		} else {
			$strResult = $AScore.' - '.$HScore;
		}
		
		if($HScore>$AScore){ // Home win
			if($HTeam == 11) {
				$strResult .= ' Win';
				$intWin++;
				$intGF = $intGF + $HScore;
				$intGA = $intGA + $AScore;
			} else {
				$strResult .= ' Loss';
				$intLoss++;
				$intGF = $intGF + $AScore;
				$intGA = $intGA + $HScore;
			}
		} elseif ($AScore>$HScore) { // Away win
			if($HTeam == 11) {
				$strResult .= ' Loss';
				$intLoss++;
				$intGF = $intGF + $HScore;
				$intGA = $intGA + $AScore;
			} else {
				$strResult .= ' Win';
				$intWin++;
				$intGF = $intGF + $AScore;
				$intGA = $intGA + $HScore;
			}
		} else { // Draw
			$strResult .= ' Tie';			
			$intTie++;
			if($HTeam == 11) {
				$intGF = $intGF + $HScore;
				$intGA = $intGA + $AScore;
			} else {
				$intGF = $intGF + $AScore;
				$intGA = $intGA + $HScore;
			}
		}
		$longResultsTable .= '<tr>';
		$longResultsTable .= '<td><a href="/game/'.$row['ID'].'">'.$row['MatchDate'].'</a></td>';
		$longResultsTable .= '<td>'.$row['MatchType'].'</td>';
		$longResultsTable .= '<td>'.$row['VenueName'].'</td>';
		$longResultsTable .= '<td>'.$strResult.'</td>';
		$longResultsTable .= '<td>'.$row['Attendance'].'</td>';
		$longResultsTable .= '</tr>';
		
		$strGameList .= $row['ID'].',';
	}

	$longResultsTable .= '</tbody></table>';
	
	$longPageContent .= '</div>';
	$longPageContent .= '<h2>Summary Data</h2>';
	$longPageContent .= '<dl>';
	$longPageContent .= '<dt>Record</dt><dd>'.$intWin.' Wins - '.$intTie.' Ties - '.$intLoss.' Lossses</dd>';
	$longPageContent .= '<dt>Goals Scored</dt><dd>'.$intGF.'</dd>';
	$longPageContent .= '<dt>Goals Conceded</dt><dd>'.$intGA.'</dd>';
	$longPageContent .= '</dl>';
	$longPageContent .= '</section>';	
	$longPageContent .= '';
	$longPageContent .= $longResultsTable;

	$strGameList = rtrim($strGameList,',');
	// echo $strGameList.'<br />';
	$sql = "SELECT PlayerID, Position, CONCAT(FirstName,' ',LastName) AS PlayerName, SUM(TimeOff-TimeOn) AS Minutes, SUM(IF(TimeOff>0,1,0)) AS GP, SUM(IF(TimeOn=0,1,0)) AS GS ";
	$sql .= "FROM tbl_gameminutes m ";
	$sql .= "LEFT OUTER JOIN tbl_players p ON m.PlayerID = p.ID ";
	$sql .= "WHERE TeamID = 11 AND TimeOff > 0 AND GameID IN (".$strGameList.") ";
	$sql .= "GROUP BY PlayerID ";
	$sql .= "ORDER BY Minutes DESC";
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$strPlayerList = '<h2>Player Stats</h2>';
	$strPlayerList .= '<table><thead><tr>';
	$strPlayerList .= '<th scope="col">Player</th>';
	$strPlayerList .= '<th scope="col">Position</th>';
	$strPlayerList .= '<th scope="col">GP</th>';
	$strPlayerList .= '<th scope="col">GS</th>';
	$strPlayerList .= '<th scope="col">Minutes</th>';
	$strPlayerList .= '</tr></thead><tbody>';
	while( $row = mysqli_fetch_assoc( $rs ) ) {
		$strPlayerList .= '<tr>';
		$strPlayerList .= '<td><a href="/player/'.$row['PlayerID'].'">'.$row['PlayerName'].'</a></td>';
		$strPlayerList .= '<td>'.$row['Position'].'</td>';
		$strPlayerList .= '<td>'.$row['GP'].'</td>';
		$strPlayerList .= '<td>'.$row['GS'].'</td>';
		$strPlayerList .= '<td>'.$row['Minutes'].'</td>';
		$strPlayerList .= '</tr>';
	}
	$strPlayerList .= '</tbody></table>';
	
	$longPageContent .= $strPlayerList;
	
	$longPageContent .= '<script src="/javascript/selectToUISlider.jQuery.js"></script>';
	$longPageContent .= '<link rel="stylesheet" href="/styles/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />';
	$longPageContent .= '<link rel="Stylesheet" href="/styles/ui.slider.extras.css" type="text/css" />';
	
	?>
<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

	if(count($arrPath)==3){
		$intMonth = $arrPath[1];
		$strMonth = date('F',mktime(0,0,0,$intMonth));
		$intDay = $arrPath[2];
	} else {
		$intMonth = date('n');
		$strMonth = date('F');
		$intDay = date('j');
	}

	// calculate next/previous date
	$diff1Day = 86400;
	$tempDate = mktime(0,0,0,$intMonth,$intDay,"1996");
	$nextDate = $tempDate + $diff1Day;
	$prevDate = $tempDate - $diff1Day;

	$strPageTitle .= " > ".$strMonth." ".$intDay;
	$strMenuGroup = "Dates";

	// Define needed js libraries

	// Games
	$sql = "SELECT g.ID AS GameID, YEAR(MatchTime) AS MatchYear, h.teamname AS HomeTeam, g.HScore, a.teamname AS AwayTeam, g.AScore, t.MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE (g.HteamID = 11 OR g.AteamID = 11) ";
	$sql .= "  AND (DAY(MatchTime) = ".$intDay." AND MONTH(MatchTime) = ".$intMonth.") ";
	$sql .= "ORDER BY MatchTime ASC";

	$dates = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Today in Crew History: '.$strMonth.' '.$intDay.'</h1>';

	// Date navigation
	$longPageContent .= '<div class="stepnav">'
	.'<a class="prev" href="/date/'.date('n/j',$prevDate).'">&lt; '.date('F j',$prevDate).'</a>'
	.'<a class="next" href="/date/'.date('n/j',$nextDate).'">'.date('F j',$nextDate).' &gt;</a>'
	.'</div>';

	$longPageContent .= '<h2 style="clear:both;">Games</h2>';
	$longPageContent .= '<table><thead><tr>';
	$longPageContent .= '<th scope="col">Year</th>';
	$longPageContent .= '<th scope="col">Home</th>';
	$longPageContent .= '<th scope="col">Score</th>';
	$longPageContent .= '<th scope="col">Away</th>';
	$longPageContent .= '<th scope="col">Score</th>';
	$longPageContent .= '<th scope="col">Competition</th>';
	$longPageContent .= '</tr></thead><tbody>';

	while($row = @mysqli_fetch_array($dates,MYSQLI_ASSOC)) {
		$longPageContent .= '<tr>';
		$longPageContent .= '<td><a href="/game/'.$row['GameID'].'">'.$row['MatchYear'].'</a></td>';
		$longPageContent .= '<td>'.$row['HomeTeam'].'</td>';
		$longPageContent .= '<td>'.$row['HScore'].'</td>';
		$longPageContent .= '<td>'.$row['AwayTeam'].'</td>';
		$longPageContent .= '<td>'.$row['AScore'].'</td>';
		$longPageContent .= '<td>'.$row['MatchType'].'</td>';
		$longPageContent .= '</tr>';
	}

	$longPageContent .= '</tbody></table>';

	// Transactions
	$sql = 'SELECT c.PlayerID, concat(p.FirstName," ",p.LastName) AS PlayerName, c.LastTeamID AS OldTeamID, th.teamname AS OldTeam, c.TeamID AS NewTeamID, ta.teamname AS NewTeam, c.SigningDate, c.ContractType, c.Notes ';
	$sql .= 'FROM tbl_contracts c ';
	$sql .= 'LEFT OUTER JOIN tbl_players p ON c.PlayerID = p.ID ';
	$sql .= 'LEFT OUTER JOIN tbl_teams th on c.LastTeamID = th.ID ';
	$sql .= 'LEFT OUTER JOIN tbl_teams ta on c.LastTeamID = ta.ID ';
	$sql .= 'WHERE (LastTeamID = 11 OR TeamID = 11) AND (day(SigningDate) = '.$intDay.' and month(SigningDate) = '.$intMonth.') ';
	$sql .= 'ORDER BY year(SigningDate)';
	$transactions = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$recordset = $transactions->fetch_array();
	$longPageContent .= '<h2>Transactions</h2>';
	if(gettype($recordset) == "array") {
		$longPageContent .= '<table><thead><tr>';
		$longPageContent .= '<th scope="col">Year</th>';
		$longPageContent .= '<th scope="col">Player</th>';
		$longPageContent .= '<th scope="col">Old Team</th>';
		$longPageContent .= '<th scope="col">New Team</th>';
		$longPageContent .= '<th scope="col">Notes</th>';
		$longPageContent .= '</tr></thead><tbody>';
		// first row
		$longPageContent .= '<tr>';
		$longPageContent .= '<td>'.$recordset['SigningDate'].'</td>';
		$longPageContent .= '<td><a href="/player/'.$recordset['PlayerID'].'">'.$recordset['PlayerName'].'</a></td>';
		$longPageContent .= '<td>'.$recordset['OldTeam'].'</td>';
		$longPageContent .= '<td>'.$recordset['NewTeam'].'</td>';
		$longPageContent .= '<td>'.$recordset['Notes'].'</td>';
		$longPageContent .= '</tr>';
		while($row = @mysqli_fetch_array($transactions,MYSQLI_ASSOC)) {
			$longPageContent .= '<tr>';
			$longPageContent .= '<td>'.$row['SigningDate'].'</td>';
			$longPageContent .= '<td><a href="/player/'.$row['PlayerID'].'">'.$row['PlayerName'].'</a></td>';
			$longPageContent .= '<td>'.$row['OldTeam'].'</td>';
			$longPageContent .= '<td>'.$row['NewTeam'].'</td>';
			$longPageContent .= '<td>'.$row['Notes'].'</td>';
			$longPageContent .= '</tr>';
		}
		$longPageContent .= '</tbody></table>';
	} else {
		$longPageContent .= "<p>No transactions on this date.</p>";
	}

	// Birthdays
	$sql = "SELECT ID, YEAR(DOB) AS BirthYear, CONCAT(FirstName,' ',LastName) AS PlayerName, Position ";
	$sql .= "FROM tbl_players p ";
	$sql .= "WHERE (DAY(DOB) = ".$intDay." AND MONTH(DOB) = ".$intMonth.") ";
	$sql .= "ORDER BY Year(DOB) DESC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent .= '<h2>Players</h2>';

	$longPageContent .= '<table><thead><tr>';
	$longPageContent .= '<th scope="col">Year</th>';
	$longPageContent .= '<th scope="col">Player</th>';
	$longPageContent .= '<th scope="col">Position</th>';
	$longPageContent .= '</tr></thead><tbody>';

	while($row = @mysqli_fetch_array($players,MYSQLI_ASSOC)) {
		$longPageContent .= '<tr>';
		$longPageContent .= '<td>'.$row['BirthYear'].'</td>';
		$longPageContent .= '<td><a href="/player/'.$row['ID'].'">'.$row['PlayerName'].'</a></td>';
		$longPageContent .= '<td>'.$row['Position'].'</td>';
		$longPageContent .= '</tr>';
	}

	$longPageContent .= '</tbody></table>';
?>
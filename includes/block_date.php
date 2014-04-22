<?php

	if(count($arrPath)==3){
		$intMonth = $arrPath[1];
		$strMonth = date('F',mktime(0,0,0,$intMonth));
		$intDay = $arrPath[2];
	} else {
		$intMonth = date('n');
		$strMonth = date('F');
		$intDay = date('j');
	}

	$strPageTitle .= " > ".$strMonth." ".$intDay;
	$strMenuGroup = "Dates";

	// Define needed js libraries

	$sql = "SELECT g.ID AS GameID, YEAR(MatchTime) AS MatchYear, h.teamname AS HomeTeam, g.HScore, a.teamname AS AwayTeam, g.AScore, t.MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE (g.HteamID = 11 OR g.AteamID = 11) ";
	$sql .= "  AND (DAY(MatchTime) = ".$intDay." AND MONTH(MatchTime) = ".$intMonth.") ";
	$sql .= "ORDER BY MatchTime ASC";

	$dates = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$sql = "SELECT ID, YEAR(DOB) AS BirthYear, CONCAT(FirstName,' ',LastName) AS PlayerName, Position ";
	$sql .= "FROM tbl_players p ";
	$sql .= "WHERE (DAY(DOB) = ".$intDay." AND MONTH(DOB) = ".$intMonth.") ";
	$sql .= "ORDER BY Year(DOB) DESC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Today in Crew History: '.$strMonth.' '.$intDay.'</h1>';

	$longPageContent .= '<h2>Games</h2>';
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
	$longPageContent .= '<h2>Transactions</h2>';
	$longPageContent .= '<table><thead><tr>';
	$longPageContent .= '<th scope="col">Year</th>';
	$longPageContent .= '<th scope="col">Player</th>';
	$longPageContent .= '<th scope="col">Old Team</th>';
	$longPageContent .= '<th scope="col">New Team</th>';
	$longPageContent .= '<th scope="col">Notes</th>';
	$longPageContent .= '</tr></thead><tbody>';
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



	// Birthdays
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
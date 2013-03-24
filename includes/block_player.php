<?php

	$strMenuGroup = "Players";

	$boolTabs = TRUE;

	if(!isset($intPlayerID)){
		$longPageContent = "Invalid Player";
		$intPlayerID = 0;
	} else {

		$sql = "SELECT FirstName, LastName, Position, College, Height_Feet, Height_Inches, Weight, DOB, date_format(DOB,'%c/%e/%Y') AS Birthdate, Hometown, Citizenship, Bio, Visible ";
		$sql .= "FROM tbl_players ";
		$sql .= "WHERE ID = ".$intPlayerID;

		$player = mysql_query($sql, $connection) or die(mysql_error());

		while($row = @mysql_fetch_array($player,MYSQL_ASSOC)) {
			$strPlayerName = $row['FirstName']." ".$row['LastName'];
			$strLineChartImage = $row['LastName']."_".$row['FirstName']."_".$intPlayerID.".gif";
			$strPosition = $row['Position'];
			$strDOB = new DateTime($row['DOB']);
			$datToday = new DateTime();
			$intPlayerAge = $strDOB->diff($datToday);
			$strBirthdate = $row['Birthdate'];
			$strHometown = $row['Hometown'];
			$strCollege = $row['College'];
			$strHeight = $row['Height_Feet'].'\' '.$row['Height_Inches'].'"';
			$intWeight = $row['Weight'];
			$strCitizenship = $row['Citizenship'];
			$textBio = $row['Bio'];
		}

		$sql = "SELECT c.TeamID, t.Teamname, c.RosterNumber, TimeStamp(c.SigningDate) AS SortDate, date_format(c.SigningDate,'%c/%e/%y') AS SigningDate , s.ContractName, c.ContractLength, c.TransferFee, c.Notes, c.RelatedID, c.DesignatedPlayer ";
		$sql .= "FROM tbl_contracts c ";
		$sql .= "LEFT OUTER JOIN tbl_teams t ON c.TeamID = t.ID ";
		$sql .= "LEFT OUTER JOIN tbl_teams pt ON c.LastTeamID = pt.ID ";
		$sql .= "LEFT OUTER JOIN lkp_contractstatus s ON c.ContractType = s.ID ";
		$sql .= "WHERE playerID = ".$intPlayerID." ";
		$sql .= "ORDER BY SortDate ASC";

		$contracts = mysql_query($sql, $connection) or die(mysql_error());

		$strPageTitle .= " > ".$strPlayerName;

		$longPageContent = '<div class="page_player">';
		$longPageContent .= "<h1>".$strPlayerName."</h1>";
		$longPageContent .= "<p>".$strPosition."</p>";

		$longPageContent .= '<div id="tabs">';

		$longPageContent .= '<ul>';
		$longPageContent .= '<li><a href="#tabs-1">Overview</a></li>';
		$longPageContent .= '<li><a href="#tabs-2">Contracts</a></li>';
		$longPageContent .= '<li><a href="#tabs-3">Game Log</a></li>';
		$longPageContent .= '<li><a href="#tabs-4">Stats</a></li>';
		$longPageContent .= '</ul>';

		// Overview tab
		$longPageContent .= '<div id="tabs-1"><dl>';
		$longPageContent .= '<dt>Citizenship</dt><dd>'.$strCitizenship.'</dd>';
		$longPageContent .= '<dt>Born</dt><dd><a href="/date/'.date('n',strtotime($strBirthdate)).'/'.date('j',strtotime($strBirthdate)).'">'.$strBirthdate.'</a> ('.$intPlayerAge->y.' years old)</dd>';
		if($strHometown){
			$longPageContent .= '<dt>Hometown</dt><dd>'.$strHometown.'</dd>';
		}
		if($strCollege){
			$longPageContent .= '<dt>College</dt><dd>'.$strCollege.'</dd>';
		}
		if($row['Height_Feet']>0) {
			$longPageContent .= '<dt>Height</dt><dd>'.$strHeight.'</dd>';
		}
		if($intWeight) {
			$longPageContent .= '<dt>Weight</dt><dd>'.$intWeight.'</dd>';
		}
		$longPageContent .= '</dl>';
		$longPageContent .= '<div class="bio clearboth">'.$textBio.'</div>';
		$longPageContent .= '</div>';

		// Contracts tab
		$longPageContent .= '<div id="tabs-2">';
		$longPageContent .= '<table><thead><tr><th scope="col">Date</th><th scope="col">Team</th><th scope="col">Notes</th></tr></thead><tbody>';
		while($row = @mysql_fetch_array($contracts,MYSQL_ASSOC)) {
			$longPageContent .= '<tr>';
			$longPageContent .= '<td>'.$row['SigningDate'].'</td>';
			$longPageContent .= '<td>'.$row['Teamname'].'</td>';
			$longPageContent .= '<td>'.$row['Notes'].'</td>';
			$longPageContent .= '</tr>';
		}
		$longPageContent .= '</tbody></table>';
		$longPageContent .= '</div>';

		// Game log tab
		$longPageContent .= '<div id="tabs-3">Coming soon...</div>';

		// Season stats tab
		$sql = 'SELECT s.Year, s.TeamID, t.teamname, s.Competition, s.GP, s.GS, s.Min, s.G ';
		$sql .= 'FROM tbl_statsyear s ';
		$sql .= 'LEFT OUTER JOIN tbl_teams t ON s.TeamID = t.ID ';
		$sql .= 'WHERE s.PlayerID = '.$intPlayerID.' ';
		$sql .= 'ORDER BY Year, teamname, Competition';
		$statsyear = mysql_query($sql, $connection) or die(mysql_error());
		$longPageContent .= '<div id="tabs-4">';
		$longPageContent .= '<table><thead><tr>';
		$longPageContent .= '<th scope="col">Year</th>';
		$longPageContent .= '<th scope="col">Team</th>';
		$longPageContent .= '<th scope="col">Competition</th>';
		$longPageContent .= '<th scope="col">GP</th>';
		$longPageContent .= '<th scope="col">GS</th>';
		$longPageContent .= '<th scope="col">Min</th>';
		$longPageContent .= '<th scope="col">G</th>';
		$longPageContent .= '</tr></thead><tbody>';
		while($row = @mysql_fetch_array($statsyear,MYSQL_ASSOC)) {
			$longPageContent .= '<tr>';
			$longPageContent .= '<td>'.$row['Year'].'</td>';
			$longPageContent .= '<td><a href="/team/'.$row['TeamID'].'">'.$row['teamname'].'</a></td>';
			$longPageContent .= '<td>'.$row['Competition'].'</td>';
			$longPageContent .= '<td>'.$row['GP'].'</td>';
			$longPageContent .= '<td>'.$row['GS'].'</td>';
			$longPageContent .= '<td>'.$row['Min'].'</td>';
			$longPageContent .= '<td>'.$row['G'].'</td>';
			$longPageContent .= '</tr>';
		}
		$longPageContent .= '</tbody></table>';
		$longPageContent .= '</div>';

		$longPageContent .= '</div>';

		$longPageContent .= '</div>';
	}
?>
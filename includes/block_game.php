<?php

	$strMenuGroup = "Games";
	$strPageTitle .= " > Game ".$intGameID;

	// Define needed js libraries


	$sql = "SELECT date_format(MatchTime, '%c/%e/%Y') AS MatchTime, h.TeamID AS HomeID, h.teamname AS HomeTeam, HScore AS HomeScore, a.TeamID AS AwayID, a.teamname AS AwayTeam, AScore AS AwayScore, Attendance, t.MatchType, g.VenueID, v.VenueName "
	."FROM tbl_games g "
	."LEFT OUTER JOIN tbl_team_identities h ON g.HTeamID = h.TeamID "
	."LEFT OUTER JOIN tbl_team_identities a ON g.ATeamID = a.TeamID "
	."LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID "
	."LEFT OUTER JOIN tbl_venues v ON g.VenueID = v.ID "
	."WHERE g.ID = ".$intGameID." AND h.Effective < g.MatchTime AND a.Effective < g.MatchTime "
	."ORDER BY h.Effective DESC, a.Effective DESC "
	."LIMIT 0,1";

	$game = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	// Games with opponents not listed in tbl_team_identities will need alternate/old SQL
	if(mysqli_num_rows($game)==0){
		$sql = "SELECT date_format(MatchTime, '%c/%e/%Y') AS MatchTime, h.ID AS HomeID, h.teamname AS HomeTeam, HScore AS HomeScore, a.ID AS AwayID, a.teamname AS AwayTeam, AScore AS AwayScore, Attendance, t.MatchType, g.VenueID, v.VenueName "
			."FROM tbl_games g "
			."LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID "
			."LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID "
			."LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID "
			."LEFT OUTER JOIN tbl_venues v ON g.VenueID = v.ID "
			."WHERE g.ID = ".$intGameID." "
			."LIMIT 0,1";
		$game = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	}

	while($row = @mysqli_fetch_array($game,MYSQLI_ASSOC)) {
		$datMatchDate = $row['MatchTime'];
		$intHomeID = $row['HomeID'];
		$strHomeTeam = $row['HomeTeam'];
		$intHomeScore = $row['HomeScore'];
		$intAwayID = $row['AwayID'];
		$strAwayTeam = $row['AwayTeam'];
		$intAwayScore = $row['AwayScore'];
		$intAttendance = $row['Attendance'];
		$strMatchType = $row['MatchType'];
		$intVenueID = $row['VenueID'];
		$strVenueName = $row['VenueName'];
	}

	$longPageContent = '<div class="gamedetails" itemscope itemtype="http://schema.org/SportsEvent">';
	$longPageContent .= '<h1 itemprop="name"><span class="home"><span itemprop="performer">'.$strHomeTeam.'</span> ';
	$longPageContent .= '<span class="score">'.$intHomeScore.'</span></span>';
	$longPageContent .= ' - ';
	$longPageContent .= '<span class="away"> <span class="score">'.$intAwayScore.'</span> <span itemprop="performer">'.$strAwayTeam.'</span> ';
	$longPageContent .= '</span>';
	$longPageContent .= '</h1>';
	$longPageContent .= '<p class="matchdate" itemprop="startDate">'.$datMatchDate.'</p>';
	$longPageContent .= '<p class="competition">'.$strMatchType.'</p>';
	$longPageContent .= '<p class="venue" itemprop="location">'.$strVenueName.'</p>';
	$longPageContent .= '<p class="attendance">'.$intAttendance.'</p>';
	$longPageContent .= '</div>';


	$sql = "SELECT PlayerID, TimeOn, TimeOff, Ejected, CONCAT(p.FirstName,' ',p.LastName) AS PlayerName, p.Position ";
	$sql .= "FROM tbl_gameminutes m ";
	$sql .= "LEFT OUTER JOIN tbl_players p ON m.PlayerID = p.ID ";
	$sql .= "WHERE GameID = ".$intGameID." AND TeamID = ".$intHomeID." ";
	$sql .= "ORDER BY TimeOn, (CASE POSITION WHEN 'Goalkeeper' THEN 0 WHEN 'Defender' THEN 1 WHEN 'Midfielder' THEN 2 WHEN 'Forward' THEN 3 END)";
	$home = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$longPageContent .= '<div id="homeroster">';
	if($intHomeID==11){
		$longPageContent .= '<h2>'.$strHomeTeam.'</h2>';
	} else {
		$longPageContent .= '<h2><a href="/opponent/'.$intHomeID.'">'.$strHomeTeam.'</a></h2>';
	}
	$longPageContent .= '<table><thead><tr><th scope="col">Player</th><th scope="col">Position</th><th scope="col">On</th><th scope="col">Off</th></tr></thead>';
	$longPageContent .= '<tbody>';
	while($row = @mysqli_fetch_array($home,MYSQLI_ASSOC)) {
		$longPageContent .= '<tr>';
		$longPageContent .= '<td><a href="/player/'.$row['PlayerID'].'">'.$row['PlayerName'].'</a></td>';
		$longPageContent .= '<td>'.$row['Position'].'</td>';
		$longPageContent .= '<td>'.$row['TimeOn'].'</td>';
		$longPageContent .= '<td>'.$row['TimeOff'];
		if($row['Ejected']){ $longPageContent .= ' (ejected)';}
		$longPageContent .= '</td>';
		$longPageContent .= '</tr>';
	}
	$longPageContent .= '</tbody></table>';
	$longPageContent .= '</div>';

	$sql = "SELECT PlayerID, TimeOn, TimeOff, Ejected, CONCAT(p.FirstName,' ',p.LastName) AS PlayerName, p.Position ";
	$sql .= "FROM tbl_gameminutes m ";
	$sql .= "LEFT OUTER JOIN tbl_players p ON m.PlayerID = p.ID ";
	$sql .= "WHERE GameID = ".$intGameID." AND TeamID = ".$intAwayID." ";
	$sql .= "ORDER BY TimeOn, (CASE POSITION WHEN 'Goalkeeper' THEN 0 WHEN 'Defender' THEN 1 WHEN 'Midfielder' THEN 2 WHEN 'forward' THEN 3 END)";
	$away = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$longPageContent .= '<div id="awayroster">';
	if($intHomeID==11){
		$longPageContent .= '<h2><a href="/opponent/'.$intAwayID.'">'.$strAwayTeam.'</a></h2>';
	} else {
		$longPageContent .= '<h2>'.$strAwayTeam.'</h2>';
	}

	$longPageContent .= '<table><thead><tr><th scope="col">Player</th><th scope="col">Position</th><th scope="col">On</th><th scope="col">Off</th></tr></thead>';
	$longPageContent .= '<tbody>';
	while($row = @mysqli_fetch_array($away,MYSQLI_ASSOC)) {
		$longPageContent .= '<tr>';
		$longPageContent .= '<td><a href="/player/'.$row['PlayerID'].'">'.$row['PlayerName'].'</a></td>';
		$longPageContent .= '<td>'.$row['Position'].'</td>';
		$longPageContent .= '<td>'.$row['TimeOn'].'</td>';
		$longPageContent .= '<td>'.$row['TimeOff'];
		if($row['Ejected']){ $longPageContent .= ' (ejected)'; }
		$longPageContent .= '</td>';
		$longPageContent .= '</tr>';
	}
	$longPageContent .= '</tbody></table>';
	$longPageContent .= '</div>';


	$sql = "select t.Team3ltr AS Team, e.MinuteID, v.Event, p.ID AS PlayerID, concat(p.FirstName,' ',p.LastName) AS PlayerName, e.Notes ";
	$sql .= "from tbl_gameevents e ";
	$sql .= "left outer join tbl_teams t on e.TeamID = t.ID ";
	$sql .= "left outer join lkp_gameevents v on e.Event = v.ID ";
	$sql .= "left outer join tbl_players p on e.PlayerID = p.ID ";
	$sql .= "where GameID = ".$intGameID." ";
	$sql .= "order by MinuteID";
	$events = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$longPageContent .= '<div id="timeline">';
	$longPageContent .= '<h2>Events</h2>';
	$longPageContent .= '<table><thead><tr>';
	$longPageContent .= '<th scope="col">Time</th>';
	$longPageContent .= '<th scope="col">Event</th>';
	$longPageContent .= '<th scope="col">Player</th>';
	$longPageContent .= '<th scope="col">Team</th>';
	$longPageContent .= '<th scope="col">Notes</th>';
	$longPageContent .= '</thead>';
	while($row = @mysqli_fetch_array($events,MYSQLI_ASSOC)) {
		$longPageContent .= '<tr>';
		$longPageContent .= "<td>".$row['MinuteID']."'</td>";
		$longPageContent .= '<td>'.$row['Event'].'</td>';
		$longPageContent .= '<td><a href="/player/'.$row['PlayerID'].'">'.$row['PlayerName'].'</a></td>';
		$longPageContent .= '<td>'.$row['Team'].'</td>';
		$longPageContent .= '<td>'.$row['Notes'].'</td>';		
		$longPageContent .= '</tr>';
	}
	$longPageContent .= '<tbody>';
	$longPageContent .= '';
	$longPageContent .= '';
	$longPageContent .= '';
	$longPageContent .= '</tbody>';
	$longPageContent .= '</table>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="clearboth"></div>';
?>
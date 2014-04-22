<?php
	if(isset($arrPath[2])){
		$strBy = $arrPath[2];
	} else {
		$strBy = 'match';
	}
	
	$strMenuGroup = "Chase";

	$strPageTitle .= " > Points Chase";

	$strJavascript = "visualize_chase.js";

	// Define needed js libraries
	$boolFlot = TRUE;

	if($strBy=='match'){
		$strMatchClass=' class="selected"';
		$strDateClass='';
	} else {
		$strMatchClass='';
		$strDateClass=' class="selected"';
	}
	
	$sql = "SELECT DATE_FORMAT(MatchTime,'%Y') AS Season, DATE_FORMAT(MatchTime,'%c/%e/%Y') AS MatchDate, DAYOFYEAR(MatchTime) AS MatchDayOfYear, h.teamname AS HomeTeam, HScore, a.teamname AS AwayTeam, AScore ";
	$sql .= "FROM tbl_games g  ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID  ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID  ";
	$sql .= "WHERE (HteamID = 11 OR ATeamID = 11) AND MatchTypeID = 21 and MatchTime < now() ORDER BY MatchTime ASC";
	$points = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	
	$longPageContent = '<h1>History Chase by '.$strBy.'</h1>';
	$longPageContent .= '<ul class="inline clearfix"><li><a class="selected" href="/visualize/chase/'.$strBy.'">vs. History</a></li><li><a href="/visualize/chase_mls/'.$strBy.'">vs. League</a></li></ul>';
	$longPageContent .= '<ul class="inline clearfix"><li><a'.$strMatchClass.' href="/visualize/chase/match">by Matchday</a></li><li><a'.$strDateClass.' href="/visualize/chase/day">by Day of Year</a></li></ul>';
	$longPageContent .= '<p>This chart illustrates the Crew\'s point total at each point through the season. Use the controls to select which seasons to display. Hover over any point to see more information about that game.</p>';
	$longPageContent .= '<section id="options"><h2>Seasons</h2> <ul id="filter" class="inline"></ul></section>';
	$longPageContent .= '<div id="flotchart" class="clearboth"></div>';

	
	?>
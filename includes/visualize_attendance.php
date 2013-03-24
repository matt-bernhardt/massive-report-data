<?php
	$strMenuGroup = "Attendance";

	$strPageTitle .= " > Attendance";

	$strJavascript = "visualize_attendance.js";

	// Define needed js libraries
	$boolFlot = TRUE;

	$sql = "SELECT attendance, format(attendance,0) AS FormatAttendance, date_format(MatchTime,'%c/%e/%Y') AS MatchDate, o.teamname AS Opponent, date_format(MatchTime,'%Y') AS Season ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams o ON g.ATeamID = o.ID ";
	$sql .= "WHERE HteamID = 11 ";
	$sql .= "  AND MatchTypeID = 21 ";
	$sql .= "  AND MatchTime < now() ";
	$sql .= "ORDER BY MatchTime ASC ";
	$attendance = mysql_query($sql, $connection) or die(mysql_error());

	$longPageContent = '<h1>Attendance Charts</h1>';
	$longPageContent .= '<p>This chart plots home attendance, grouped by game number. Use the controls to select which seasons to display. Hover over any point to see more information about that game.</p>';
	$longPageContent .= '<section id="options"><h2>Seasons</h2> <ul id="filter" class="inline"></ul></section>';
	$longPageContent .= '<div id="flotchart" class="clearboth"></div>';
?>
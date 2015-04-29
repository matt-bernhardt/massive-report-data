<?php
	$strMenuGroup = "Standings";

	$strPageTitle .= " > Standings";

	$strJavascript = "visualize_standings.js";

	// Define needed js libraries
	$boolFlot = TRUE;

	$longPageContent = '<h1>Conference Standings</h1>';
	$longPageContent .= '<p>Below is a graph of the current conference standings. Each team is represented by a horizontal line, with the Crew called out in yellow. The left side of each line indicates a team\'s current point total, while the right edge represents the team\'s maximum potential points.</p>';
	$longPageContent .= '<h2>Eastern Conference</h2>';
	$longPageContent .= '<div class="standings" id="east" style="width:100%;height:300px;"></div>';
	$longPageContent .= '<h2>Western Conference</h2>';
	$longPageContent .= '<div class="standings" id="west" style="width:100%;height:300px;"></div>';
	$longPageContent .= '<p>The above charts are current through games played on Sunday, March 8, 2015.</p>';
?>

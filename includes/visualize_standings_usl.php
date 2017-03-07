<?php
	$strMenuGroup = "Standings";

	$strPageTitle .= " > USL Standings";

	$strJavascript = "visualize_standings_usl.js";

	// Define needed js libraries
	$boolFlot = TRUE;

	$longPageContent = '<h1>United Soccer League Standings</h1>';
	$longPageContent .= '<p>Below is a graph of the current United Soccer League standings. Each team is represented by a horizontal line. The left side of each line indicates a team\'s current point total, while the right edge represents the team\'s maximum potential points. Hover over either end of any line for more information.</p>';
	$longPageContent .= '<h2>Eastern Conference</h2>';
	$longPageContent .= '<div class="standings" id="east" style="width:100%;height:400px;"></div>';
	$longPageContent .= '<h2>Western Conference</h2>';
	$longPageContent .= '<div class="standings" id="west" style="width:100%;height:400px;"></div>';
?>

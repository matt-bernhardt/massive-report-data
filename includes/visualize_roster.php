<?php
	
	$strMenuGroup = "Roster";

	$strPageTitle .= " > Roster Visualizer";

	$strJavascript = "visualize_roster.js";

	// Define needed js libraries
	$boolD3 = TRUE;

	$longPageContent = '<h1>Roster Visualizer</h1>';
	$longPageContent .= '<p>This chart visualizes certain information about team rosters.</p>';
	$longPageContent .= '<section id="options"><h2>Teams</h2> <ul id="filter" class="inline"></ul></section>';
	$longPageContent .= '<div id="fig" class="clearboth"></div>';

	
	?>
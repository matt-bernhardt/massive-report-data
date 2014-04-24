<?php
	
	$strMenuGroup = "Roster";

	$strPageTitle .= " > Roster Visualizer";

	$strJavascript = "visualize_roster.js";

	// Define needed js libraries
	$boolD3 = TRUE;

	$longPageContent = '<h1>Roster Visualizer</h1>';
	$longPageContent .= '<p>This chart visualizes the sizes of players on a team roster.</p>';
	$longPageContent .= '<section id="options"><h2>Teams</h2> <ul id="filter" class="inline">';
	$longPageContent .= '<li><input type="checkbox" name="CHI" id="chi" data-id="13"><label for="chi">CHI</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="CLB" id="clb" data-id="11"><label for="clb">CLB</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="DC" id="dc" data-id="12"><label for="dc">DC</label></li>';
	$longPageContent .= '</ul></section>';
	$longPageContent .= '<div id="chart" class="clearboth"></div>';

	?>
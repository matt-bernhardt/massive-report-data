<?php
	
	$strMenuGroup = "Roster";

	$strPageTitle .= " > Roster Visualizer";

	$strJavascript = "visualize_roster.js";

	// Define needed js libraries
	$boolD3 = TRUE;

	$longPageContent = '<h1>Roster Visualizer</h1>';
	$longPageContent .= '<p>This chart visualizes the sizes of players on team rosters across Major League Soccer. Dots are color-coded by position, and sized according to the amount of playing time they have received this season.</p>';
	$longPageContent .= '<p>Click on a team name to highlight that team\'s players. Hover over a dot to see that player\'s name.</p>';
	$longPageContent .= '<section id="options"><h2>Teams</h2> <ul id="filter" class="inline">';
	$longPageContent .= '<li><input type="checkbox" name="CLB" id="clb" data-id="11"><label for="clb">CLB</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="CHI" id="chi" data-id="13"><label for="chi">CHI</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="COL" id="col" data-id="14"><label for="col">COL</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="DAL" id="dal" data-id="16"><label for="dal">DAL</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="DC" id="dc" data-id="12"><label for="dc">DC</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="HOU" id="hou" data-id="427"><label for="hou">HOU</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="KC" id="kc" data-id="18"><label for="kc">KC</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="LA" id="la" data-id="19"><label for="la">LA</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="MON" id="mon" data-id="45"><label for="mon">MON</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="NE" id="ne" data-id="15"><label for="ne">NE</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="NY" id="ny" data-id="20"><label for="ny">NY</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="NYC" id="nyc" data-id="547"><label for="nyc">NYC</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="ORL" id="orl" data-id="506"><label for="orl">ORL</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="PHI" id="phi" data-id="479"><label for="phi">PHI</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="POR" id="por" data-id="42"><label for="por">POR</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="RSL" id="rsl" data-id="340"><label for="rsl">RSL</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="SEA" id="sea" data-id="43"><label for="sea">SEA</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="SJ" id="sj" data-id="17"><label for="sj">SJ</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="TOR" id="tor" data-id="463"><label for="tor">TOR</label></li>';
	$longPageContent .= '<li><input type="checkbox" name="VAN" id="van" data-id="44"><label for="van">VAN</label></li>';
	$longPageContent .= '</ul></section>';
	$longPageContent .= '<div id="chart" class="clearboth"></div>';

	?>
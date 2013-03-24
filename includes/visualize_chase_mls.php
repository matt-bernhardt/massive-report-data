<?php
	if(isset($arrPath[2])){
		$strBy = $arrPath[2];
	} else {
		$strBy = 'match';
	}
	
	$strMenuGroup = "Chase";

	$strPageTitle .= " > Points Chase";

	$strJavascript = "visualize_chase_mls.js";

	// Define needed js libraries
	$boolFlot = TRUE;

	if($strBy=='match'){
		$strMatchClass=' class="selected"';
		$strDateClass='';
	} else {
		$strMatchClass='';
		$strDateClass=' class="selected"';
	}
	
	$longPageContent = '<h1>League Chase by '.$strBy.'</h1>';
	$longPageContent .= '<ul class="inline clearfix"><li><a href="/visualize/chase/'.$strBy.'">vs. History</a></li><li><a class="selected" href="/visualize/chase_mls/'.$strBy.'">vs. League</a></li></ul>';
	$longPageContent .= '<ul class="inline clearfix"><li><a'.$strMatchClass.' href="/visualize/chase_mls/match">by Matchday</a></li><li><a'.$strDateClass.' href="/visualize/chase_mls/day">by Day of Year</a></li></ul>';
	$longPageContent .= '<p>This chart illustrates each team\'s pursuit of a playoff spot at each point through the season. Team results can be turned on and off by clicking on their name in the control bar. Hover over any point to see more information about that game.</p>';
	$longPageContent .= '<section id="options"><h2>Seasons</h2> <ul id="filter" class="inline"></ul></section>';
	$longPageContent .= '<div id="flotchart" class="clearboth"></div>';
?>
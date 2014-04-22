<?php

	$strMenuGroup = "Seasons";

	$boolTabs = TRUE;
	$boolD3 = TRUE;

	$strJavascript = "jquery.hash.season.js";

	if(!isset($intSeason)){
		$intSeason = date('Y');
	}

	$longPageContent = '<section id="options" class="clearfix" style="clear:both;">';
	$longPageContent .= '<h1>Season Summary</h1>';
	$longPageContent .= '<ul>';
	for($i=1996; $i<=date('Y'); $i++) {
		if ($i == $intSeason) {
			$strClass = 'thisyear';
		} else {
			$strClass = '';
		}
  		$longPageContent .= '<li><a href="/season/'.$i.'" class="'.$strClass.'">'.$i.'</a></li>';
	}
	$longPageContent .= '</ul>';
	$longPageContent .= '</section>';


	$longPageContent .= '<div id="tabs" class="page_season"><div id="season" class="'.$intSeason.'">';

	$longPageContent .= '<ul>';
	$longPageContent .= '<li><a href="#overview">Overview</a></li>';
	$longPageContent .= '<li><a href="#games">Games</a></li>';
	$longPageContent .= '<li><a href="#stats">Stats</a></li>';
	$longPageContent .= '<li><a href="#transactions">Transactions</a></li>';
	$longPageContent .= '<li><a href="#combos">Combos</a></li>';
	$longPageContent .= '</ul>';

	$longPageContent .= '</div><div id="region">';
	$longPageContent .= '<p>Default state</p>';
	$longPageContent .= '</div>'; // region

	$longPageContent .= '</div>'; // page_season

?>
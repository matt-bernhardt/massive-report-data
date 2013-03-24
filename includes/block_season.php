<?php

	$strMenuGroup = "Seasons";

	$boolTabs = TRUE;
	$boolIsotope = TRUE;

	$strJavascript = "jquery.bbq.season.js";

	$longPageContent = '<div id="tabs" class="bbq page_season">';
	$longPageContent .= "<h1>".$intSeason." Season</h1>";

	$longPageContent .= '<div class="bbq-nav bbq-nav-top">';
	$longPageContent .= '<a href="#overview">Overview</a>';
	$longPageContent .= '<a href="#games">Games</a>';
	$longPageContent .= '<a href="#stats">Stats</a>';
	$longPageContent .= '<a href="#transactions">Transactions</a>';
	$longPageContent .= '</div>'; // bbq_nav

	$longPageContent .= '<div class="bbq-content">';
	$longPageContent .= '<div style="display: none;" class="bbq-loading">Loading content...</div>'; // bbq-loading
	$longPageContent .= '<div class="bbq-default bbq-item" style="display: none;">';
	$longPageContent .= 'Auto-load here';
	$longPageContent .= '</div>'; // bbq-default
	$longPageContent .= '<div class="bbq-item" style="display: none;">';
	$longPageContent .= '</div>'; // bbq-item
	$longPageContent .= '';
	$longPageContent .= '';

	$longPageContent .= '</div>'; // bbq_content

	$longPageContent .= '</div>'; // page_season

?>
<?php

	$strMenuGroup = "Players";

	$boolTabs = TRUE;

	$strJavascript = "jquery.bbq.player.js";

	if(!isset($intPlayerID)){
		$intPlayerID = 0;

		$longPageContent = '<div id="tabs" class="bbq page_season">';
		$longPageContent .= "<h1>Invalid Player</h1>";

		$longPageContent .= '<div class="bbq-nav bbq-nav-top">';
		$longPageContent .= '<a href="#overview">Overview</a>';
		$longPageContent .= '<a href="#contracts">Contracts</a>';
		$longPageContent .= '<a href="#gamelog">Game Log</a>';
		$longPageContent .= '<a href="#stats">Stats</a>';
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

	} else {

		$sql = "SELECT FirstName, LastName, Position ";
		$sql .= "FROM tbl_players ";
		$sql .= "WHERE ID = ".$intPlayerID;

		$player = mysql_query($sql, $connection) or die(mysql_error());

		while($row = @mysql_fetch_array($player,MYSQL_ASSOC)) {
			$strPlayerName = $row['FirstName']." ".$row['LastName'];
			$strPosition = $row['Position'];
		}

		$longPageContent = '<div id="tabs" class="bbq page_season">';
		$longPageContent .= "<h1>".$strPlayerName."</h1>";
		$longPageContent .= "<p>".$strPosition."</p>";

		$longPageContent .= '<div class="bbq-nav bbq-nav-top">';
		$longPageContent .= '<a href="#overview">Overview</a>';
		$longPageContent .= '<a href="#contracts">Contracts</a>';
		$longPageContent .= '<a href="#gamelog">Game Log</a>';
		$longPageContent .= '<a href="#stats">Stats</a>';
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

	}

?>
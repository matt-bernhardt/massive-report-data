<?php

	$strMenuGroup = "Players";

	$boolTabs = TRUE;

	$strJavascript = "jquery.hash.player.js";

	if(!isset($intPlayerID)){
		$intPlayerID = 0;

		$longPageContent .= "<h1>Invalid Player</h1>";

	} else {

		$sql = "SELECT FirstName, LastName, Position ";
		$sql .= "FROM tbl_players ";
		$sql .= "WHERE ID = ".$intPlayerID;

		$player = mysql_query($sql, $connection) or die(mysql_error());

		while($row = @mysql_fetch_array($player,MYSQL_ASSOC)) {
			$strPlayerName = $row['FirstName']." ".$row['LastName'];
			$strPosition = $row['Position'];
		}

		$strPageTitle .= ' > '.$strPlayerName;
		
		$longPageContent = '<div itemscope itemtype="http://schema.org/Person" id="tabs" class="page_player">';
		$longPageContent .= '<div id="player" class="'.$intPlayerID.'">';
		$longPageContent .= "<h1 itemprop=\"name\">".$strPlayerName."</h1>";
		$longPageContent .= "<p itemprop=\"jobTitle\">".$strPosition."</p>";

		$longPageContent .= '<ul>';
		$longPageContent .= '<li><a href="#overview">Overview</a></li>';
		$longPageContent .= '<li><a href="#contracts">Contracts</a></li>';
		$longPageContent .= '<li><a href="#gamelog">Game Log</a></li>';
		$longPageContent .= '<li><a href="#stats_v2">Stats</a></li>';
		$longPageContent .= '</ul>';

		$longPageContent .= '</div><div id="region">';
		$longPageContent .= '<p>Default state</p>';
		$longPageContent .= '</div>'; // region

		$longPageContent .= '</div>'; // page_player

	}

?>
<?php

	$strMenuGroup = "Players";

	$boolTabs = TRUE;
	$boolD3 = TRUE;

	$strJavascript = "jquery.hash.player.js";

	if(!isset($intPlayerID)){
		$intPlayerID = 0;

		$longPageContent .= "<h1>Invalid Player</h1>";

	} else {

		$sql = "SELECT FirstName, LastName, Position, Citizenship, DOB ";
		$sql .= "FROM tbl_players ";
		$sql .= "WHERE ID = ".$intPlayerID;

		$player = mysqli_query($connection, $sql) or die(mysqli_error($connection));

		while($row = @mysqli_fetch_array($player,MYSQLI_ASSOC)) {
			$strPlayerName = $row['FirstName']." ".$row['LastName'];
			$strPosition = $row['Position'];
			$strCitizenship = $row['Citizenship'];
			$strDOB = $row['DOB'];
		}

		$sql = "SELECT URL, Title FROM tbl_url WHERE PlayerID = ".$intPlayerID;
		$url = mysqli_query($connection, $sql) or die(mysqli_error($connection));

		$strPageTitle .= ' > '.$strPlayerName;
		
		$longPageContent = '<div itemscope itemtype="http://schema.org/Person" id="tabs" class="page_player">';
		$longPageContent .= '<div id="player" class="'.$intPlayerID.'">';
		$longPageContent .= "<h1 itemprop=\"name\">".$strPlayerName."</h1>";
		$longPageContent .= "<p itemprop=\"jobTitle\">".$strPosition."</p>";

		$longPageContent .= "<div class=\"semantic\">";
		$longPageContent .= "<div itemprop=\"nationality\">".$strCitizenship."</div>";
		$longPageContent .= "<div itemprop=\"birthDate\">".$strDOB."</div>";
		while($row = @mysqli_fetch_array($url,MYSQLI_ASSOC)) {
			$longPageContent .= "<div itemprop=\"sameAs\">".$row['URL']."</div>";
		}
		$longPageContent .= "</div>";

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
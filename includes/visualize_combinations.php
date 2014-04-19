<?php
	$strMenuGroup = "Combinations";

	$strPageTitle .= " > Combinations";


	if(isset($arrPath[2])){
		$intYear = filter_var($arrPath[2], FILTER_VALIDATE_INT);
	} else {
		$intYear = date("Y");
		$strPath .= "/".$intYear;
	}

	if(isset($arrPath[3])){
		$intPlayer1 = filter_var($arrPath[3], FILTER_VALIDATE_INT);
		$sql = "SELECT CONCAT(FirstName,' ',LastName) AS PlayerName FROM tbl_players WHERE ID = ".$intPlayer1;
		$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
		while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)) {
			$strPlayer1 = $row['PlayerName'];
		}
	} else {
		$intPlayer1 = 0;
		$strPlayer1 = '';
	}

	if(isset($arrPath[4])){
		$intPlayer2 = filter_var($arrPath[4], FILTER_VALIDATE_INT);
		$sql = "SELECT CONCAT(FirstName,' ',LastName) AS PlayerName FROM tbl_players WHERE ID = ".$intPlayer2;
		$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
		while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)) {
			$strPlayer2 = $row['PlayerName'];
		}
	} else {
		$intPlayer2 = 0;
		$strPlayer2 = '';
	}

	// Define needed js libraries
	$boolD3 = TRUE;

	// Build headline and complete title
	$strHeadline = '<h1>'.$intYear.' Player Combinations';
	$strPageTitle .= ' > '.$intYear;
	if($strPlayer1) { 
		$strHeadline .= ': '.$strPlayer1;
		$strPageTitle .= ' > '.$strPlayer1;
	}
	if($strPlayer2) { 
		$strHeadline .= ' and '.$strPlayer2;
		$strPageTitle .= ' + '.$strPlayer2;
	}
	$strHeadline .= '</h1>';
	$longPageContent = $strHeadline;

	// Overview paragraph
	$longPageContent .= '<p>This section explores how team performance changes while different pairs of players appear in various combinations.</p>';

	
	$longPageContent .= '<section id="options"><h2>Seasons</h2> <ul id="filter" class="inline clearfix">';
	$sql = "SELECT DISTINCT Year FROM tbl_combos_stats WHERE TeamID = 11 ORDER BY Year ASC";
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while($row = @mysqli_fetch_array($rs,MYSQLI_ASSOC)){
		$i = (int) $row['Year'];
		if($i==$intYear){
			$strClass = ' class="selected"';
		} else {
			$strClass = '';
		}
		$longPageContent .= '<li><a href="/visualize/combinations/'.$i.'"'.$strClass.'>'.$i.'</a></li>';
	}
	$longPageContent .= '</ul></section>';
	
	
	// Build visualization

	if ($intPlayer1 == 0) {
		// Overall grid
		$strJavascript = "visualize_combinations_grid.js";
		require_once('visualize_combinations_grid.php');
	} elseif ($intPlayer1 > 0 && $intPlayer2 == 0) {
		// Focus on player 1
		$longPageContent .= '<p>Player 1</p>';
		$longPageContent .= '<div id="svg"></div>';
		$strJavascript = "visualize_combinations.js";
	} elseif ($intPlayer1 > 0 && $intPlayer2 > 0) {
		// Combination of players 1 & 2
		$longPageContent .= '<p>Player 2</p>';
		$longPageContent .= '<div id="svg"></div>';
		$strJavascript = "visualize_combinations.js";
	} else {
		// Not sure what happened here
		$longPageContent .= '<p>Huh?</p>';
	}

?>
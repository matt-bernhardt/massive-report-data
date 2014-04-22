<?php
	$strMenuGroup = "Players";

	// Define needed js libraries
	$boolIsotope = TRUE;

	$sql = "SELECT tbl_players.ID, CONCAT(LEFT(firstname,1),LEFT(lastname,1)) AS Symbol, FirstName, LastName, LEFT(POSITION,3) AS Pos, GROUP_CONCAT(DISTINCT YEAR(MatchTime) SEPARATOR ' ') AS YearList, RosterNumber, DATE_FORMAT(DOB,'%c/%e/%y') ";
	$sql .= "FROM tbl_players ";
	$sql .= "LEFT OUTER JOIN tbl_gameminutes ON tbl_players.ID = tbl_gameminutes.PlayerID ";
	$sql .= "LEFT OUTER JOIN tbl_games ON tbl_gameminutes.GameID = tbl_games.ID ";
	$sql .= "WHERE tbl_gameminutes.TeamID = 11 ";
	$sql .= "GROUP BY tbl_players.ID ";
	$sql .= "ORDER BY lastName ASC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Player Explorer</h1>';
	$longPageContent .= '<p class="clearboth">Welcome to the all-time Columbus roster. You can filter the players below according to several criteria.</p>';
	$longPageContent .= '<section id="options" class="clearfix"><div class="option-combo control">';
	$longPageContent .= '<h2>Year</h2>';
	$longPageContent .= '<ul id="filters" class="filter option-set clearfix" data-option-key="filter">';
	$longPageContent .= '<li><a href="#filter" data-option-value="*" class="selected">Show All</a></li>';
	for ($i = 1996; $i <= 2012; $i++) {
		if($i==2012){$strClass='';}else{$strClass="";}
		$longPageContent .= '<li><a href="#filter" data-option-value=".'.$i.'"'.$strClass.'>'.$i.'</a></li>';
	}
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="option-combo control">';
	$longPageContent .= '<h2>Sort</h2>';
	$longPageContent .= '<ul id="filters" class="filter option-set clearfix" data-option-key="filter">';
	$longPageContent .= '<li><a href="#filter" data-option-value="*" class="selected">Show All</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".gk">Goalkeepers</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".def">Defenders</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".mid">Midfielders</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".fwd">Forwards</a></li>';
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="option-combo control">';
	$longPageContent .= '<h2>Sort</h2>';
	$longPageContent .= '<ul id="sort-by" class="filter option-set clearfix" data-option-key="sortBy">';
	$longPageContent .= '<li><a href="#sortBy=original-order" data-option-value="original-order" class="selected">Alphabetical</a></li>';
	$longPageContent .= '<li><a href="#sortBy=number" data-option-value="number">Position</a></li>';
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';
	$longPageContent .= '</section>';
	$longPageContent .= '<div id="container" class="clearfix" style="margin-left: -10px; width:980px;">';

	while($row = @mysqli_fetch_array($players,MYSQLI_ASSOC)) {
		switch ($row['Pos']) {
			case 'Goa':
				$strPos = "gk";
				break;
			case 'Def':
				$strPos = "def";
				break;
			case 'Mid':
				$strPos = "mid";
				break;
			case 'For':
				$strPos = "fwd";
				break;
		}
		$strPlayerName = $row['FirstName'].' '.$row['LastName'];

		$longPageContent .= '<a class="element '.$row['YearList'].' '.$strPos.'" data-symbol="'.$row['Symbol'].'" data-category="transition" href="/player/'.$row['ID'].'">';
		$longPageContent .= '<span class="number">'.ucfirst($strPos).'</span>';
		$longPageContent .= '<span class="symbol" title="'.$strPlayerName.'">'.$row['Symbol'].'</span>';
		$longPageContent .= '<span class="name">'.$row['LastName'].'</span>';
		$longPageContent .= '<span class="weight">#'.$row['RosterNumber'].'</span>';
		$longPageContent .= '</a>';
	}

	$longPageContent .= '</div>';
	$longPageContent .= '<script>$(function(){var a=$("#container");a.isotope({layoutMode:"cellsByRow",cellsByRow:{columnWidth:160},itemSelector:".element",getSortData:{symbol:function(a){return a.attr("data-symbol")},category:function(a){return a.attr("data-category")},number:function(a){return a.find(".number").text()},weight:function(a){return parseFloat(a.find(".weight").text().replace(/[\(\)]/g,""))},name:function(a){return a.find(".name").text()}}});var b=$("#options .option-set"),c=b.find("a");c.click(function(){var b=$(this);if(b.hasClass("selected")){return false}var c=b.parents(".option-set");c.find(".selected").removeClass("selected");b.addClass("selected");var d={},e=c.attr("data-option-key"),f=b.attr("data-option-value");f=f==="false"?false:f;d[e]=f;if(e==="layoutMode"&&typeof changeLayoutMode==="function"){changeLayoutMode(b,d)}else{a.isotope(d)}return false})})</script>';
	$longPageContent .= "<hr />";

?>
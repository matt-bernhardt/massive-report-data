<?php
	$strMenuGroup = "Games";

	// Define needed js libraries
	$boolIsotope = TRUE;

	$sql = "SELECT g.ID, date_format(MatchTime,'%Y') AS MatchYear, date_format(MatchTime,'%c/%e/%Y') AS MatchDate, h.teamname, a.teamname, t.id, t.MatchType, if(HteamID = 11, a.team3ltr, concat('@ ',h.team3ltr)	) AS Opp, Concat(HScore,' - ',Ascore) AS Score ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE HteamID = 11 OR AteamID = 11 ";
	$sql .= "ORDER BY MatchTime ASC";

	$games = mysql_query($sql, $connection) or die(mysql_error());

	$sql = "SELECT t.id, MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE HteamID = 11 OR AteamID = 11 ";
	$sql .= "GROUP BY t.id ";
	$sql .= "ORDER BY MatchType ASC";

	$competitions = mysql_query($sql, $connection) or die(mysql_error());

	$longPageContent = '<h1>Game Explorer</h1>';
	$longPageContent .= '<p class="clearboth">Welcome to the all-time Columbus schedule. You can filter the games below according to several criteria.</p>';
	$longPageContent .= '<section id="options" class="clearfix"><div class="option-combo control">';
	$longPageContent .= '<h2>Competition</h2>';
	$longPageContent .= '<ul id="filters" class="filter option-set clearfix" data-option-key="filter">';
	$longPageContent .= '<li><a href="#filter" data-option-value="*" class="selected">Show All</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".league">League</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".playoff">Playoffs</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".opencup">Open Cup</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".international">International</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".friendly">Friendly</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".preseason">Preseason</a></li>';
	$longPageContent .= '<li><a href="#filter" data-option-value=".other">Other</a></li>';
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="option-combo control">';
	$longPageContent .= '<h2>Year</h2>';
	$longPageContent .= '<ul id="filters" class="filter option-set clearfix" data-option-key="filter">';
	$longPageContent .= '<li><a href="#filter" data-option-value="*" class="selected">Show All</a></li>';
	for ($i = 1996; $i <= 2012; $i++) {
		if($i==2012){$strClass='';}else{$strClass="";}
		$longPageContent .= '<li><a href="#filter" data-option-value=".s'.$i.'"'.$strClass.'>'.$i.'</a></li>';
	}
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '</section>';
	$longPageContent .= '<div id="container" class="clearfix" style="margin-left: -10px; width:980px;">';

	while($row = @mysql_fetch_array($games,MYSQL_ASSOC)) {

		switch($row['id']) {
			case '21':
				$strCompetition = "league";
				$strShowCompetition = "League";
				break;
			case '4':
				$strCompetition = "playoff";
				$strShowCompetition = "Playoffs";
				break;
			case '5':
				$strCompetition = "playoff";
				$strShowCompetition = "MLS Cup";
				break;
			case '14':
			case '22':
				$strCompetition = "opencup";
				$strShowCompetition = "Open Cup";
				break;
			case '23':
				$strCompetition = "international";
				$strShowCompetition = "Champs Lg";
				break;
			case '24':
				$strCompetition = "international";
				$strShowCompetition = "Giants Cup";
				break;
			case '25':
				$strCompetition = "international";
				$strShowCompetition = "Champs Cup";
				break;
			case '1':
				$strCompetition = "friendly";
				$strShowCompetition = "Friendly";
				break;
			case '3':
				$strCompetition = "preseason";
				$strShowCompetition = "Preseason";
				break;
			default:
				$strCompetition = "other";
				$strShowCompetition = "Other";
		}
		$intSeason = $row['MatchYear'];

		$longPageContent .= '<a class="element '.$strCompetition.' s'.$intSeason.'" data-symbol="'.$row['Opp'].'" data-category="transition" href="/game/'.$row['ID'].'">';
		$longPageContent .= '<span class="competition">'.$strShowCompetition.'</span>';
		$longPageContent .= '<span class="opponent">'.$row['Opp'].'</span>';
		$longPageContent .= '<span class="score">'.$row['Score'].'</span>';
		$longPageContent .= '<span class="date">'.$row['MatchDate'].'</span>';
		$longPageContent .= '</a>';
	}

	$longPageContent .= '</div>';
	$longPageContent .= '<script>$(function(){var a=$("#container");a.isotope({layoutMode:"cellsByRow",cellsByRow:{columnWidth:160},itemSelector:".element",getSortData:{symbol:function(a){return a.attr("data-symbol")},category:function(a){return a.attr("data-category")},number:function(a){return a.find(".number").text()},weight:function(a){return parseFloat(a.find(".weight").text().replace(/[\(\)]/g,""))},name:function(a){return a.find(".name").text()}}});var b=$("#options .option-set"),c=b.find("a");c.click(function(){var b=$(this);if(b.hasClass("selected")){return false}var c=b.parents(".option-set");c.find(".selected").removeClass("selected");b.addClass("selected");var d={},e=c.attr("data-option-key"),f=b.attr("data-option-value");f=f==="false"?false:f;d[e]=f;if(e==="layoutMode"&&typeof changeLayoutMode==="function"){changeLayoutMode(b,d)}else{a.isotope(d)}return false})})</script>';
	$longPageContent .= "<hr />";
?>
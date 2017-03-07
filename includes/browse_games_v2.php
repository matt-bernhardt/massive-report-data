<?php
	$strMenuGroup = "Games";

	$strJavascript = "browse_filter.js";

	// Define needed js libraries
	// $boolIsotope = TRUE;

	$sql = "SELECT g.ID, date_format(MatchTime,'%Y') AS MatchYear, date_format(MatchTime,'%c/%e/%Y') AS MatchDate, h.teamname, a.teamname, t.id, t.MatchType, if(HteamID = 11, a.team3ltr, concat('@ ',h.team3ltr)	) AS Opp, Concat(HScore,' - ',Ascore) AS Score ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE (HteamID = 11 OR AteamID = 11) AND g.MatchTypeID <> 3 ";
	$sql .= "ORDER BY MatchTime ASC";
	$games = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$sql = "SELECT t.id, MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE (HteamID = 11 OR AteamID = 11) AND g.MatchTypeID <> 3 ";
	$sql .= "GROUP BY t.id ";
	$sql .= "ORDER BY MatchType ASC";
	$competitions = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Game Explorer</h1>';
	$longPageContent .= '<p class="clearboth">Welcome to the all-time Columbus schedule. You can filter the games below according to several criteria.</p>';

	$longPageContent .= '<section id="options" class="clearfix combo-filters">';
	$longPageContent .= '<div class="option-combo competition">';
	$longPageContent .= '<h2>Competition</h2>';
	$longPageContent .= '<ul class="filter option-set clearfix" data-filter-group="competition">';
	$longPageContent .= '<li><a href="#filter-competition-any" data-filter-value="" class="selected">Show All</a></li>';
	$longPageContent .= '<li><a href="#filter-competition-league" data-filter-value=".league">League</a></li>';
	$longPageContent .= '<li><a href="#filter-competition-playoff" data-filter-value=".playoff">Playoffs</a></li>';
	$longPageContent .= '<li><a href="#filter-competition-opencup" data-filter-value=".opencup">Open Cup</a></li>';
	$longPageContent .= '<li><a href="#filter-competition-international" data-filter-value=".international">International</a></li>';
	$longPageContent .= '<li><a href="#filter-competition-friendly" data-filter-value=".friendly">Friendly</a></li>';
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="option-combo season">';
	$longPageContent .= '<h2>Year</h2>';
	$longPageContent .= '<ul class="filter option-set clearfix" data-filter-group="season">';
	$longPageContent .= '<li><a href="#filter-season-any" data-filter-value="" class="selected">Show All</a></li>';
	for ($i = 1996; $i <= 2017; $i++) {
		if($i==2017){$strClass='';}else{$strClass="";}
		$longPageContent .= '<li><a href="#filter-season-s'.$i.'" data-filter-value=".s'.$i.'"'.$strClass.'>'.$i.'</a></li>';
	}
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '</section>';
	$longPageContent .= '<div id="container" class="clearfix" style="margin-left: -10px; width:980px;">';

	while($row = @mysqli_fetch_array($games,MYSQLI_ASSOC)) {

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
?>
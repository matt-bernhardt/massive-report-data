<?php
	$strMenuGroup = "Players";

	$boolDataTables = true;
	$strJavascript = "browse_filter.js";

	// Define needed js libraries
	// $boolIsotope = TRUE;

	$sql = "SELECT tbl_players.ID, CONCAT(LEFT(firstname,1),LEFT(lastname,1)) AS Symbol, FirstName, LastName, LEFT(POSITION,3) AS Pos, GROUP_CONCAT(DISTINCT YEAR(MatchTime) SEPARATOR ' ') AS YearList, RosterNumber, date_format(DOB,'%Y-%m-%d') AS MarkupDOB, DATE_FORMAT(DOB,'%c/%e/%y') AS DOB, Concat(Height_Feet,'-',Height_Inches) AS Height, Weight ";
	$sql .= "FROM tbl_players ";
	$sql .= "LEFT OUTER JOIN tbl_gameminutes ON tbl_players.ID = tbl_gameminutes.PlayerID ";
	$sql .= "LEFT OUTER JOIN tbl_games ON tbl_gameminutes.GameID = tbl_games.ID ";
	$sql .= "WHERE tbl_gameminutes.TeamID = 11 ";
	$sql .= "GROUP BY tbl_players.ID ";
	$sql .= "ORDER BY lastName ASC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Player Explorer</h1>';
	$longPageContent .= '<p class="clearboth">Welcome to the all-time Columbus roster. You can filter the players below according to several criteria.</p>';

	$longPageContent .= '<section id="options" class="clearfix combo-filters">';
	$longPageContent .= '<div class="option-combo year">';
	$longPageContent .= '<h2>Season</h2>';
	$longPageContent .= '<ul class="filter option-set clearfix" data-filter-group="year">';
	$longPageContent .= '<li><a href="#filter-year-any" data-filter-value="" class="selected">Show All</a></li>';
	for ($i = 1996; $i <= 2014; $i++) {
		if($i==2014){$strClass='';}else{$strClass="";}
		$longPageContent .= '<li><a href="#filter-year-'.$i.'" data-filter-value=".'.$i.'"'.$strClass.'>'.$i.'</a></li>';
	}
	$longPageContent .= '</ul>';
	$longPageContent .= '</div>';

	$longPageContent .= '<div class="option-combo position">';
	$longPageContent .= '<h2>Position</h2>';
	$longPageContent .= '<ul class="filter option-set clearfix" data-filter-group="position">';
	$longPageContent .= '<li><a href="#filter-position-any" data-filter-value="" class="selected">Show All</a></li>';
	$longPageContent .= '<li><a href="#filter-position-gk" data-filter-value=".gk">Goalkeepers</a></li>';
	$longPageContent .= '<li><a href="#filter-position-def" data-filter-value=".def">Defenders</a></li>';
	$longPageContent .= '<li><a href="#filter-position-mid" data-filter-value=".mid">Midfielders</a></li>';
	$longPageContent .= '<li><a href="#filter-position-fwd" data-filter-value=".fwd">Forwards</a></li>';
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

		$longPageContent .= '<a itemscope itemtype="http://schema.org/Person" itemprop="url" title="'.$strPlayerName.'" class="element '.$row['YearList'].' '.$strPos.'" data-symbol="'.$row['Symbol'].'" data-category="transition" href="/player/'.$row['ID'].'">';
		$longPageContent .= '<span class="position">'.ucfirst($strPos).'</span>';
		$longPageContent .= '<span class="symbol">'.$row['Symbol'].'</span>';
		$longPageContent .= '<span class="name" itemprop="name">'.$row['LastName'].'</span>';
		$longPageContent .= '<span class="dob" itemprop="birthDate" datetime="'.$row['MarkupDOB'].'">'.$row['DOB'].'</span>';
		$longPageContent .= '<span class="size">'.$row['Height'].' '.$row['Weight'].' lbs</span>';
		$longPageContent .= '<span class="jersey">#'.$row['RosterNumber'].'</span>';
		$longPageContent .= '</a>';
	}

	$longPageContent .= '</div>';

?>
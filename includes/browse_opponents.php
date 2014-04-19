<?php

	$strMenuGroup = "Games";

	$sql = "SELECT IF(g.HTeamID=11,ATeamID,HTeamID) AS OpponentID, IF(g.HTeamID=11,a.teamname,h.teamname) AS Opponent, COUNT(g.ID) AS Games, "
	."SUM(IF(g.HTeamID=11, IF(g.HScore>g.AScore,1,0), IF(g.AScore>g.HScore,1,0))) AS Wins, "
	."SUM(IF(g.HScore=g.AScore,1,0)) AS Draws, "
	."SUM(IF(g.HTeamID=11, IF(g.HScore<g.AScore,1,0), IF(g.AScore<g.HScore,1,0))) AS Losses, "
	."SUM(IF(g.HTeamID=11, g.HScore, g.AScore)) AS GoalsFor, "
	."SUM(IF(g.HTeamID=11, g.AScore, g.HScore)) AS GoalsAgainst, "
	."GROUP_CONCAT(DISTINCT t.matchtype ORDER BY t.matchtype ASC SEPARATOR '<br />') AS Competitions "
	."FROM tbl_games g "
	."LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID "
	."LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID "
	."LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.id "
	."WHERE (HTeamID = 11 OR ATeamID = 11) and t.official = 1 AND g.MatchTime < now() "
	."GROUP BY OpponentID "
	."ORDER BY Opponent ASC";

	$opponents = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>All-Time Opponents</h1>';
	$longPageContent .= '<p>The following chart presents summary information about every team that has faced the Columbus Crew. Click on a team name to see more detailed records.</p>';
	$longPageContent .= '<div id="container" class="clearfix"><table class="statsyear"><thead><tr>';
	$longPageContent .= '<th scope="col" class="phone tablet monitor">Opponent</th>';
	$longPageContent .= '<th scope="col" class="phone tablet monitor" title="Games Played">GP</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">W</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">D</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">L</th>';
	$longPageContent .= '<th scope="col" class="phone" nowrap>W-D-L</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">G+</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">G-</th>';
	$longPageContent .= '<th scope="col" class="tablet monitor">Competitions</th>';
	$longPageContent .= '</tr></thead><tbody>';

	$i = 1;
	while($row = @mysqli_fetch_array($opponents,MYSQLI_ASSOC)) {
		if($i % 2 == 0) {
			$strClass = "even";
		} else {
			$strClass = "odd";
		}
		$longPageContent .= '<tr class="last '.$strClass.'">';
		$longPageContent .= '<td class="phone tablet monitor"><a href="/opponent/'.$row['OpponentID'].'">'.$row['Opponent'].'</a></td>';
		$longPageContent .= '<td class="phone tablet monitor">'.$row['Games'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['Wins'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['Draws'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['Losses'].'</td>';
		$longPageContent .= '<td class="phone">'.$row['Wins'].'-'.$row['Draws'].'-'.$row['Losses'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['GoalsFor'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['GoalsAgainst'].'</td>';
		$longPageContent .= '<td class="tablet monitor">'.$row['Competitions'].'</td>';
		$longPageContent .= '</tr>';

		$i++;
	}
	$longPageContent .= '</tbody></table></div>';

?>
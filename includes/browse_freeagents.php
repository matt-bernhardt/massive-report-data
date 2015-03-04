<?php
	$strMenuGroup = "Players";

	$strJavascript = "browse_freeagents.js";

	// Define needed js libraries
	$boolDataTables = true;

	$sql = "SELECT s.PlayerID, CONCAT(p.FirstName,' ',p.LastName) AS PlayerName, p.DOB, p.Position ";
	$sql .= "FROM tbl_statsyear s ";
	$sql .= "INNER JOIN tbl_players p ON s.PlayerID = p.ID ";
	$sql .= "WHERE s.year = 2014 AND s.CompetitionID = 21 AND p.DOB <= '1987-12-31' ";
	$sql .= "GROUP BY s.PlayerID ";
	$sql .= "ORDER BY p.LastName ASC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$longPageContent = '<h1>Possible Free Agents</h1>';
	$longPageContent .= '<p class="clearboth">Here are the players who may be eligible for free agency as of the 2014/2015 offseason, should the new Collective Bargaining Agreement adopt the two thresholds of:</p>';
	$longPageContent .= '<ol>';
	$longPageContent .= '<li>At least 28 years of age</li>';	
	$longPageContent .= '<li>At least eight years of service within MLS - regardless of team</li>';	
	$longPageContent .= '</ol>';

	$longPageContent .= '<p>Players listed in italics would become eligible for free agency after the 2015 season.</p>';
	$longPageContent .= '<p>Click on a table heading to re-sort the display.</p>';

	$longPageContent .= '<table class="datatable" style="width:100%"><thead>';
	$longPageContent .= '<tr>';
	$longPageContent .= '<th scope="col">Player</th>';
	$longPageContent .= '<th scope="col">Born</th>';
	$longPageContent .= '<th scope="col">Years</th>';
	$longPageContent .= '<th scope="col">Position</th>';
	$longPageContent .= '</tr>';
	$longPageContent .= '</thead><tbody>';

	while($row = @mysqli_fetch_array($players,MYSQLI_ASSOC)) {

		$url = "http://www.mlssoccer.com/players/" . strtolower(str_replace(" ","-",$row['PlayerName']));

		$sql = "SELECT COUNT(DISTINCT Year) AS Years FROM tbl_statsyear_mls WHERE PlayerID = " . $row['PlayerID'];
		$years = mysqli_query($connection, $sql) or die(mysqli_error($connection));

		while($row1 = @mysqli_fetch_array($years,MYSQLI_ASSOC)) {
			if($row1['Years'] >= 6) {
				$intYears = $row1['Years'] + 1;
				if($row['DOB'] > '1986-12-31' || $row1['Years'] == 6) {
					$strClass = "pending";
				} else {
					$strClass = "freeagent";
				}
				$longPageContent .= '<tr class="' . $strClass . '">';
				$longPageContent .= '<td><a href="' . $url . '">' . $row['PlayerName'] . '</a></td>';
				$longPageContent .= '<td>' . $row['DOB'] . '</td>';
				$longPageContent .= '<td>' . $intYears . '</td>';
				$longPageContent .= '<td>' . $row['Position'] . '</td>';
				$longPageContent .= '</tr>';
			}			
		}
	}

	$longPageContent .= '</tbody></table>';
	$longPageContent .= "<hr />";

?>
<?php

	$strMenuGroup = "Players";

	$strJavascript = "browse_dates.js";

	$boolInputDate = TRUE;

	$sql = "SELECT tbl_players.ID, concat(FirstName,' ',LastName) PlayerName, Position, tbl_contracts.RosterNumber, Max(SigningDate) AS LastContract, right(max(concat(date_format(SigningDate,'%Y%m%d'),'_',lpad(TeamID,4,'0'))),4) AS LastTeam "
	."FROM tbl_players "
	."INNER JOIN tbl_contracts ON tbl_players.ID = tbl_contracts.PlayerID "
	."WHERE SigningDate < '".$inputDate."'"
	."GROUP BY tbl_players.ID "
	."HAVING LastTeam = '0011' "
	."ORDER BY LastName, FirstName ASC";

	$players = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$showDate = date("F j, Y",strtotime($inputDate));

	$longPageContent = '<h1>Roster Time Machine: '.$showDate.'</h1>';
	$longPageContent .= '<form method="get">';
	$longPageContent .= '<p class="clearboth">Show the Crew roster on: <input type="text" id="datepicker" name="date" value="'.$inputDate.'" /> <input type="submit" value="Refresh" /></p>';
	$longPageContent .= '</form>';

	$longPageContent .= '<div id="roster" class="clearfix" style="margin-left: -10px; width:980px;">';

	while($row = @mysqli_fetch_array($players,MYSQLI_ASSOC)) {
		$longPageContent .= '<div itemscope itemtype="http://schema.org/Person" class="player">';
		$longPageContent .= '<span class="jersey">'.$row['RosterNumber'].'</span>';
		$longPageContent .= '<a itemscope itemtype="http://schema.org/Person" itemprop="url" title="'.$row['PlayerName'].'" data-category="transition" href="/player/'.$row['ID'].'">';
		$longPageContent .= '<span class="name" itemprop="name">'.$row['PlayerName'].'</span>';
		$longPageContent .= '</a>';
		$longPageContent .= '<span class="position">'.$row['Position'].'</span>';
		$longPageContent .= '</div>';
	}

	$longPageContent .= '</div>';

?>
<?php
	// This page drives the routing to various content - almost everying on the site is actually served through a 404 page.

    include_once ("includes/block_conn_open.php");

	$strPath = $_GET['q'];

	$arrPath = explode("/",$strPath);

	$strPageTitle = "Massive Report > Data";
	$strMenuGroup = "";

	$strMode = $arrPath[0];
	$strMessage = "";

	switch ($strMode) {
		case "about":
			// About this site
			include ("includes/block_about.php");
			break;

		case "blog":
			// Meta blog
			include("blog/index.php");
			break;

		case "browse":
			//Browse categories
			$strPageTitle .= " > Browse";

			$strCategory = $arrPath[1];
			switch ($strCategory) {
				case "players":
					$strPageTitle .= " > Players";
					include ("includes/browse_players_v2.php");
					break;

				case "games":
					$strPageTitle .= " > Games";
					include ("includes/browse_games_v2.php");
					break;

				case "roster":
					if(isset($_GET['date'])){
						$inputDate = $_GET['date'];
					} else {
						$inputDate = date("Y-m-d");
					}
					$strPageTitle .= " > Roster";
					include ("includes/browse_roster.php");
					break;

				default:
					// Generic browse
					break;
			}
			break;

		case "changelog":
			// Site changelog
			include ("includes/block_changelog.php");
			break;

		case "date":
			// This Day in History
			include ("includes/block_date.php");
			break;

		case "game":
			// Game record
			if(count($arrPath)==1){
			} else {
				$intGameID = $arrPath[1];
			}
			include ("includes/block_game.php");
			break;

		case "opponent":
			// Opponent summaries
			$strPageTitle .= " > Opponent";
			if(count($arrPath)==1){
				$intOpponentID = 12;
			} else {
				$intOpponentID = $arrPath[1];
			}
			include ("includes/block_opponent.php");
			break;

		case "player":
			// Player profile
			$strPageTitle .= " > Player";
			$boolTabs = TRUE;
			
			if(count($arrPath)==1){
			} else {
				$intPlayerID = $arrPath[1];
			}

			include ("includes/block_player_v3.php");

			break;

		case "season":
			// Season summaries
			$strPageTitle .= " > Season";
			$boolTabs = TRUE;

			if(count($arrPath)==2){
				$intSeason = $arrPath[1];
			} else {
				$intSeason = date('Y');
			}
			include ("includes/block_season_v2.php");

			break;

		case "visualize":
			// Visualizations

			$strCategory = $arrPath[1];
			switch ($strCategory) {
				case "attendance":
					include ("includes/visualize_attendance.php");
					break;

				case "chase":
					include ("includes/visualize_chase.php");
					break;

				case "chase_mls":
					include ("includes/visualize_chase_mls.php");
					break;

				case "standings":
					include ("includes/visualize_standings.php");
					break;

				default:
					// Generic browse
					break;
			}
			break;

		default:
			// Home page
			break;

	}

    include ("includes/block_header.php");

    if(isset($longPageContent)){
    	print $longPageContent;
    } else {
?>
    <h1>Coming Soon</h1>
    <p>Sorry, you've reached a part of the site that isn't ready yet. Please check back soon, as we are continually adding new features.</p>
<?php
	}

	if(isset($strJavascript)){
		include ("javascript/".$strJavascript);
	}
	include ("includes/block_footer.php");
?>

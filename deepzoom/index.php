<?php
	$strPageTitle = "Massive Report > Data > Deep Zoom";
	$strMenuGroup = "Rosters";

	include_once('../includes/block_header.php');
?>
<h1>Columbus Roster Progression</h1>
<div id="content">
<p>The following chart is an index of the players used in every game in Columbus Crew history. It is currently complete through the end of the 2011 season.</p>
<p>Each row across the chart is a game - only competitive games (League, Open Cup, or CONCACAF competitions) are included. Each column is dedicated to a single player; the number at each location on the chart is the number of minutes played by that player in that game. Cells which are colored orange indicate that the player scored in that game.</p>
<p>In between each season, the all-time roster for the Crew is repeated in order to make the chart more readable. The playing time for each player is also provided as a summary, both in the total minutes played as well as the percentage of possible time.</p>
    <!-- Seadragon combo embed (uses Silverlight if present, otherwise falls back to Ajax) -->
    <!-- The first two arguments to Seadragon.embed() are the desired width and height for the viewer, in CSS units. -->
    <script type="text/javascript" src="http://seadragon.com/combo/embed.js"></script>
    <script type="text/javascript">Seadragon.embed("100%", "600px", "GeneratedImages/dzc_output.xml");</script>
</div>
<?php
  include_once('../includes/block_footer.php');
?>

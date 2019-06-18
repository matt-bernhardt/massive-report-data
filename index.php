<?php
	$strPageTitle = "Massive Report > Data";
	$strMenuGroup = "";
	include "includes/block_header.php";
?>
		<h1>Welcome to Massive Report Data!</h1>
		<p class="teaser">This is a repository for information concerning Columbus Crew SC. Here you will find records of every official game the team has ever played, and every player who has appeared in those games.</p>
		<p>As part of these primary areas of information, the site also holds data about team attendance, performance, the opponents that the Crew have faced, and the competitions in which it has competed. You will also find a variety of charts and plots that visualize the rich history of one of the charter members of Major League Soccer.</p>
		<p>The site is maintained by Matt Bernhardt, a longtime observer of the team who has contributed to a number of platforms over the years. I record, analyze and visualize a wide variety of information to provide some perspective on the team's activities.</p>
		<p>This is a work in progress. I invite you to look around, and let me know if there is something I've overlooked or gotten wrong. My email address is at the bottom of every page.</p>
<?php
	$sql = "SELECT p.guid, DATE_FORMAT(p.post_date,'%M %D, %Y') AS show_date, p.post_title, t.guid AS thumbnail ";
	$sql .= "FROM wp_posts p ";
	$sql .= "LEFT OUTER JOIN wp_postmeta m ON p.id = m.post_id ";
	$sql .= "LEFT OUTER JOIN wp_posts t ON m.meta_value = t.id ";
	$sql .= "WHERE p.post_type = 'post' AND p.post_status = 'publish' AND m.meta_key = '_thumbnail_id' ";
	$sql .= "ORDER BY p.post_date DESC ";
	$sql .= "LIMIT 0,5;";
	$updates = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while($row = @mysqli_fetch_array($updates,MYSQLI_ASSOC)) {
?>
		<div class="update">
			<h2><a href="<?php echo $row['guid']; ?>"><?php echo $row['post_title']; ?></a></h2>
			<p><?php echo $row['show_date']; ?><br>
			<?php // echo $row['thumbnail']; ?>
			</p>
		</div>
<?php
	}
?>
<!-- 
		<div class="update">
			<h2><a href="/static/opener">Crew SC Home Opener Attendance and Temperature</a></h2>
			<p>March 14<br>
			With another Crew SC home opener upon us, the annual examination of attendance figures is poised to begin again. I've analyzed this data for years, and want to try to put tonight's crowd size in some perspective.</p>
		</div>
		<div class="update">
			<h2><a href="/browse/freeagents">Possible Free Agents</a></h2>
			<p>March 3<br>
			With rumors swirling of a possible breakthrough on free agency, here is a list of 60 players who would become eligible once their contract is up, and another 24 who would gain that status after the 2015 season.</p>
		</div>
		<div class="update">
			<h2><a href="/visualize/roster">Charting Player Sizes By Team</a></h2>
			<p>April 27<br>
			Inspired by a series of charts on NHL player sizes that I saw recently, here is a quick visualization of player sizes - height and weight - by team, position, and playing time.</p>
		</div>
		<div class="update">
			<h2><a href="/browse/opponents">All-time Opponents</a></h2>
			<p>August 13<br>
			This update was actually posted some time ago, but the navigation changes needed to expose it haven't happened yet. This is a list of all opponents that the Crew have faced in official competitions. Can't remember if the Crew ever played a team from Syracuse (<a href="/opponent/121">yes, they have</a>)? This is your answer.</p>
		</div>
		<div class="update">
			<h2><a href="/visualize/combinations">Player Combinations</a></h2>
			<p>July 14<br>
			I'm happy to release an exploration tool for player combinations. This project allows people to explore how the Crew's performance has changed as different combinations of players have changed.</p>
		</div>
-->
		<p><em>Matthew Bernhardt</em><br />
		Historian and Statistician<br />
		Massive Report</p>
<?php
	include "includes/block_footer.php";
?>

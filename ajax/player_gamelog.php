<?php
	if(isset($_GET['player'])){
		$intPlayerID = $_GET['player'];
	} else {
		$intPlayerID = 2938;
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT FirstName, LastName ";
	$sql .= "FROM tbl_players ";
	$sql .= "WHERE ID = ".$intPlayerID;

	$player = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	while($row = @mysqli_fetch_array($player,MYSQLI_ASSOC)) {
		$strPlayerName = $row['FirstName']." ".$row['LastName'];
		$strLineChartImage = $row['LastName']."_".$row['FirstName']."_".$intPlayerID.".gif";
	}

?>
<div id="chart"></div>
<script>
	var w = $("#chart").width();

	var margin = {
		top: 20,
		right: 0,
		bottom: 20,
		left: 0
	};

	var lineheight = 18,
		textwidth = 60;

	var timelineScale = d3.scale.linear()
		.domain([0,90])
		.range([margin.left+textwidth,w-margin.right]);

	var vis = d3.select("#chart")
		.append("svg:svg")
		.attr("width",w);

	d3.json("/api/gamelog.php?term=<?php echo $intPlayerID; ?>", function(error, json) {
		if(error) {
			console.log(error);
		}

		var g = vis.selectAll("g")
			.data(json)
			.enter()
			.append("g")
			.attr("class",function(d) {return "game g"+d.GameID+" t"+d.TeamID})
			.attr("transform",function(d,i) {return "translate("+margin.left+","+((i+0.5)*lineheight)+")"});

		var a = g
			.append("a")
			.attr("xlink:href",function(d) {return "/game/"+d.GameID;});

		var label = a.append("text")
			.attr("class","label");

		label.attr("fill","#eee")
			.attr("x",0)
			.attr("y",5)
			.text(function(d) {return d.MatchTime});

		var game = a.append("line")
			.attr("class","game");

		game.attr("stroke-width",17)
			.attr("stroke","gray")
			.attr("x1",function(d) {return timelineScale(0)})
			.attr("y1",0)
			.attr("x2",function(d) {return timelineScale(90)})
			.attr("y2",0);

		var player = a.append("line")
			.attr("class","player");

		player.attr("stroke-width",17)
			.attr("stroke","#ffd407")
			.attr("x1",function(d) {return timelineScale(d.TimeOn)})
			.attr("y1",0)
			.attr("x2",function(d) {return timelineScale(d.TimeOff)})
			.attr("y2",0);

	});
	/*
	*/

</script>
<style>
g.game {
	opacity: 0.9;
}
g.game:hover,
g.game:focus {
	opacity: 1.0;
}
</style>
<p><img id="linechart" src="/images/line_charts/<?php echo $strLineChartImage; ?>" alt="Line Chart of games for <?php echo $strPlayerName; ?>" /></p>

<?php
	include_once("../includes/block_conn_close.php");
?>
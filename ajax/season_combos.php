<?php
  if(isset($_GET["season"])) {
  	$reqData = "crew".$_GET["season"].".json";
  } else {
  	$reqData = "crew2014.json";
  }
?>
<style type="text/css">
* {
  padding: 0;
  margin: 0;
  font-family: Arial, sans-serif;
  color: #d9d9d9;
}
h1 {
  margin: 0 10px;
  color: #E3C803;
}
.node circle {
  stroke: #1a1a1a;
  stroke-width: 2px;
}
.node:hover circle {
	stroke: #d9ad5b;
}
.node text {
	display: none;
	fill: #d9d9d9;
}
.node:hover text {
	display: inherit;
	fill: #d9ad5b;
}
line.link {
  stroke: #999;
  stroke-opacity: .6;
}
div#chart {
  background-color: #1a1a1a;
  border: 1px solid #959595;
  height: 600px;
  margin: 0px;
}
.control {
	padding: 0.25em;
	margin: 0.5em 0;
	display: inline-block;
}
.control>* {
	vertical-align: middle;
}
.control input {
	margin: 0 1em;
	display: inline-block;
}
.control .value {
	display: inline-block;
	min-width: 2em;
	text-align: right;
}
</style>
	<h2>Player-Game Combinations</h2>
	<p>The following force-directed graph shows the relationship between players and the games in which they appeared. Hover over any node in the graph to see more information. You can also adjust the slide controls at the bottom to alter the charge separating each node, and the default distance between them.</p>
<div class='gallery' id='chart'> </div>
<div class="control">
	<label for="charge">Charge:
		<input type="range" class="charge" name="charge" min="-300" max="0" value="-200">
		<span class="value"></span>
	</label>
</div>
<div class="control">
	<label for="link">Link Distance:
		<input type="range" class="link" name="link" min="0" max="100" value="75">
		<span class="value"></span>
	</label>
</div>
<input type="reset">
<script>
$(document).ready( function() {
	$( '.control input' ).change(function() {
		v = $(this).val();
		$(this).next().text(v);
	});

	var width = 960,
	    height = 500;

	var linkDistance = 75;
	var charge = -200;

	var color = d3.scale.category20();

	var force = d3.layout.force()
	    .charge(function() { return charge; } )
	    .linkDistance(function() { return linkDistance; } )
	    .size([width, height]);

	var svg = d3.select("#chart").append("svg")
	    .attr("width", width)
	    .attr("height", height);

	d3.json("/fdg/<?php echo $reqData; ?>", function(error, graph) {
	  force
		.nodes(graph.nodes)
		.links(graph.links)
		.start();

	  var link = svg.selectAll(".link")
		.data(graph.links)
		.enter().append("line")
		.attr("class", "link")
		.style("stroke-width", function(d) { return Math.sqrt(d.value); });

	  var node = svg.selectAll(".node")
		.data(graph.nodes)
		.enter().append("g")
		.attr("class","node")
		.call(force.drag);

		node.append("circle")
		.attr("r", 5)
		.style("fill", function(d) { return color(d.group); });

		/*
	  node.append("title")
		.text(function(d) { return d.name; });
		*/
		
	  node.append("text")
	  	.attr("dx", 12)
	  	.attr("dy", "0.25em")
	  	.text(function(d) { return d.name });

	  force.on("tick", function() {
	    link.attr("x1", function(d) { return d.source.x; })
	        .attr("y1", function(d) { return d.source.y; })
	        .attr("x2", function(d) { return d.target.x; })
	        .attr("y2", function(d) { return d.target.y; });

	    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
	  });

	  d3.select("input[name=charge]").on("change", function() {
	  	force.stop();
	    charge = this.value;
	    force.start();
	  });

	  d3.select("input[name=link]").on("change", function() {
	  	force.stop();
	    linkDistance = this.value;
	    force.start();
	  });

	  d3.select("input[type=reset]").on("click", function() {
	  	force.stop();
	    linkDistance = 30;
	    charge = -120;
	    $(".value").text("");
	    force.start();
	  });

	});

});
</script>
<p class="builtwith">Built with <a href="http://d3js.org/">d3.js</a>, a project by <a href="http://bost.ocks.org/mike/">Mike Bostock</a>.</p>
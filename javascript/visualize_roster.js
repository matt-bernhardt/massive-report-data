<script type="text/javascript">
	
	var w = $("#chart").width();
	var h = w * (3/4);

	var margin = {
		top: 50,
		right: 20,
		bottom: 40,
		left: 40
	};

	var year = 2015;

	// weight on horizontal / w / x scale
	var weightScale = d3.scale.linear()
		.domain([130,240])
		.range([margin.left,w-margin.right]);

	// height on vertical / h / y scale
	var heightScale = d3.scale.linear()
		.domain([81,60])
		.range([margin.top,h-margin.bottom]);

	// chart
	var vis = d3.select("#chart")
		.append("svg:svg")
		.attr("width",w)
		.attr("height",h);

	// X axis
	var xAxis = d3.svg.axis()
		.scale(weightScale)
		.tickSize((-1*h)+margin.top+margin.bottom)
		.tickFormat(function(d) { return d+" lb"})
		.orient('bottom');

	var xAxisElem = vis.append('g')
		.attr('class', 'x axis')
		.attr('transform','translate(0,' + (h - margin.bottom ) + ')')
		.call(xAxis);

	xAxisElem.selectAll("text").attr('y','10');

	// Y axis
	var yAxis = d3.svg.axis()
		.scale(heightScale)
		.tickSize((-1*w)+margin.left+margin.right)
		.tickValues([60,63,66,69,72,75,78,81])
		.tickFormat(function(d) { return parseInt(d / 12)+"'-"+parseInt(d % 12)+'" '; })
		.orient('left');

	var yAxisElem = vis.append('g')
		.attr('class','y axis')
		.attr('transform','translate(' + margin.left + ',0)')
		.call(yAxis);

	yAxisElem.selectAll("text").attr('x','-6');

	// Legend container
	var l = vis.append("g").attr("class","legend");

	l.append("text")
		.attr("dx",margin.left+50)
		.attr("dy",margin.top/2+6)
		.attr("fill","#d9d9d9")
		.text("Legend");

	l.append("circle")
		.attr("cx",margin.left+150)
		.attr("cy",margin.top/2)
		.attr("r",15)
		.attr("class","Goalkeeper");
	l.append("text")
		.attr("dx",20)
		.attr("dy",0)
		.attr("x",margin.left+150)
		.attr("y",margin.top/2+5)
		.attr("fill","#d9d9d9")
		.text("Goalkeeper");

	l.append("circle")
		.attr("cx",margin.left+300)
		.attr("cy",margin.top/2)
		.attr("r",15)
		.attr("class","Defender");
	l.append("text")
		.attr("dx",20)
		.attr("dy",0)
		.attr("x",margin.left+300)
		.attr("y",margin.top/2+5)
		.attr("fill","#d9d9d9")
		.text("Defender");

	l.append("circle")
		.attr("cx",margin.left+450)
		.attr("cy",margin.top/2)
		.attr("r",15)
		.attr("class","Midfielder");
	l.append("text")
		.attr("dx",20)
		.attr("dy",0)
		.attr("x",margin.left+450)
		.attr("y",margin.top/2+5)
		.attr("fill","#d9d9d9")
		.text("Midfielder");

	l.append("circle")
		.attr("cx",margin.left+600)
		.attr("cy",margin.top/2)
		.attr("r",15)
		.attr("class","Forward");
	l.append("text")
		.attr("dx",20)
		.attr("dy",0)
		.attr("x",margin.left+600)
		.attr("y",margin.top/2+5)
		.attr("fill","#d9d9d9")
		.text("Forward");

	// All player container
	var ac = vis.append("g").attr("class","ac");

	// Player container
	var pc = vis.append("g").attr("class","pc");

	function chartPlayers(thisYear,thisTeam,thisContainer,thisColor,showLabels) {

		d3.json("/api/roster.php?year="+thisYear+"&term="+thisTeam, function(error,json) {
			if(error) {
				console.log(error);
			}

			// Get maximum minutes played, and construct time scale
			var maxMinutes = d3.max(json, function(d) { return +d.Minutes; } );
			var timeScale = d3.scale.linear()
				.domain([0,maxMinutes])
				.range([5,15]);

			// Individual player containers
			var player = thisContainer.selectAll("g")
				.data(json,function(d) { return d.PlayerID});

			// Adding new players
			var newPlayer = player
				.enter()
				.append("g")
				.attr("class","player")
				.attr("data-id",function(d) { return d.PlayerID });

			// player circle
			player.append("circle")
				.attr("cx",function(d) {return weightScale(parseInt(d.Weight))})
				.attr("cy",function(d) {return heightScale(parseInt(d.Height_Inches)+12*parseInt(d.Height_Feet))})
				.attr("r",function(d) {return timeScale(parseInt(d.Minutes))})
				.attr("class",function(d) {return showLabels===true ? d.Position : ""})
				.attr("fill",thisColor)
				.attr("stroke",function(d) {return showLabels===true ? "red" : "#000"})
				.attr("stroke-width",function(d) {return showLabels===true ? "2" : "0"})
				.attr("opacity","1");

			if(showLabels===true) {
				player.append("text")
					.attr("dx", 20)
					.attr("dy", "0.25em")
					.attr("x",function(d) {return weightScale(parseInt(d.Weight))})
					.attr("y",function(d) {return heightScale(parseInt(d.Height_Inches)+12*parseInt(d.Height_Feet))})
					.text(function(d) { return d.PlayerName });
			}

			player.exit().remove();

		});

	};

	// Graph all players for context
	chartPlayers(year,0,ac,"#202020",false);

	// This is the update code
	$("#filter label").click(function(d) {
		var reqTeam = $(this).prev("input").attr("data-id");
		$("#filter label").removeClass("selected");
		chartPlayers(year,reqTeam,pc,"#666",true);
		$(this).addClass("selected");
		
	});


</script>
<style>
	#chart svg {
		background: #404040;
	}
	.axis path,
	.axis line {
		fill: none;
		stroke: #696969;
		shape-rendering: crispEdges;
	}
	.axis text {
		font-family: 'Open Sans', sans-serif;
		font-size: 13px;
		fill: #d9d9d9;
	}
.player text {
	display: none;
	fill: #d9d9d9;
}
.player:hover text {
	display: inherit;
	fill: #e3c803;
	font-weight: bold;
	font-size: 18px;
	text-shadow: 2px 2px #000;
}
	circle.Goalkeeper {
		stroke: #F7E7D4;
		fill: #F7E7D4;
		stroke: #006d2c;
		fill: #006d2c;
	}
	circle.Defender {
		stroke: #E7CB96;
		fill: #E7CB96;
		stroke: #31a354;
		fill: #31a354;	
	}
	circle.Midfielder {
		stroke: #A69688;
		fill: #A69688;
		stroke: #74c476;
		fill: #74c476;
	}
	circle.Forward {
		stroke: #C69380;	
		fill: #C69380;
		stroke: #bae4b3;
		fill: #bae4b3;
	}
</style>
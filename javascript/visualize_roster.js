<script type="text/javascript">
	var thisTeam = 0;
	
	var w = $("#chart").width();
	var h = w * (3/4);

	var margin = {
		top: 30,
		right: 20,
		bottom: 40,
		left: 40
	};

	// weight on horizontal / w / x scale
	var weightScale = d3.scale.linear()
		.domain([130,240])
		.range([margin.left,w-margin.right]);

	// height on vertical / h / y scale
	var heightScale = d3.scale.linear()
		.domain([81,60])
		.range([margin.top,h-margin.bottom]);

	var vis = d3.select("#chart")
		.append("svg:svg")
		.attr("width",w)
		.attr("height",h);

	var foo = d3.json("/api/roster.php?term="+thisTeam, function(error, json) {
		if(error) {
			console.log(error);
		}

		var maxMinutes = d3.max(json, function(d) { return +d.Minutes; } );

		var timeScale = d3.scale.linear()
			.domain([0,maxMinutes])
			.range([2,10]);

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

		// Players container
		var players = vis.append("g").attr("class","players");

		var node = players.selectAll("g")
			.data(json)
			.enter()
			.append("g")
			.attr("class","player")
			.attr("data-playerID",function(d) {return d.PlayerID});

		// player circle
		node.append("circle")
			.attr("cx",function(d) {return weightScale(parseInt(d.Weight))})
			.attr("cy",function(d) {return heightScale(parseInt(d.Height_Inches)+12*parseInt(d.Height_Feet))})
			.attr("r",function(d) {return timeScale(parseInt(d.Minutes))})
			.attr("class",function(d) {return d.Position})
			.attr("fill","#404040")
			.attr("stroke","white")
			.attr("stroke-width","2")
			.attr("opacity","0.5");

		node.append("text")
			.attr("dx", 12)
			.attr("dy", "0.25em")
			.attr("x",function(d) {return weightScale(parseInt(d.Weight))})
			.attr("y",function(d) {return heightScale(parseInt(d.Height_Inches)+12*parseInt(d.Height_Feet))})
			.text(function(d) { return d.PlayerName });
	});

	// This is the update code
	$("#filter label").click(function(d) {
		var thisTeam = $(this).prev("input").attr("data-id");
		console.log('Clicked on team '+thisTeam);
		$("#filter label").removeClass("selected");
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
	fill: #d9ad5b;
}
	circle.Goalkeeper {
		stroke: #F7E7D4;
	}
	circle.Defender {
		stroke: #E7CB96;		
	}
	circle.Midfielder {
		stroke: #A69688;
	}
	circle.Forward {
		stroke: #C69380;	
	}
</style>
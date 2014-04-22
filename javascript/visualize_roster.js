<script type="text/javascript">
	var w = $("#fig").width();
	var h = w * (3/4);

	var margin = {
		top: 0,
		right: 0,
		bottom: 0,
		left: 0
	};

	var weightScale = d3.scale.linear()
		.domain([75,250])
		.range([margin.left,w-margin.right]);

	var heightScale = d3.scale.linear()
		.domain([48,96])
		.range([margin.top,h-margin.bottom]);

	var vis = d3.select("#fig")
		.append("svg:svg")
		.attr("width",w)
		.attr("height",h);

	d3.json("/api/roster.php", function(error, json) {
		if(error) {
			console.log(error);
		}

		console.log(json);

		var player = vis.selectAll("circle")
			.data(json)
			.enter()
			.append("circle")
			.attr("cx",function(d) {return weightScale(parseInt(d.Weight))})
			.attr("cy",function(d) {return heightScale(parseInt(d.Height_Inches)+12*parseInt(d.Height_Feet))})
			.attr("r",function(d) {return 10});


	});
</script>
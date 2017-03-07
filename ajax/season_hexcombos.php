<?php
  if(isset($_GET["season"])) {
  	$reqData = "chart_pairings_11_".$_GET["season"].".json";
  } else {
  	$reqData = "chart_pairings_11_2015.json";
  }
?>
<h2>HexCombos</h2>
<p>This is an experimental display of player combinations using a set of hexagonal tiles. It probably will not work on mobile devices.</p>
<p>To read this display, look down the list of players on the left edge. Players are sorted by minutes played; those with the most minutes appear at the top of the list. The hexagonal tiles represent a pair of players - follow the grid back to the left edge to determine which players are combined in each grid. If you hover over a tile with your mouse, the relevant players are highlighted in red. Tiles are color coded according the amount of time that pair has played together. Black cells indicate that the pair has played together the entire season, while white cells indicate few shared minutes. Pairings of players who have <em>never</em> appeared together receive no coloring.</p>
<div id="combinations"></div>
<script type="text/javascript">
// Basic parameters
var i; // counters
var _s32 = (Math.sqrt(3)/2); // constant ratio of hexagon's internal to external radius

var nodeSize = 15; // size of an individual node - this may become auto calculated
var nodeText = 12;
// need to add a label size bsaed on data label lengths

var dx = nodeSize * 1.5; // column spacing value
var dy = nodeSize * _s32; // row spacing value

var w = 960; // total plot width
var h = 900; // total plot height

// need to refactor the margin calculations...
var margin = { // this is meant to provide a margin around the plot
    top: nodeSize,
    right: nodeSize * 1.5,
    bottom: nodeSize * 1.5,
    left: 125
};

var svgContainer; // overall plot container
var label, labels, labelText; // plot labels
var hexagon, hexagons; // plot data appears in hexagons

// create container
svgContainer = d3.select("#combinations")
    .append("svg")
    .attr("width", w)
    .attr("height", h);

// load data, build visualization
d3.json("/fdg/<?php echo $reqData; ?>", function(error, data) {

    if(error) {
        alert("Error loading json file:\n" + error.statusText);
        console.log(error);
    } else {

        // build node labels
        buildLabels(data.nodes);

        // draw hexagon grid
        buildHexagons(data.combinations);

        buildListeners();

    }

});

// Functions
buildHexagons = function(data) {
    var colorRange = d3.scale.linear()
        .domain([0,1620]) // games played together
        .range([255,0]); // color being mapped

    hexagons = svgContainer.append("g")
        .attr("class","hexagons");

    hexagon = hexagons.selectAll("path")
        .data(data)
        .enter()
        .append("path")
            .attr("data-combination",function(d,i) {
                return d.source+" "+d.target;
            })
            .style("fill", function(d) {
                console.log(d.value + " => " + (colorRange(d.value)));
                return "rgb(" + Math.floor(colorRange(d.value)) + "," + Math.floor(colorRange(d.value)) + "," + Math.floor(colorRange(d.value)) + ")";
            })
            .attr("stroke","rgb(255,255,255)")
            .attr("stroke-width","0")
            .attr("d", function(d,i) {
                col = margin.left + ( Math.abs(d.source - d.target) * dx );
                row = ((Math.abs(d.source-d.target)/2) + Math.min(d.source,d.target)) * Math.sqrt(3) * nodeSize + margin.top;
                return setHexagonPoints(col,row,nodeSize);
            })
            .attr("class","cell")
        .append("svg:title")
            .text(function(d){
              return d.value + " minutes played together";  
            });

};

buildLabels = function(data) {
    labels = svgContainer.append("g")
        .attr("class","labels");

    labelText = labels.selectAll("g text")
        .data(data)
        .enter()
        .append("text")
            .attr("x",nodeText)
            .attr("y",function(d,i){
                return (i*dy*2) + dy + nodeText/2;
            })
            .attr("font-size",nodeText  )
            .text(function(d) {
                return d.name;
            }); // label text
        
    label = labels.selectAll("path")
        .data(data)
        .enter()
        .append("path") // label drawing
            .style("fill", "rgba(255,226,90,0)")
            .attr("stroke","rgb(255,255,255)")
            .attr("class","label")
            .attr("data-node",function(d,i){
                return i;
            })
            .attr("d",function(d,i) {
                var startY = i * nodeSize*_s32*2 + (margin.top - nodeSize*_s32);
                var path = "M 0 " + startY + " ";
                path += "H " + (margin.left + dx - nodeSize) + " ";
                path += "L " + (margin.left + dx - nodeSize/2) + " " + (startY + ( _s32*nodeSize ) ) + " ";
                path += "L " + (margin.left + dx - nodeSize) + " " + (startY + ( _s32*nodeSize * 2) ) + " ";
                path += "H 0 ";
                return path;
            })
        .append("svg:title")
            .text(function(d){
                return d.value + " games played";
            });

};

buildListeners = function() {
    $("g.hexagons path").mouseout(function() {
        // reset all labels
        $("g.labels").children("path").each(function() {
            $(this).attr("class","label");
        });
    });
    $("g.labels path").mouseout(function() {
        // reset all labels
        $("g.hexagons").children("path").each(function() {
            $(this).attr("class","cell");
        });
    });

    $("g.hexagons path").mouseover(function() {
        // highlight the labels for the relevant combined node
        var cl = $(this).data("combination").split(" ").sort();
        for(var x in cl){
            // cl[x] = +cl[x];
            var needle = $("g.labels").children("path")[+cl[x]];
            $(needle).attr("class","label active");

        }
    });
    $("g.labels path").mouseover(function() {
        // highlight the hexagons for the relevant label
        var needle = $(this).data("node");
        var haystack = $("g.hexagons path");
        console.log(haystack);
        for (var x = 0; x < haystack.length; x++) {

            var candidate = haystack[x].attributes["data-combination"].value.split(" ").map(Number);
            console.log(needle);
            console.log(candidate);
            console.log(typeof(candidate));

            // need to be able to read the candidate's data-combination attribute
            if(candidate.indexOf(needle) >= 0) {
                console.log("found");
                haystack[x].attributes["class"].value = "cell active";
            } else {
                console.log("not here");
                haystack[x].attributes["class"].value = "cell";
            }
            console.log("");

        }
    });

};

setHexagonPoints = function(x,y,size) {
    var hexPoints = "";
    hexPoints += "M " + (size + x   ) + " " + (0 + y         ) + " ";
    hexPoints += "L " + (size/2 + x ) + " " + (size*_s32 + y ) + " " ;
    hexPoints += "L " + (-size/2 + x) + " " + (size*_s32 + y ) + " " ;
    hexPoints += "L " + (-size + x  ) + " " + (0 + y         ) + " " ;
    hexPoints += "L " + (-size/2 + x) + " " + (-size*_s32 + y) + " " ;
    hexPoints += "L " + (size/2 + x ) + " " + (-size*_s32 + y) + " " ;
    hexPoints += "L " + (size + x   ) + " " + (0 + y         ) + " " ;
    return hexPoints;
};

</script>
<style type="text/css">
#combinations {
	/* background-color: rgba(255,212,60,0.25); */
}
svg {
	font: 10px sans-serif;
}
g.labels text {
	fill: rgb(238,238,238);
}
.label:hover {
	stroke: red;
	stroke-width: 2px;
}
.cell:hover {
	stroke: red;
	stroke-width: 2px;
}
.active {
	stroke: red;
	stroke-width: 2px;
}
</style>
<p class="builtwith">Built with <a href="http://d3js.org/">d3.js</a>, a project by <a href="http://bost.ocks.org/mike/">Mike Bostock</a>.</p>
var competitionControllers = angular.module('competitionControllers', []);

competitionControllers.controller('CompetitionController', ['$http', function($http) {
  console.log("Building competition plot...");

  $http.get('/api/team-honors.json').success(function(data) {
    plotHonors(data);
  });
}]);

function plotHonors(data) {
  console.log('Plotting competitions...');

  var cell = {
    width: 40,
    height: 20,
    padding: 5
  };

  var margin = {
    top: 20,
    right: 90,
    bottom: 0,
    left: 90
  };

  var labelDistance = 0;

  var vis = d3.select("div#chart").append("svg:svg");

  // tooltip
  var tooltip = d3.select("div.tooltip")
    .style("opacity",0);

  // team names in left column
  var teamHeading = vis.append("g")
    .attr("class","teamLabels");

  // team data in right column
  var teamData = vis.append("g")
    .attr("class","teamData");

  // years in top row
  var yearHeading = vis.append("g")
    .attr("class","yearLabels");

  // background
  var background = vis.append("svg:rect");

  // cell container
  var cellContainer = vis.append("g");

  var cells,
    cellRect,
    cellText;

  var teamNames = d3
    .set(data.map(function(d) { return d.name; }))
    .values()
    .sort();

  var yearNames = d3
    .set(data.map(function(d) { return d.year; }))
    .values()
    .sort();

  w = margin.left + margin.right + (yearNames.length * cell.width);
  h = margin.top + margin.bottom + (teamNames.length * cell.height);

  vis.attr("width", w).attr("height", h);

  // team labels on left column
  teamHeading.selectAll("rect")
    .data(teamNames)
    .enter()
    .append("svg:text")
    .text(function(d) {
      return d;
    })
    .attr("x",0)
    .attr("y",function(d,i) {
      return margin.top + ( (i + 1) * cell.height ) - cell.padding;
    })
    .attr("font-family","Arial, sans-serif")
    .attr("font-size","12px");

  // team data
  teamData.selectAll("text")
    .data(teamNames)
    .enter()
    .append("text")
    .attr("x",function(d) {
      return w - margin.right + cell.padding;
    })
    .attr("y",function(d,i) {
      return margin.top + ( (i + 1) * cell.height ) - cell.padding;
    })
    .attr("font-family","Arial, sans-serif")
    .attr("font-size","12px")
    .attr("fill","black")
    .text(function(d) {
      return d;
    });

  // year labels in top row
  yearHeading.selectAll("rect")
    .data(yearNames)
    .enter()
    .append("svg:text")
    .text(function(d) { 
      return d;
    })
    .attr("x",function(d,i) {
      return margin.left + (i * cell.width) + cell.padding;
    })
    .attr("y",cell.height - cell.padding)
    .attr("font-family","Arial, sans-serif")
    .attr("font-size","12px");

  // background
  background
    .attr("x",margin.left)
    .attr("y",margin.top)
    .attr("width", yearNames.length * cell.width)
    .attr("height", teamNames.length * cell.height)
    .attr("fill","#ddd");


}
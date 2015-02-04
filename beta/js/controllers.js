var playerControllers = angular.module('playerControllers', []);

playerControllers.controller('ListController', ['$scope', '$http', function($scope, $http) {
    $scope.search = {
        "PlayerName" : "",
        "Position" : {},
        "Citizenship" : {},
        "BirthYear" : {},
        "Seasons" : {}
    };
    $scope.pane = 'table';
	$http.get('/api/players-crew.json').success(function(data) {
		$scope.players = data;

        $scope.Positions = [];
        $scope.Positions = summarize(data,'Position');

        $scope.Citizenships = [];
        $scope.Citizenships = summarize(data,'Citizenship');

        $scope.BirthYear = [];
        $scope.BirthYear = summarize(data,'BirthYear');

        $scope.Seasons = [];
        $scope.Seasons = summarizeMulti(data,'Seasons');

		$scope.predicate = 'LastName';
        $scope.reverse = false;

        plot();
	});
}]);

playerControllers.controller('DetailsController', ['$scope', '$http', '$routeParams', '$log', function($scope, $http, $routeParams, $log) {
	$scope.playerId = $routeParams.playerId;
	$log.log('Looking for ' + $scope.playerId);
	$http.get('/api/player.php?term=' + $scope.playerId).success(function(data) {
		$log.log(data);
		$scope.player = data;
	}).error(function(data) {
		$log.log('Failure receiving data from API');
	});
}]);

myApp.filter('playerFilter', function() {
    return function( list, searchobj ) {

        if (list != undefined) {
            
            return list.filter( function( item ) {

                // Check for filters set
                var any_filter_set = false;
                if ( searchobj.hasOwnProperty('PlayerName') && searchobj.PlayerName != "" ) {
                    any_filter_set = true;
                }
                for ( Position in searchobj.Position) {
                    any_filter_set = any_filter_set || searchobj.Position[ Position ];
                }
                for ( Citizenship in searchobj.Citizenship) {
                    any_filter_set = any_filter_set || searchobj.Citizenship[ Citizenship ];
                }
                for ( BirthYear in searchobj.BirthYear) {
                    any_filter_set = any_filter_set || searchobj.BirthYear[ BirthYear ];
                }
                for ( Season in searchobj.Seasons) {
                    any_filter_set = any_filter_set || searchobj.Seasons[ Season ];
                }
                // If any_filter_set is still false, just pass everything through
                if ( !any_filter_set ) { return !any_filter_set; }

                // Still here? Do the filters pass?
                var any_value_set = false;
                var passes_filters = false;

                if ( searchobj.PlayerName != "" && item.PlayerName.indexOf(searchobj.PlayerName) === -1 ) {
                    return false;
                }

                for ( Position in searchobj.Position ) {
                    any_value_set = any_value_set || searchobj.Position[Position];
                    passes_filters = passes_filters || (searchobj.Position[ Position ] && item.Position == Position);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                any_value_set = false;
                passes_filters = false;
                for ( Citizenship in searchobj.Citizenship ) {
                    any_value_set = any_value_set || searchobj.Citizenship[ Citizenship ];
                    passes_filters = passes_filters || (searchobj.Citizenship[ Citizenship ] && item.Citizenship == Citizenship);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                any_value_set = false;
                passes_filters = false;
                for ( BirthYear in searchobj.BirthYear ) {
                    any_value_set = any_value_set || searchobj.BirthYear[ BirthYear ];
                    passes_filters = passes_filters || (searchobj.BirthYear[ BirthYear ] && item.BirthYear == BirthYear);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                any_value_set = false;
                passes_filters = false;
                for ( Season in searchobj.Seasons ) {
                    any_value_set = any_value_set || searchobj.Seasons[ Season ];
                    for (var i = 0; i < item.Seasons.length; i++ ) {
                        passes_filters = passes_filters || (searchobj.Seasons[ Season ] && item.Seasons[i] == Season);
                    }
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                return true;
            } );

        }
    };
});

function plot() {
    var w = $("header").width()*0.8;
    var h = w * (3/4);

    var margin = {
        top: 50,
        right: 20,
        bottom: 40,
        left: 40
    };

    // chart
    var vis = d3.select("#plot")
        .append("svg:svg")
        .attr("width",w)
        .attr("height",h);

    // weight on horizontal / w / x scale
    var weightScale = d3.scale.linear()
        .domain([130,240])
        .range([margin.left,w-margin.right]);

    // height on vertical / h / y scale
    var heightScale = d3.scale.linear()
        .domain([81,60])
        .range([margin.top,h-margin.bottom]);

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

    // All player container
    var ac = vis.append("g").attr("class","ac");

    // Player container
    var pc = vis.append("g").attr("class","pc");

}

function summarize(object, key) {
    var temparray = [];
    temparray = object.reduce(function(prev, curr, index, array) {
        return temparray.indexOf(curr[key]) === -1 ? temparray.push(curr[key]) : temparray;
    });
    return temparray.sort();
}

function summarizeMulti(object, key) {
    var temparray = [];
    temparray = object.reduce(function(prev, curr, index, array) {
        var tempMulti = curr[key];
        var tempMultiLength = tempMulti.length;
        for (var i = 0; i < tempMultiLength; i++ ) {
            if(temparray.indexOf(tempMulti[i]) === -1) {
                temparray.push(tempMulti[i])
            }
        }
        return temparray;
    });
    return temparray.sort();
}
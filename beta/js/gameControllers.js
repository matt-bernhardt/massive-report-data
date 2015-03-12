var gameControllers = angular.module('gameControllers', []);

gameControllers.controller('GameListController', ['$scope', '$http', function($scope, $http) {
    $scope.search = {
        "HomeAway" : {},
        "Seasons" : {},
        "Competitions" : {},
        "Opponents" : {}
    };
    $scope.pane = 'table';
	$http.get('/api/games-crew.json').success(function(data) {

        console.log("Get...");

		$scope.games = data;

        $scope.HomeAway = [];
        $scope.HomeAway = summarize(data,'HomeAway');

        $scope.Seasons = [];
        $scope.Seasons = summarize(data,'MatchYear');

        $scope.Competitions = [];
        $scope.Competitions = summarize(data,'CompetitionType');

        $scope.Opponents = [];
        $scope.Opponents = summarize(data,'Opponent');

        $scope.reverse = false;

        plot();
	});
}]);

myApp.filter('gameFilter', function() {
    return function( list, searchobj ) {

        console.log("Hello!");

        if (list != undefined) {
            
            console.log("there!");

            return list.filter( function( item ) {

                console.log("beautiful!");

                // Check for filters set
                var any_filter_set = false;
                for ( Home in searchobj.HomeAway) {
                    any_filter_set = any_filter_set || searchobj.HomeAway[ Home ];
                }
                for ( Season in searchobj.Seasons) {
                    any_filter_set = any_filter_set || searchobj.Seasons[ Season ];
                }
                for ( Competition in searchobj.Competitions) {
                    any_filter_set = any_filter_set || searchobj.Competitions[ Competition ];
                }
                for ( Opponent in searchobj.Opponents) {
                    any_filter_set = any_filter_set || searchobj.Opponents[ Opponent ];
                }

                console.log("life!");

                // If any_filter_set is still false, just pass everything through
                if ( !any_filter_set ) { return !any_filter_set; }

                // Still here? Do the filters pass?
                var any_value_set = false;
                var passes_filters = false;

                // Home/Away
                any_value_set = false;
                passes_filters = false;
                for ( Home in searchobj.HomeAway ) {
                    any_value_set = any_value_set || searchobj.HomeAway[Home];
                    passes_filters = passes_filters || (searchobj.HomeAway[ Home ] && item.HomeAway == Home);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                // Seasons
                any_value_set = false;
                passes_filters = false;
                for ( Season in searchobj.Seasons ) {
                    any_value_set = any_value_set || searchobj.Seasons[Season];
                    passes_filters = passes_filters || (searchobj.Seasons[ Season ] && item.MatchYear == Season);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                // Competitions
                any_value_set = false;
                passes_filters = false;
                for ( Competition in searchobj.Competitions ) {
                    any_value_set = any_value_set || searchobj.Competitions[Competition];
                    passes_filters = passes_filters || (searchobj.Competitions[ Competition ] && item.CompetitionType == Competition);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                // Opponents
                any_value_set = false;
                passes_filters = false;
                for ( Opponent in searchobj.Opponents ) {
                    any_value_set = any_value_set || searchobj.Opponents[Opponent];
                    passes_filters = passes_filters || (searchobj.Opponents[ Opponent ] && item.Opponent == Opponent);
                }
                if( any_value_set && !passes_filters ) {
                    return false;
                }

                return true;
            } );

        }
    };
});

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
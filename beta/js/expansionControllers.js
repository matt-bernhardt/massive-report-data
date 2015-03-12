var expansionControllers = angular.module('expansionControllers', []);

expansionControllers.controller('ExpansionController', ['$scope', '$http', function($scope, $http) {
    $scope.search = {
        "PlayerName" : "",
        "Position" : {},
        "Citizenship" : {},
        "BirthYear" : {},
        "Teams" : {}
    };
	$http.get('/api/expansion2014.json').success(function(data) {
		$scope.players = data;

        $scope.Positions = [];
        $scope.Positions = summarize(data,'Position');

        $scope.Citizenships = [];
        $scope.Citizenships = summarize(data,'Citizenship');

        $scope.BirthYear = [];
        $scope.BirthYear = summarize(data,'BirthYear');

        $scope.Teams = [];
        $scope.Teams = summarizeMulti(data,'Teams');

		$scope.predicate = 'LastName';
        $scope.reverse = false;

	});
}]);

myApp.filter('playerFilter', function() {
    return function( list, searchobj ) {

        console.log("<hello>");

        if (list != undefined) {
            
            return list.filter( function( item ) {

                console.log("<beautiful>");

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
                for ( Team in searchobj.Teams) {
                    any_filter_set = any_filter_set || searchobj.Teams[ Team ];
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
                for ( Team in searchobj.Teams ) {
                    any_value_set = any_value_set || searchobj.Teams[ Team ];
                    for (var i = 0; i < item.Teams.length; i++ ) {
                        passes_filters = passes_filters || (searchobj.Teams[ Team ] && item.Teams[i] == Team);
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
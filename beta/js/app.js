var myApp = angular.module('myApp', [
	'ngRoute',
	'playerControllers',
	'gameControllers',
	'expansionControllers'
]);

myApp.config(['$routeProvider', function($routeProvider) {
	$routeProvider.
	when('/index', {
		templateUrl: 'partials/index.html',
		conroller: 'ListController'
	}).
	when('/player/:playerId', {
		templateUrl: 'partials/player.html',
		controller: 'DetailsController'
	}).
	when('/players', {
		templateUrl: 'partials/players.html',
		controller: 'ListController'
	}).
	when('/games', {
		templateUrl: 'partials/games.html',
		controller: 'GameListController'
	}).
	when('/expansion', {
		templateUrl: 'partials/expansion.html',
		controller: 'ExpansionController'
	}).
	otherwise({
		redirectTo: '/index'
	});
}]);
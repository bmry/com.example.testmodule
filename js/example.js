(function(angular, $, _){

var resourceUrl = CRM.resourceUrls['com.example.testmodule'];
var example= angular.module('example',['ngRoute']);

example.config(['$routeProvider',
	function($routeProvider){
	$routeProvider.when('/example',{
		templateUrl:resourceUrl + '/partials/example.html',
		controller:'ExampleCtrl'
	});
}


	]);

example.controller('ExampleCtrl',function($scope){
	$scope.name = 'world';
});

})(angular,CRM.$,CRM._);
swdApp.controller('HorMenuCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.siteList=[];
	$scope.init=function()
	{
		$http.get(window.cfg.apiUrl + "alclair/get.php").success(function (result) {
            $scope.siteList = result.data;
        });
	}
	$scope.init();
}]);
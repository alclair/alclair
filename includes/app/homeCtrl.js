swdApp.controller('homeCtrl', ['$http', '$scope', '$location', function ($http, $scope, $location) {
    $scope.siteList = {};


    var api_url = window.cfg.apiUrl + "alclair/get.php";
    $http.get(api_url).success(function (result) {
        $scope.siteList = result.data;
    });

    $scope.logout = function () {
        window.location.href = window.cfg.rootUrl + "/account/logout";
    };

}]);

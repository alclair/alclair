swdApp.controller('adminSettingsCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.vm = {};

    $scope.LoadData = function () {
        var api_url = window.cfg.rootUrl + "/api/settings/get.php";
        $http.get(api_url).success(function (data) {

            $scope.vm.site_name = data.site_name;
            $scope.vm.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.vm.maximum_barrel_warning = data.maximum_barrel_warning;
            $scope.vm.image_with_ticket = data.image_with_ticket;
            $scope.vm.allow_duplicate_tickets = data.allow_duplicate_tickets;
            
            		if ($scope.vm.image_with_ticket == true) {
				    	$scope.vm.image_with_ticket = 1;
				    	$scope.vm.test3 = 1;    
			        } else {
				        $scope.vm.image_with_ticket = 0;
				        $scope.vm.test3 = 0;
			        }
			        
            		if ($scope.vm.allow_duplicate_tickets == true) {
				    	$scope.vm.allow_duplicate_tickets = 1;
				    	$scope.vm.test4 = 1;    
			        } else {
				        $scope.vm.allow_duplicate_tickets = 0;
				        $scope.vm.test4 = 0;
			        }
        });
    }
    $scope.SaveData = function () {
        var api_url = window.cfg.rootUrl + "/api/settings/save.php";
        //alert(api_url+"?"+$.param($scope.vm));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (data) {
             //console.log(data);	

             if (data.code == "success") {
                 toastr.success("Settings are saved successfully.");
             }
             else {
                 toastr.error(data.message);
             }
         });
    }
    $scope.init = function () {
        $scope.LoadData();
    }
    $scope.init();
}]);
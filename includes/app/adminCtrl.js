swdApp.controller('adminCtrl', ['$http', '$scope', 'Session', function ($http, $scope, Session) {
    
}]);



swdApp.controller('adminSiteCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.siteList = {};

   
        $.blockUI();
        var api_url = window.cfg.apiUrl + "site/get.php";
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.siteList = result.data;
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get site error.");
        });
   
}]);



swdApp.controller('adminDisposalWellCtrl', ['$http', '$scope', 'Session', 'AppDataService', function ($http, $scope, Session, AppDataService) {
    $scope.disposalWellList = {};

    if (Session.isAuthenticated() == true) {
        AppDataService.loadDisposalWellList(null, function () {
            $.blockUI();
        }, function (result) {
            $.unblockUI();
            $scope.disposalWellList = result.data;
        }, function (result) {
            $.unblockUI();
            toastr.error("Get disposal well error.");
        });
    }
}]);



swdApp.controller('adminUserCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.userList = {};

   
        $.blockUI();
        var api_url = window.cfg.apiUrl + "users/get.php";
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.userList = result.data;
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get user error.");
        });
}]);
swdApp.controller('adminUserEditCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.vm = {};

        $.blockUI();
        var api_url = window.cfg.apiUrl + "users/get.php?id="+window.cfg.Id ;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.vm = result.data;
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get user error.");
        });
		$scope.SaveData = function () {		
	   var api_url = window.cfg.apiUrl + "users/update.php?id="+window.cfg.Id;
        $.blockUI();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
				 
                 $.unblockUI(); 				 
                 toastr.success(result.message );
             }
            
         }).error(function (data) {
             toastr.error("Update user error.");

         });
    }
}]);

swdApp.controller('adminUserAddCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.vm = {};
	 
	$scope.submitData = function () {
		alert($scope.vm.username);
	   var api_url = window.cfg.apiUrl + "users/add.php";
        $.blockUI();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
				 
                 $.unblockUI(); 				 
                 toastr.success(result.message );
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");

         });
    }
}]);
 
swdApp.controller('adminTruckingCompanyCtrl', ['$http', '$scope', 'Session', 'AppDataService', function ($http, $scope, Session, AppDataService) {
    $scope.truckingcompanyList = {};

    if (Session.isAuthenticated() == true) {
        AppDataService.loadTruckingCompanyList(null, function () {
            $.blockUI();
        }, function (result) {
            $.unblockUI();
            $scope.truckingcompanyList = result.data;
        }, function (result) {
            $.unblockUI();
            toastr.error("Get trucking company error.");
        });
    }
}]);

swdApp.controller('adminOperatorCtrl', ['$http', '$scope',  function ($http, $scope) {
   
    $scope.operatorList = {};
	$scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
	if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
		 $scope.LoadData = function () {
        $.blockUI();
		var api_url = window.cfg.apiUrl + "operators/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.operatorList = result.data;
			$scope.TotalPages = result.TotalPages;
			$scope.TotalRecords = result.TotalRecords;

			$scope.PageRange = [];
			$scope.PageWindowStart = (Math.ceil($scope.PageIndex / $scope.PageWindowSize) - 1) * $scope.PageWindowSize + 1;
			$scope.PageWindowEnd = $scope.PageWindowStart + $scope.PageWindowSize - 1;
			if ($scope.PageWindowEnd > $scope.TotalPages) {

				$scope.PageWindowEnd = $scope.TotalPages;

			}
			for (var i = $scope.PageWindowStart; i <= $scope.PageWindowEnd; i++) {

				$scope.PageRange.push(i);

			}
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get operator error.");
        });
		 }
		 
	$scope.GoToPage = function (v) {

        $scope.PageIndex = v;
        $scope.LoadData();

    };	 
	 $scope.init = function () {

	$scope.LoadData();

    }
    $scope.init();

}]);
swdApp.controller('adminOperatorEditCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.vm = {};
	 $scope.LoadData = function () {
        $.blockUI();
		var api_url = window.cfg.apiUrl + "operators/get.php?id="+window.cfg.Id;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.vm = result.data;
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get operator error.");
        });
	}
	 $scope.init = function () {

	$scope.LoadData();

    }
    $scope.init();
	$scope.SaveData = function () {
		
	   var api_url = window.cfg.apiUrl + "operators/update.php?id="+window.cfg.Id;
        $.blockUI();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
				 
                 $.unblockUI(); 				 
                 toastr.success(result.message );
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Update operator error.");

         });
    }
}]);
swdApp.controller('adminOperatorAddCtrl', ['$http', '$scope',  function ($http, $scope) {
    $scope.vm = {};
	 
	$scope.SubmitData = function () {
		
	   var api_url = window.cfg.apiUrl + "operators/add.php";
        $.blockUI();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
				 
                 $.unblockUI(); 				 
                 toastr.success(result.message );
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");

         });
    }
}]);
 


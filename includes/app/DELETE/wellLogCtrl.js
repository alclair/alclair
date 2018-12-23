swdApp.controller('wellLogCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.wellLogList = {};    
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = window.cfg.disposal_well_id;

    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "welllog/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText;
		//alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                $scope.wellLogList = result.data;
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

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get welllog error.");
            });
    };

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {
        
        $scope.LoadData();
    };

    $scope.LoadData();


    $scope.deleteWellLog = function (id) {
        console.log(id);
        if (confirm("Are you sure to delete this well log?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "welllog/delete.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.wellLogList.length; i++) {
                if ($scope.wellLogList[i].id == id) {
                    toastr.success("Delete well log successful!", "Message");
                    $scope.wellLogList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete well log, please try again.");
        });
    };

}]);




swdApp.controller('wellLogAddCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.greeting = 'Hola!';
    $scope.welllog = {
        disposal_well_id: 0,
        date_logged: new Date(),
        level_skim_tank_1_ft: 0,
        level_skim_tank_2_ft: 0,
        level_oil_tank_1_ft: 0,
        level_oil_tank_2_ft: 0,
        level_oil_tank_3_ft: 0,
        level_gun_ft: 0,
        flowmeter_barrels: 0,
        injection_rate: 0,
        injection_pressure: 0,
        oil_sold_barrels: 0,
        water_received_barrels: 0,
		pipeline1_starting_total:0,
		pipeline1_ending_total:0,
		pipeline2_starting_total:0,
		pipeline2_ending_total:0,
        notes: '',
    };

    $scope.action = window.cfg.Action;
    $scope.vm = {
        title: $scope.action == "add" ? "Create a New Well Log" : "Edit Well Log",
    };

    $scope.disposalWellList = [];
    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
    }, function (result) {
    });


    if (window.cfg.Id > 0) {
        myblockui();
        var api_url = window.cfg.apiUrl + "welllog/get.php?id=" + window.cfg.Id;
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
                    $scope.welllog = result.data[0];
					$scope.welllog.pipeline1_starting_total=parseFloat($scope.welllog.pipeline1_starting_total);
					$scope.welllog.pipeline1_ending_total=parseFloat($scope.welllog.pipeline1_ending_total);
					$scope.welllog.pipeline2_starting_total=parseFloat($scope.welllog.pipeline2_starting_total);
					$scope.welllog.pipeline2_ending_total=parseFloat($scope.welllog.pipeline2_ending_total);
					//console.log($scope.welllog);
                    if ($scope.action == "edit") {
                        $scope.welllog.date_logged = new Date($scope.welllog.date_logged);
                    }
                    //console.log($scope.welllog);
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get welllog error.");
            });
    }

    $scope.open = function ($event) {
        $scope.opened = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];

    $scope.getLastLog=function()
	{
		if($scope.action == "add")
		{
			var api_url = window.cfg.apiUrl + "welllog/getLastLog.php?disposal_well_id=" + $scope.welllog.disposal_well_id;
			//alert(api_url);
			$http.get(api_url)
				.success(function (result) {
					//console.log(result);				
					$scope.welllog.pipeline1_starting_total=parseFloat(result.pipeline1_starting_total);
					$scope.welllog.pipeline2_starting_total=parseFloat(result.pipeline2_starting_total);
				}).error(function (result) {                
					toastr.error("Get welllog error.");
				});
		}
	}
    $scope.add = function () {
        //$scope.welllog.date_logged = $scope.welllog.date_logged.getTime() / 1000;
        //$scope.welllog.date_logged = $scope.welllog.date_logged.getFullYear() + "-" + ($scope.welllog.date_logged.getMonth() + 1) + "-" + $scope.welllog.date_logged.getDate();
        if (!isEmpty($scope.welllog.date_logged)) {
            $scope.welllog.date_logged = moment($scope.welllog.date_logged).format("MM/DD/YYYY");
        }
       // console.log($scope.welllog);        

        var api_url = window.cfg.apiUrl + 'welllog/add.php';
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.welllog),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             //console.log(result);

             if (result.code == "success") {
                 $.unblockUI();
                 window.location.href = window.cfg.rootUrl + "/welllog/index";
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert welllog error.");

         });
    };

    $scope.edit = function () {
        var api_url = window.cfg.apiUrl + 'welllog/update.php';
        //$scope.welllog.date_logged = $scope.welllog.date_logged.getFullYear() + "-" + ($scope.welllog.date_logged.getMonth() + 1) + "-" + $scope.welllog.date_logged.getDate();
        if (!isEmpty($scope.welllog.date_logged)) {
            $scope.welllog.date_logged = moment($scope.welllog.date_logged).format("MM/DD/YYYY");
        }
        $scope.welllog.Id = window.cfg.Id;

        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.welllog),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/welllog/index";
                 toastr.success("Edit successful!", "Message");
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Update welllog error.");

         });
    };

}]);
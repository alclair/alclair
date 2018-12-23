swdApp.controller('Maintenance_Start', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
	    
    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {

        $scope.LoadData();
    }
    $scope.LoadData = function () {
        myblockui();
		
        var api_url = window.cfg.apiUrl + "user_logs/get_maintenance.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	             $scope.ticket = result.data[0];
	             console.log(result.testing); 
				 $scope.ticket.date_created = moment($scope.ticket.date_created).format("MM/DD/YYYY");
				 $scope.date_created = moment($scope.ticket.date_created).format("MM/DD/YYYY");

	            console.log(result.testing);
	            if ($scope.ticket.unload_pump_oil_yn == true) {
				    	$scope.ticket.unload_pump_oil_yn = 1
				    	$scope.ticket.test1=1;    
			        } else {
				        $scope.ticket.unload_pump_oil_yn = 0;
				        $scope.ticket.test1=0;
			        }
			     if ($scope.ticket.tank_battery_lines_yn == true) {
				    	$scope.ticket.tank_battery_lines_yn = 1
				    	$scope.ticket.test2=1;    
			    } else {
				        $scope.ticket.tank_battery_lines_yn = 0;
				        $scope.ticket.test2=0;
			        }
			    if ($scope.ticket.lubricator_working_yn == true) {
				    	$scope.ticket.lubricator_working_yn = 1
				    	$scope.ticket.test3=1;    
			    } else {
				        $scope.ticket.lubricator_working_yn = 0;
				        $scope.ticket.test3=0;
			        }			        
			     if ($scope.ticket.oil_in_triplex_yn == true) {
				    	$scope.ticket.oil_in_triplex_yn = 1
				    	$scope.ticket.test4=1;    
			    } else {
				        $scope.ticket.oil_in_triplex_yn = 0;
				        $scope.ticket.test4=0;
			        }
				if ($scope.ticket.leaks_plungers_valves_pipes_fittings_yn == true) {
				    	$scope.ticket.leaks_plungers_valves_pipes_fittings_yn = 1
				    	$scope.ticket.test5=1;    
			    } else {
				        $scope.ticket.leaks_plungers_valves_pipes_fittings_yn = 0;
				        $scope.ticket.test5=0;
			        }
				if ($scope.ticket.pump_house_filters_yn == true) {
				    	$scope.ticket.pump_house_filters_yn = 1
				    	$scope.ticket.test6=1;    
			    } else {
				        $scope.ticket.pump_house_filters_yn = 0;
				        $scope.ticket.test6=0;
			        }

					$.unblockUI();
            }).error(function (result) {
                toastr.error("Get list error");
            });
    };
    
    $scope.Return = function () {
        //var api_url = window.cfg.apiUrl + 'user_logs/update_close.php';
        
        //$scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	window.location.href = window.cfg.rootUrl + "/user_logs/index/";
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

 
	$scope.SaveData = function () {
        var api_url = window.cfg.apiUrl + 'user_logs/update_maintenance.php';
        
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	toastr.success("Log saved successfully.");
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

	$scope.CloseLog = function () {
        var api_url = window.cfg.apiUrl + 'user_logs/update_maintenance_close.php';
        
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	window.location.href = window.cfg.rootUrl + "/user_logs/log_saved/";
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };
    
    
    $scope.init = function () {
    	$scope.LoadData();
    }
    $scope.init();
}]);

swdApp.controller('displayMaintenanceLogsCtrl', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
    $scope.ticketList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
	$scope.openEnd = function ($event) {        
        $scope.openedEnd = true;
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
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";

        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");

    }
    $scope.LoadData = function () {
        myblockui();
        $cookies.put("SearchText", $scope.SearchText);
        $cookies.put("SearchUserLogTypes", $scope.SearchUserLogTypes);
		$cookies.put("SearchStartDate",$scope.SearchStartDate);
		$cookies.put("SearchEndDate",$scope.SearchEndDate);
        var api_url = window.cfg.apiUrl + "user_logs/get_all.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+ "&SearchUserLogTypes="+$scope.SearchUserLogTypes ;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                $scope.ticketList = result.data;
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
                toastr.error("Get tickets error.");
            });
    };

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };

	$scope.LoadUserLogTypes=function()
    {
        $http.get(window.cfg.apiUrl + "user_logs/get_logtypes.php").success(function (result) {
            $scope.UserLogTypes = result.data;
			if(!isEmpty(window.cfg.type_id))
			{		
				$scope.SearchUserLogTypes=window.cfg.type_id;
			}
        });
    }

    
    $scope.init = function () {
        $scope.SearchText = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
		if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}

        $scope.SearchUserLogTypes = $cookies.get("SearchUserLogTypes");
        if (isEmpty($scope.SearchUserLogTypes)) $scope.SearchUserLogTypes = "0";
        $scope.LoadData();
        $scope.LoadUserLogTypes();
    }
    $scope.init();
}]);

/*swdApp.controller('Maintenance_Admin', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
	    
    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];

    $scope.LoadData = function () {
        myblockui();
		
        var api_url = window.cfg.apiUrl + "user_logs/get_maintenance.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.ticket = result.data[0];
	            console.log(result.data[0]);
	            if ($scope.ticket.unload_pump_oil_yn == true) {
				    	$scope.ticket.unload_pump_oil_yn = 1
				    	$scope.ticket.test1=1;    
			        } else {
				        $scope.ticket.unload_pump_oil_yn = 0;
				        $scope.ticket.test1=0;
			        }
			     if ($scope.ticket.tank_battery_lines_yn == true) {
				    	$scope.ticket.tank_battery_lines_yn = 1
				    	$scope.ticket.test2=1;    
			    } else {
				        $scope.ticket.tank_battery_lines_yn = 0;
				        $scope.ticket.test2=0;
			        }
			    if ($scope.ticket.lubricator_working_yn == true) {
				    	$scope.ticket.lubricator_working_yn = 1
				    	$scope.ticket.test3=1;    
			    } else {
				        $scope.ticket.lubricator_working_yn = 0;
				        $scope.ticket.test3=0;
			        }			        
			     if ($scope.ticket.oil_in_triplex_yn == true) {
				    	$scope.ticket.oil_in_triplex_yn = 1
				    	$scope.ticket.test4=1;    
			    } else {
				        $scope.ticket.oil_in_triplex_yn = 0;
				        $scope.ticket.test4=0;
			        }
				if ($scope.ticket.leaks_plungers_valves_pipes_fittings_yn == true) {
				    	$scope.ticket.leaks_plungers_valves_pipes_fittings_yn = 1
				    	$scope.ticket.test5=1;    
			    } else {
				        $scope.ticket.leaks_plungers_valves_pipes_fittings_yn = 0;
				        $scope.ticket.test5=0;
			        }
				if ($scope.ticket.pump_house_filters_yn == true) {
				    	$scope.ticket.pump_house_filters_yn = 1
				    	$scope.ticket.test6=1;    
			    } else {
				        $scope.ticket.pump_house_filters_yn = 0;
				        $scope.ticket.test6=0;
			        }

					$.unblockUI();
            }).error(function (result) {
                toastr.error("Get list error");
            });
    };
 
	$scope.Return = function () {
        //var api_url = window.cfg.apiUrl + 'user_logs/update_close.php';
        
        //$scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	window.location.href = window.cfg.rootUrl + "/user_logs/index/";
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };
    
    $scope.init = function () {
    	$scope.LoadData();
    }
    $scope.init();
}]);*/

swdApp.controller('Inventory_Start', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
	    
    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {

        $scope.LoadData();
    }
    $scope.LoadData = function () {
        myblockui();
		
        var api_url = window.cfg.apiUrl + "user_logs/get_inventory.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	             $scope.ticket = result.data[0];
				 $scope.ticket.date_created = moment($scope.ticket.date_created).format("MM/DD/YYYY");
				 $scope.date_created = moment($scope.ticket.date_created).format("MM/DD/YYYY");

	            console.log(result.testing);
	            if ($scope.ticket.leaks_yn == true) {
				    	$scope.ticket.leaks_yn = 1
				    	$scope.ticket.test1=1;    
			        } else {
				        $scope.ticket.leaks_yn = 0;
				        $scope.ticket.test1=0;
			        }
			     if ($scope.ticket.pump_conditions_yn == true) {
				    	$scope.ticket.pump_conditions_yn = 1
				    	$scope.ticket.test2=1;    
			    } else {
				        $scope.ticket.pump_conditions_yn = 0;
				        $scope.ticket.test2=0;
			        }
			    if ($scope.ticket.meters_yn == true) {
				    	$scope.ticket.meters_yn = 1
				    	$scope.ticket.test3=1;    
			    } else {
				        $scope.ticket.meters_yn = 0;
				        $scope.ticket.test3=0;
			        }			        
			     if ($scope.ticket.fire_extinguishers_yn == true) {
				    	$scope.ticket.fire_extinguishers_yn = 1
				    	$scope.ticket.test4=1;    
			    } else {
				        $scope.ticket.fire_extinguishers_yn = 0;
				        $scope.ticket.test4=0;
			        }
				if ($scope.ticket.eye_wash_station_yn == true) {
				    	$scope.ticket.eye_wash_station_yn = 1
				    	$scope.ticket.test5=1;    
			    } else {
				        $scope.ticket.eye_wash_station_yn = 0;
				        $scope.ticket.test5=0;
			        }
				if ($scope.ticket.signs_still_up_yn == true) {
				    	$scope.ticket.signs_still_up_yn = 1
				    	$scope.ticket.test6=1;    
			    } else {
				        $scope.ticket.signs_still_up_yn = 0;
				        $scope.ticket.test6=0;
			        }
				if ($scope.ticket.spills_yn == true) {
				    	$scope.ticket.spills_yn = 1
				    	$scope.ticket.test6=1;    
			    } else {
				        $scope.ticket.spills_yn = 0;
				        $scope.ticket.test7=0;
			        }
					$.unblockUI();
            }).error(function (result) {
                toastr.error("Get list error");
            });
    };
 
	$scope.Return = function () {
        //var api_url = window.cfg.apiUrl + 'user_logs/update_close.php';
        
        //$scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	window.location.href = window.cfg.rootUrl + "/user_logs/index/";
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

	$scope.SaveData = function () {
        var api_url = window.cfg.apiUrl + 'user_logs/update_inventory.php';
        
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	toastr.success("Log saved successfully.");
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

	$scope.CloseLog = function () {
        var api_url = window.cfg.apiUrl + 'user_logs/update_inventory_close.php';
        
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
			 	window.location.href = window.cfg.rootUrl + "/user_logs/log_saved/";
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };
    
    
    $scope.init = function () {
    	$scope.LoadData();
    }
    $scope.init();
}]);

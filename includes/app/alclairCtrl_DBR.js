swdApp.controller('Daily_Build_Rate', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.holidays = [];
    $scope.day_to_view = 0;
    var holiday_IDs = [];
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
   	//$scope.SearchStartDate=window.cfg.CurrentDay;
   	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;//OctoberOne;
	$scope.SearchEndDate=window.cfg.CurrentDay;
   	$scope.openStartDay = function ($event) {        
        $scope.openedStartDay = true;
    };
	$scope.openEndDay = function ($event) {        
        $scope.openedEndDay = true;
    };

	$scope.openStart = function ($event, index) {        
        $scope.openedStart = true;
        console.log("INDEX IS " + index)
    };
    //$scope.openStart = function ($event) {        
    //    $scope.openedStart = true;
    //};

	$scope.openStart2 = function ($event) {        
        $scope.openedStart2 = true;
    };


 $scope.greeting = 'Hola!';
 $scope.daily = {
 	ticket_number: '',
    artwork_none: 0,
	repair_date: new Date,
	holiday_date: window.cfg.CurrentDay,
	estimated_ship_date: window.cfg.CurrentDay_plus_2weeks,
};

	$scope.celebrate = [];
    //$count_holidays = 0;
    $scope.newHoliday = function($event){
        // prevent submission
        $count_holidays = $scope.holidays.length;
        $event.preventDefault();
        $scope.holidays.push({});
        $scope.holidays[$count_holidays] = {
			holiday_date: window.cfg.CurrentDay,
			//fault_design: 1,
		}
        $count_holidays = $count_holidays + 1;
    }
    
    
    $scope.removeHoliday = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.holidays.pop({});
    }

    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    
    $scope.open = function ($event) {
        $scope.opened = true;
    };

    $scope.addHolidays = function() {
		for (i = 0; i < $scope.holidays.length; i++) {
			//console.log("Length is  " + $scope.faults.length)
			//console.log("Repair ID is still " + id_of_repair)
			console.log("Description ID is " + $scope.holidays[i].description_id)
			var api_url = window.cfg.apiUrl + 'alclair/add_holidays.php';
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.holidays[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (result) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
	}
    
    $scope.LoadData = function () {
        myblockui();
        console.log("Day to view " + $scope.day_to_view)
        var api_url = window.cfg.apiUrl + "alclair_daily_build_rate/get_daily_build_rate.php?day_to_view=" + $scope.day_to_view;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("HERE IS " + JSON.stringify(result.test))
                if (result.data.length > 0) {
	                $scope.DailyList = result.DailyList;
					$scope.current_ship_date = result.current_ship_date;
                    $scope.daily = result.data[0];
                    $scope.DailyListCount = result.DailyListCount;
                    //$scope.repair_form_fileList = result.data2;
                    
                    console.log("LOOKING HERE " + $scope.current_ship_date)
					$scope.SearchStartDate=$scope.current_ship_date;//window.cfg.CurrentMonthFirstDate;//OctoberOne;
					$scope.SearchEndDate=$scope.current_ship_date;window.cfg.CurrentDay;
                    
                    $scope.holidays = result.data_holidays;
                    $scope.num_of_holidays = $scope.holidays.length;
                    //console.log("NUMBER OF HOLIDAYS IS " + $scope.num_of_holiday)
                    for (i = 0; i < $scope.holidays.length; i++) {
						holiday_IDs[i] = $scope.holidays[i].id;
					}
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }

	$scope.LoadData2 = function () {
		console.log("Day to view is " + $scope.day_to_view)
        myblockui();
        //var api_url = window.cfg.apiUrl + "alclair_daily_build_rate/get_daily_build_rate.php?day_to_view=" + $scope.day_to_view;
        var api_url = window.cfg.apiUrl + "alclair_daily_build_rate/get_daily_build_rate.php?StartDate=" + moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&EndDate=" + moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("TEST IS " + result.test)
                if (result.data.length > 0) {
	                $scope.DailyList = result.DailyList;
	                $scope.current_ship_date = result.current_ship_date;
                    $scope.daily = result.data[0];
                    $scope.DailyListCount = result.DailyListCount;
                    //$scope.repair_form_fileList = result.data2;
                    
                    $scope.holidays = result.data_holidays;
                    $scope.num_of_holidays = $scope.holidays.length;
                    //console.log("NUMBER OF HOLIDAYS IS " + $scope.num_of_holiday)
                    for (i = 0; i < $scope.holidays.length; i++) {
						holiday_IDs[i] = $scope.holidays[i].id;
					}
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
    
    $scope.Update3 = function (uploaddocument) { 
	    
	    myblockui();
        var api_url = window.cfg.apiUrl + "alclair_daily_build_rate/do_stuff.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("TEST IS " + JSON.stringify(result.test))
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get OOPS error.");
            });
	}
    $scope.Update2 = function (uploaddocument) { 
	     ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////// DETERMINING IF UPDATING, ADDING OR DELETING A HOLIDAY ////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		console.log("LENGTH IS  " + $scope.holidays.length)
		if($scope.holidays.length == 0 ) {
			$http.get(window.cfg.apiUrl + "alclair_daily_build_rate/delete_holiday.php?id=" + holiday_IDs + "&delete_all=YES").success(function (result) {
                 }).error(function (result) {
					toastr.error("Failed to delete holiday, please try again.");
        			});	
		}
		for (k = 0; k < holiday_IDs.length; k++) {
		    for (i = 0; i < $scope.holidays.length; i++) {
			    //console.log("IDs IS " + holiday_IDs[k] + " and Scope IS " + $scope.holidays[i].id)
			    if(holiday_IDs[k] == $scope.holidays[i].id) {

					if($scope.holidays[i].holiday_date)
					$scope.holidays[i].holiday_date = moment($scope.holidays[i].holiday_date).format("MM/DD/YYYY");
					
					var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/update_holiday.php?id=' + $scope.holidays[i].id 
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.holidays[i]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        				})
					.success(function (result) {             
						console.log("THE ID IS " + result.test)             
						if (result.code == "success") {		
							$.unblockUI();
           				} else {
			 				$.unblockUI();
			 				//console.log("adsfasdfasdfasdfsdf")
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
						console.log("Code is " + result.code)
						toastr.error("Insert holiday error.");
		 			});
					
					break;
				} else if(i == $scope.holidays.length-1) {
					// DELETE fault AT ID = k
					console.log("DELETE: The ID is " + holiday_IDs[k])
					$http.get(window.cfg.apiUrl + "alclair_daily_build_rate/delete_holiday.php?id=" + holiday_IDs[k])
					.success(function (result) {
                    	    console.log("WOOHOO")
                    }).error(function (result) {
						toastr.error("Failed to delete holiday, please try again.");
        				});					
				}
			}	
		}
		for (i = 0; i < $scope.holidays.length; i++) {
	    		if(!$scope.holidays[i].id)  {	
			    // ADD HOLIDAY
				console.log("ADD: There is no ID " + $scope.holidays[i].id)
				if($scope.holidays[i].holiday_date)
					$scope.holidays[i].holiday_date = moment($scope.holidays[i].holiday_date).format("MM/DD/YYYY");
				
				var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/add_holiday.php';
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.holidays[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert holiday error.");
				});
			}
		}
		///////////////////////    END DEALING WITH HOLIDAYS   /////////////////////////////////
								
       	var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/update_daily_build_rate.php';
		console.log(api_url+"?"+$scope.holiday);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.daily),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {

             console.log("The JSON is " + JSON.stringify(result));
                          
             if (result.code == "success") {
                 
				 //$scope.UploadFile();
				 $.unblockUI();
				 window.location.href = window.cfg.rootUrl + "/admin/daily_build_rate/";
             }
             else {
                 $.unblockUI();
                 console.log("message " + result.code);
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert repair form error.");

         });
	}
    
    
    $scope.Update = function (uploaddocument) {
	    console.log("HOLIDAY IS " + $scope.holidays[0].the_name)
	    console.log("DATE IS " + $scope.holidays[0].holiday_date)
	    console.log("IDS IS " + holiday_IDs)
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////// DETERMINING IF UPDATING, ADDING OR DELETING A HOLIDAY ////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for (k = 0; k < holiday_IDs.length; k++) {
		    for (i = 0; i < $scope.holidays.length; i++) {
			    if(holiday_IDs[k] == $scope.holidays[i].id) {
					// UPDATE JOB
					console.log("UPDATE: The ID is " + $scope.holidays[i].id)
					
					var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/update_holiday.php'; 
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.holidays[i]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {                          
						if (result.code == "success") {		
							$.unblockUI();
           				} else {
			 				$.unblockUI();
			 				//console.log("adsfasdfasdfasdfsdf")
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
						console.log("Code is " + result.code)
						toastr.error("Insert holiday error.");
		 			});
					
					break;
				} else if(i == $scope.holidays.length-1) {
					// DELETE fault AT ID = k
					console.log("DELETE: The ID is " + holiday_IDs[k])
					$http.get(window.cfg.apiUrl + "alclair_daily_build_rate/delete_holiday.php?id=" + holiday_IDs[k]).success(function (result) {
                    }).error(function (result) {
						toastr.error("Failed to delete holiday, please try again.");
        			});					
				}
			}	
		}
		for (i = 0; i < $scope.holidays.length; i++) {
	    		if(!$scope.holidays[i].id)  {	
			    // ADD HOLIDAY
			    if($scope.holiday[i].holiday_date)
					$scope.holiday[i].holiday_date = moment($scope.holiday[i].holiday_date).format("MM/DD/YYYY");
					//$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
				console.log("ADD: There is no ID " + $scope.holidays[i].id)
			
				var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/add_holiday.php';
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.holidays[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert holiday error.");
				});
			}
		}
		///////////////////////    END DEALING WITH HOLIDAYS   /////////////////////////////////
						
		console.log("Date is " + $scope.holidays[0].holiday_date)
								
       	var api_url = window.cfg.apiUrl + 'alclair_daily_build_rate/update_daily_build_rate.php';
		console.log(api_url+"?"+$scope.holiday);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.daily),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {

             console.log("The JSON is " + JSON.stringify(result));
                          
             if (result.code == "success") {
                 
				 //$scope.UploadFile();
				 $.unblockUI();
				 window.location.href = window.cfg.rootUrl + "/admin/daily_build_rate/";
             }
             else {
                 $.unblockUI();
                 console.log("message " + result.code);
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert repair form error.");

         });
    };
    
    $scope.init=function()
    {
	    $scope.LoadData();
	    $scope.DAY_TO_VIEW = AppDataService.day_to_view;
	    
        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.printed_or_not = '0';
	$scope.month_range = 30;
	$scope.ascending_or_descending = 1;

		
    $scope.reload_page = function () {
	 	$scope.LoadData();
 	}
    
    $scope.sound_modal = function () {
		$('#displaySound').modal("show");   
        var api_url = window.cfg.apiUrl + "alclair_repair_manufacturing/get_sound_issues.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&MONTH_RANGE=" + $scope.month_range;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + JSON.stringify(result.sound_counter))
	            $scope.one = result.sound_counter[0];
	            $scope.two = result.sound_counter[1];
	            $scope.three = result.sound_counter[2];
	            $scope.four = result.sound_counter[3];
	            $scope.five = result.sound_counter[4];
	            $scope.six = result.sound_counter[5];
	            $scope.seven = result.sound_counter[6];
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
	}
	$scope.fit_modal = function () {
		$('#displayFit').modal("show");   
        var api_url = window.cfg.apiUrl + "alclair_repair_manufacturing/get_fit_issues.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&MONTH_RANGE=" + $scope.month_range;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + JSON.stringify(result.fit_counter))
	            $scope.one = result.fit_counter[0];
	            $scope.two = result.fit_counter[1];
	            $scope.three = result.fit_counter[2];
	            $scope.four = result.fit_counter[3];
	            $scope.five = result.fit_counter[4];
	            $scope.six = result.fit_counter[5];
	            $scope.seven = result.fit_counter[6];
	            
	            $scope.eight = result.fit_counter[7];
	            $scope.nine = result.fit_counter[8];
	            $scope.ten = result.fit_counter[9];
	            $scope.eleven = result.fit_counter[10];
	            $scope.twelve = result.fit_counter[11];
	            $scope.thirteen = result.fit_counter[12];
	            $scope.fourteen = result.fit_counter[13];
	            
	            $scope.fifteen = result.fit_counter[14];
	            $scope.sixteen = result.fit_counter[15];
	            $scope.seventeen = result.fit_counter[16];
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
	}
    
	$scope.design_modal = function () {
		$('#displayDesign').modal("show");   
        var api_url = window.cfg.apiUrl + "alclair_repair_manufacturing/get_design_issues.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&MONTH_RANGE=" + $scope.month_range;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + JSON.stringify(result.design_counter))
	            $scope.one = result.design_counter[0];
	            $scope.two = result.design_counter[1];
	            $scope.three = result.design_counter[2];
	            $scope.four = result.design_counter[3];
	            $scope.five = result.design_counter[4];
	            $scope.six = result.design_counter[5];
	            $scope.seven = result.design_counter[6];
	            
	            $scope.eight = result.design_counter[7];
	            $scope.nine = result.design_counter[8];
	            $scope.ten = result.design_counter[9];
	            $scope.eleven = result.design_counter[10];
	            $scope.twelve = result.design_counter[11];
	            
	            $scope.thirteen = result.design_counter[12];
	            $scope.fourteen = result.design_counter[13];
	            $scope.fifteen = result.design_counter[14];
	            $scope.sixteen = result.design_counter[15];
	            $scope.seventeen = result.design_counter[16];
	            $scope.eighteen = result.design_counter[17];
	            $scope.nineteen = result.design_counter[18];
	            $scope.twenty = result.design_counter[19];
	            $scope.twentyone = result.design_counter[20];
	            

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
	}
    
    
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
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;//OctoberOne;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
	console.log("Date is " + window.cfg.OctoberOne)
	console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
	//$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";
        $scope.cust_name = "";
		$scope.qc_form = {
	        cust_name: "",
    		};
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
    }
    $scope.sort_me = function (to_sort_by) {
	    console.log("THIS IS WORKING")
	    if($scope.ascending_or_descending == 0) {
		    $scope.ascending_or_descending = 1;
	    } else {
		    $scope.ascending_or_descending = 0;
	    }
	    console.log("Asc or Desc is " + $scope.ascending_or_descending)
	    $scope.LoadData(to_sort_by, $scope.ascending_or_descending)
	}
    $scope.LoadData = function (to_sort_by, asc_or_desc) {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		
		console.log("rush is " + $scope.order_status_id)
		console.log("Sort By " + to_sort_by)
        var api_url = window.cfg.apiUrl + "alclair_repair_manufacturing/get_repair_manufacturing.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&MONTH_RANGE=" + $scope.month_range + "&To_Sort_By=" + to_sort_by + "&asc_or_desc=" + asc_or_desc;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test Is " + result.test)
	            
	            $scope.OrdersList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.TotalRecords2 = result.TotalRecords2;
                $scope.TotalSound = result.TotalSound;
                $scope.TotalFit = result.TotalFit;
                $scope.TotalDesign = result.TotalDesign;
                $scope.OrdersWithFit = result.OrdersWithFit;
                $scope.percentFitIssues = result.OrdersWithFit/result.TotalRecords2*100;
				$scope.percentFitIssues = $scope.percentFitIssues.toFixed(1);

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
                toastr.error("Get QC Form error.");
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


       $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {

	    $scope.MONTH_RANGE = AppDataService.month_range;
        
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";

        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.orderStatusTableList = result.data;
        }, function (result) { });

        $scope.LoadData();
    }
    			        
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
      
    }
            
    $scope.init();
}]);

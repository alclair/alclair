swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
     $scope.open_add_order = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair/add_order/";
 	}
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
    
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

    $scope.Search = function () {
	    
	    //console.log("Search State Date is " + $scope.SearchStartDate + " and Search End Date is " + $scope.SearchEndDate)
	    //return;
	    
        myblockui();
		
        var api_url = window.cfg.apiUrl + "WooCommerce/get_orders.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&ORDER_STATUS_ID=" + $scope.order_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not + "&USE_IMPRESSION_DATE=" + $scope.use_impression_date;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	            
                $scope.OrdersList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                //console.log("Pass or Fail is " + result.testing1)

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

    $scope.init = function () {
        //$scope.LoadData();
    }
    			    
    
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
      
    }
        
    $scope.DONE = function () {
	    //var $order_id = 9729;
        myblockui();
        
        var api_url = window.cfg.apiUrl + "alclair/move_to_done.php?ID=" + $scope.id_to_make_done +"&DoneDate=" + moment($scope.done_date).format("MM/DD/YYYY");
		//console.log("api url is " + api_url)
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Test1 is " + result.test1);
			toastr.success("Successfully moved to Done.");
			$scope.LoadData();
			$('#SelectDateModal').modal("hide");

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });

    }
    
    $scope.init();
	}]);
	
swdApp.controller('SearchWoo', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
    
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
	console.log("Date is " + window.cfg.OctoberOne)
	console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
	//$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;

    $scope.Search = function () {
	    $scope.OrdersList = "";
	    console.log("Search Start Date is " + $scope.SearchStartDate + " and Search End Date is " + moment($scope.SearchEndDate).format("MM/DD/YYYY"))
	    //return;
	    
        myblockui();
		
        var api_url = window.cfg.apiUrl + "WooCommerce/scott_a_v2.php?StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //console.log("Test is " + result.test)
	            
                $scope.OrdersList = result.data;
                $scope.STARTED = result.started;
                $scope.ENDED = result.ended;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                //console.log("Pass or Fail is " + result.testing1)

				/*
                $scope.PageRange = [];
                $scope.PageWindowStart = (Math.ceil($scope.PageIndex / $scope.PageWindowSize) - 1) * $scope.PageWindowSize + 1;
                $scope.PageWindowEnd = $scope.PageWindowStart + $scope.PageWindowSize - 1;
                if ($scope.PageWindowEnd > $scope.TotalPages) {
                    $scope.PageWindowEnd = $scope.TotalPages;
                }
                for (var i = $scope.PageWindowStart; i <= $scope.PageWindowEnd; i++) {
                    $scope.PageRange.push(i);
                }
				*/
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };

    $scope.init = function () {
        $scope.Search();
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
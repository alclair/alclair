swdApp.controller('Reconciliation', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
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
		//$scope.SearchStartDate = '01/01/2020';
		//$scope.SearchEndDate = '01/31/2020';
        var api_url = window.cfg.apiUrl + "WooCommerce/reconcile.php?StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //console.log("Test is " + result.test)
	            
                $scope.OrdersList = result.data;
                $scope.STARTED = result.started;
                $scope.ENDED = result.ended;
                $scope.InWooNotOtis = result.InWooNotOtis;
                $scope.InOtisNotWoo = result.InOtisNotWoo;
                console.log("TOTAL OF " + result.InOtisNotWoo.length)
                console.log("TEST IS " + JSON.stringify(result.test))
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                //console.log("Pass or Fail is " + result.testing1)

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };

	$scope.goFindOrder = function (order_number) {        
        console.log("The order number is " + order_number)
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
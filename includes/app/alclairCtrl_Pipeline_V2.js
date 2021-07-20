	swdApp.controller('Pipeline_V2', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
	$scope.today_or_next_week = '0';
	$scope.order_status_id = 0;
	$scope.remove_hearing_protection = 1;
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
     //document.getElementById("barcode_orders").oninput = function() {myFunction()};
		
	function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("barcode_orders").value;
				//pos = x.indexOf("-");
				//id_of_qc_form = x.slice(0,pos);
				id_of_order = x;
				console.log("The id is " + id_of_order)
				//var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + id_of_order;
				window.location.href = window.cfg.rootUrl + "/alclair/edit_traveler/" + id_of_order; 
		}, 500); 
	};
    
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
    
    $scope.qc_form = {
        cust_name: '',
        pass_or_fail: '0',
        qc_date: new Date,
    };

	$scope.customers = [];
	$scope.getDesignedFor = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_designed_for.php";
	        $http.get(api_url).success(function (data) {
	            $scope.customers = data.data;
	    })
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
    $scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		
		console.log("rush is " + $scope.order_status_id)
		console.log("Monitor ID is " + $scope.monitor_id)
		console.log("Hearing protection is " + $scope.remove_hearing_protection)
        var api_url = window.cfg.apiUrl + "alclair/get_pipeline_2wks.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&TODAY_OR_NEXT_WEEK=" + $scope.today_or_next_week + "&RUSH_OR_NOT=" + $scope.rush_or_not + "&REMOVE_HEARING_PROTECTION=" + $scope.remove_hearing_protection + "&monitor_id=" + $scope.monitor_id + "&order_status_id=" + $scope.order_status_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.test2)
	            
                $scope.OrdersList = result.data;
                
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

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this order?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair/delete_order.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.OrdersList.length; i++) {
                if ($scope.OrdersList[i].id == id) {
                    toastr.success("Delete Order successful!", "Message");
                    $scope.OrdersList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete form, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
	    $scope.getDesignedFor();
	    $scope.PRINTED_OR_NOT = AppDataService.PRINTED_OR_NOT;
	    $scope.TODAY_OR_NEXT_WEEK = AppDataService.TODAY_OR_NEXT_WEEK;
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        $scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList_not_Universals(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.OrderStatusList = result.data;
        }, function (result) { });
        
   		/*if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}*/
        $scope.LoadData();
    }
    			    
    $scope.PDF = function (id) {
	    //var $order_id = 9729;
        
        //myblockui();
        console.log("Order num is " +  id)
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF.php?ID=" + id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Result is " + result.data);

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }
    
    $scope.init();
	}]);
swdApp.controller('Open_Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
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
		        console.log("TEST IS " + data.test)
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
    $scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		
		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			console.log("DEFINED Impression checked " + $scope.use_impression_date)	
			$scope.use_impression_date = 1;
		}
		
        var api_url = window.cfg.apiUrl + "alclair/get_open_orders.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&ORDER_STATUS_ID=" + $scope.order_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not + "&USE_IMPRESSION_DATE=" + $scope.use_impression_date;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	            
                $scope.OrdersList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                //$scope.TotalPages = result.TotalPages;
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
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        $scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.orderStatusTableList = result.data;
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
	

	swdApp.controller('BAs_Required_Manual', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
	
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
	$scope.today_or_next_week = '2';
	$scope.TODAY_OR_NEXT_WEEK = [];
	$scope.iem = [];
	
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
	console.log("SCOPE FORMATS IS " + $scope.format)
	$scope.SearchStartDate=window.cfg.FirstOfYear;
	//$scope.SearchStartDate=window.cfg.CurrentDay;//OctoberOne;
	$scope.SearchEndDate=window.cfg.CurrentDay_plus_2weeks;
	$scope.openStartDay = function ($event) {        
        $scope.openedStartDay = true;
    };
	$scope.openEndDay = function ($event) {        
        $scope.openedEndDay = true;
    };

	

    $scope.LoadData = function () {
        myblockui();
		
		 console.log("IEMs are " + $scope.iem.versa)
        var api_url = window.cfg.apiUrl + "alclair/get_bas_manual_load_data.php?versa=" + $scope.iem.versa + 
       			"&dual_xb=" + $scope.iem.dual_xb +
                "&st3=" + $scope.iem.st3 +
                "&tour=" + $scope.iem.tour +
                "&rsm=" + $scope.iem.rsm +
                "&cmvk=" + $scope.iem.cmvk +
                "&spire=" + $scope.iem.spire +
                "&studio4=" + $scope.iem.studio4 +
                "&revx=" + $scope.iem.revx +
                "&electro=" + $scope.iem.electro +
                "&esm=" + $scope.iem.esm +
                "&dkm=" + $scope.iem.dkm +
                "&icon=" + $scope.iem.icon
        + "&StartDate=" + moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&EndDate=" + moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log(result.test)
	            //console.log("TOTAL ROWS " + result.TotalRows)
                $scope.OrdersList = result.data;
                $scope.PartsList = result.data2;
                $scope.DampersList = result.data3;
  
                $scope.TotalRecords = result.TotalRecords;
                //console.log("Pass or Fail is " + result.testing1)

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };
    
    $scope.OnPageLoad = function () {
		var api_url = window.cfg.apiUrl + "alclair/get_bas_manual_on_load.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + 		"&SearchText=" + $scope.SearchText +"&TODAY_OR_NEXT_WEEK=" + $scope.today_or_next_week + "&RUSH_OR_NOT=" + $scope.rush_or_not + 		"&REMOVE_HEARING_PROTECTION=" + $scope.remove_hearing_protection + "&monitor_id=" + $scope.monitor_id + "&StartDate=" + 									moment($scope.SearchStartDate).format("MM/DD/YYYY") + "&EndDate=" + moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	           console.log(result.test)
                $scope.OrdersList = result.data;
                for (var i = 0; i <= $scope.OrdersList.length-1; i++) {
	            	//console.log("I is " + i + " Model is " + $scope.OrdersList[i]["monitors"])
	            }
                
                $scope.iem.versa = $scope.OrdersList[0]["casing_count"];
                //$scope.iem.versa = 2;
                $scope.iem.dual_xb = $scope.OrdersList[1]["casing_count"];
                $scope.iem.st3 = $scope.OrdersList[2]["casing_count"];
                $scope.iem.tour = $scope.OrdersList[3]["casing_count"];
                $scope.iem.rsm = $scope.OrdersList[4]["casing_count"];
                $scope.iem.cmvk = $scope.OrdersList[5]["casing_count"];
                $scope.iem.spire = $scope.OrdersList[6]["casing_count"];
                $scope.iem.studio4 = $scope.OrdersList[7]["casing_count"];
                $scope.iem.revx = $scope.OrdersList[8]["casing_count"];
                $scope.iem.electro = $scope.OrdersList[9]["casing_count"];
                $scope.iem.esm = $scope.OrdersList[10]["casing_count"];
                $scope.iem.dkm = $scope.OrdersList[11]["casing_count"];
                $scope.iem.icon = $scope.OrdersList[12]["casing_count"];
				//console.log("HERE NOW are " + $scope.iem["versa"])
				$scope.LoadData();
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get QC Form error.");
            });
    };

	$scope.GrabFields = function () {
		 $scope.iem.versa = $scope.iem.versa;
		 $scope.iem.dual_xb = $scope.dual_xb;
         $scope.iem.st3 = $scope.dual_xb;
         $scope.iem.tour = $scope.dual_xb;
         $scope.iem.rsm = $scope.dual_xb;
		 $scope.iem.cmvk = $scope.dual_xb;
		 $scope.iem.spire = $scope.dual_xb;
		 $scope.iem.studio4 = $scope.dual_xb;
		 $scope.iem.revx = $scope.dual_xb;
		 $scope.iem.electro = $scope.dual_xb;
		 $scope.iem.esm = $scope.dual_xb;
		 $scope.iem.dkm = $scope.dual_xb;
		 $scope.iem.icon = $scope.dual_xb;
		 console.log("Test number is " + $scope.iem.versa)
		 //$scope.LoadData();
	};

    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
		$scope.OnPageLoad();
		//$scope.LoadData();
    }
    			    
    
    $scope.init();
	}]);
	
	
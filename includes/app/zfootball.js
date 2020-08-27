swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.personnelList = {};
	$scope.TEAM_NAME = "";
	$scope.DATE_DISPLAYED = "";
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
	$scope.SearchEndDate=window.cfg.CurrentDay_football;
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
	
	$scope.refresh_page=function()
    {
        $scope.LoadData();
    }

	$scope.UpdateAttendance3 = function () {
		myblockui();
		//console.log("Length is " + $scope.personnelList.length)
		 for (var i = 0; i < $scope.personnelList.length; i++) {
			 
			 if($scope.personnelList[i].absent != 1) {
				 $scope.personnelList[i].absent = 0;
			 }
			 //console.log("Person absent " + $scope.personnelList[i].absent + " and Person ID " +  $scope.personnelList[i].person_id)
			 var api_url = window.cfg.apiUrl + "zfootball/update_attendance2.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize+"&DATE="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&TEAM_ID=" + $scope.team_id + "&ABSENT=" + $scope.personnelList[i].absent + "&PERSONNEL_ID=" + $scope.personnelList[i].person_id + "&LOG_ID=" + $scope.personnelList[i].log_id;
       myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.personnelList),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         console.log("Test is " + result.test)
             if (result.code == "success") {
                 $.unblockUI();
                 
                 $scope.LoadData();
                 //toastr.success(result.message) 
             }
             else {
                 $.unblockUI();
                 console.log("Error Here")
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Broken");

         });
		}
	}
	
    
    $scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		console.log("The team ID is " + $scope.team_id)
        var api_url = window.cfg.apiUrl + "zfootball/get_attendance.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize+"&DATE="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&TEAM_ID=" + $scope.team_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            
	            
                $scope.personnelList = result.data;
                $scope.TEAM_NAME = result.TEAM_NAME;
                $scope.DATE_DISPLAYED = result.DATE_DISPLAYED;
                $scope.ATTENDANCE_YET = result.ATTENDANCE_YET;
                console.log("Team Name is " + $scope.TEAM_NAME)
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;
                console.log("TotalRecords " + result.data.length)
                
                for (var i = 0; i < result.data.length; i++) {
	                if($scope.personnelList[i]["present"] == "NO") {
		            	$scope.personnelList[i]["absent"] = 1;	    
	                }					
				}

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
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";

        AppDataService.loadTeamList(null, null, function (result) {
           $scope.teamList = result.data;
        }, function (result) { });
        $scope.LoadData();
    }

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

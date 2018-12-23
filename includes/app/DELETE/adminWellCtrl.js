swdApp.controller('adminWellCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "wells";
    $scope.entityName = "Well";

    $scope.fields = [];
    $scope.countylist = [];
    $scope.operators = [];    
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
	$scope.GetFields=function()
	{
		myblockui();
		var api_url = window.cfg.apiUrl + "fields/get.php";
		$http.get(api_url).success(function(result){
			$scope.fields=result.data;	
			$.unblockUI();
		});
	}
	$scope.GetCountyList=function()
	{
		$("#modalAddRecord").block();
		//myblockui();
		var api_url = window.cfg.apiUrl + "county/get.php";
		//$http.get(api_url).success(function(result){
		//	$scope.countylist=result.data;	
		//	$.unblockUI();
		//});
		$http.get(api_url).success(function(result){
		//	
			$scope.countylist=result.data;	
			$("#modalAddRecord").unblock();
		});
	}
	$scope.GetOperators=function()
	{
		myblockui();
		var api_url = window.cfg.apiUrl + "operators/get.php?all=1";
		$http.get(api_url).success(function(result){
			$scope.operators=result.data;	
			$.unblockUI();
		});
	}
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/getpaging.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result);

            $scope.recordList = result.data;
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
            toastr.error("Get error.");
        });
    };

    $scope.GoToPage = function (v) {

        $scope.PageIndex = v;
        $scope.LoadData();

    };    
    

    $scope.Search = function () {
        
        $scope.LoadData();
    };

    $scope.deleteRecord = function (id) {
        console.log("Record that need delete, id is: " + id);
        if (confirm("Are you sure to delete this record?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + $scope.apifolder + "/delete.php?id=" + id)
        .success(function (result) {
            if (result.code == "success") {
                for (var i = 0; i < $scope.recordList.length; i++) {
                    if ($scope.recordList[i].id == id) {
                        toastr.success("Delete successful!", "Message");
                        $scope.recordList.splice(i, 1);
                        $scope.TotalRecords = $scope.TotalRecords - 1;
                        break;
                    }
                }
            }
            else {
                toastr.error(result.message);
            }
        })
        .error(function (result) {
            toastr.error("Failed to delete ticket, please try again.");
        });
    };

    $scope.OperatorSelected = function (id) {
        $scope.recordAdd.operator_id = id;
    }
	$scope.FieldNameSelected = function (id) {
        $scope.recordAdd.field_name = id;
    }
	$scope.CountyNameSelected = function (id) {
        $scope.recordAdd.county_name = id;
    }

    //Upload "Add of Record"
    $scope.uploadAddRecord = function () { //location.reload();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/add.php";
        //var api_url = window.cfg.apiUrl + $scope.apifolder + "/add_again.php";

        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.recordAdd),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
	             //location.reload();
             /*    $.unblockUI();
                 toastr.success(result.message);
                 var newrecord = {
                     id: result.data.id,
                     name: $scope.recordAdd.name,
                 };
                 $scope.recordList.push(newrecord);
                 $scope.TotalRecords += 1;
                 $("#modalAddRecord").modal("hide");*/
             }
             else {
               /*  $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);*/
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");
         });
         
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/add_again.php";
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.recordAdd),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success(result.message);
                 var newrecord = {
                     id: result.data.id,
                     name: $scope.recordAdd.name,
                 };
                 $scope.recordList.push(newrecord);
                 $scope.TotalRecords += 1;
                 $("#modalAddRecord").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");
         });

         
    };

    //Load "Edit of Record"
	$scope.loadRecordEdit = function (id) {
		$scope.GetOperators();
		$scope.GetCountyList();
		$scope.GetFields();
		
        myblockui();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/getpaging.php?id=" + id;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result);
            $scope.recordEdit = result.data;
            $scope.recordEdit.range = parseInt($scope.recordEdit.range);
            $scope.recordEdit.operator_name = { id: $scope.recordEdit.operator_id, name: $scope.recordEdit.operator_name };
            $scope.recordEdit.field_name = { id: $scope.recordEdit.field_name_id, name: $scope.recordEdit.field_name };
			$scope.recordEdit.county_name = { id: $scope.recordEdit.county_name_id, name: $scope.recordEdit.county_name };
            console.log($scope.recordEdit);
            $("#modalEditRecord").modal("show");
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.wells = [];
	$scope.getWells = function () {
		 	var api_url = window.cfg.apiUrl + "wells/get_WellsList.php";
		 	//alert(api_url);
		 	$http.get(api_url).success(function (data) {
            $scope.wells = data.data;
            //alert($scope.wells.length);
        })
    }

	$scope.addRecord = function () {
		console.log($scope.ticket.well_info.id);  // Outputs the ID for the well in Wells List.  
		$scope.ticket.ID_num = $scope.ticket.well_info.id;
		var api_url = window.cfg.apiUrl + 'wells/does_exist.php';
		$http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.ticket),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
			.success(function (result) {
             	console.log(result.well_exists);
			 	//console.log($scope.ticket.ticket_number)
			 	if (result.well_exists != null ) {
			 		toastr.error("This well already exists in your records!");  
			 		$.unblockUI(); 
			 		}
			 	else {
			 		//$scope.to = $scope.ticket.well_info;
			 		//console.log($scope.to)
			 		console.log($scope.ticket.ID_num);
			 		
					var api_url = window.cfg.apiUrl + 'wells/insert_into_ticket_tracker_well.php';

					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.ticket),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {
						 //console.log(result);
						 //console.log(result.ids)
						 toastr.success("Well has been added to your records!");
               		})
               	}
         });
       //}  // END THE ELSE STATEMENT
	}
	
	$scope.closeAddRecord = function() {
		location.reload();
	}
	
    $scope.loadWellsList = function() {
	     $scope.getWells();
		 $("#modalWellsList").modal("show");
    };
    

    //Update "Edit of Record"
    $scope.updateRecordEdit = function () {
	    //console.log($scope.recordEdit)
	    console.log($scope.recordEdit.operator_name.id)
	    console.log($scope.recordEdit.field_name.name)
	    console.log($scope.recordEdit.county_name)
	    console.log($scope.recordEdit.township)
	    $scope.recordEdit.operator_id = $scope.recordEdit.operator_name.id;
	    $scope.recordEdit.field_name = 	$scope.recordEdit.field_name.name;
	    $scope.recordEdit.county_name = 	$scope.recordEdit.county_name.name;
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update.php?id=" + $scope.recordEdit.id;
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.recordEdit),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success(result.message);
                 var newrecord = {
                     id: $scope.recordEdit.id,
                     name: $scope.recordEdit.name,               
                 };
                 for (var i = 0; i < $scope.recordList.length; i++) {
                     if ($scope.recordList[i].id == $scope.recordEdit.id) {
                         $scope.recordList[i] = newrecord;
                         break;
                     }
                 }

                 $("#modalEditRecord").modal("hide");
                 location.reload();
             }
         }).error(function (data) {
             toastr.error("Update error.");
         });
    };

    $scope.loadRecordAddModal = function () {
		$scope.GetOperators();
		$scope.GetCountyList();
		$scope.GetFields();
        $("#modalAddRecord").modal("show");
    };    
	$scope.init=function()
	{
		$scope.LoadData();
		//$scope.GetOperators();
		//$scope.GetCountyList();
		//$scope.GetFields();
		
	}
	$scope.init();
}]);
 


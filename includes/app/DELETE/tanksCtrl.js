swdApp.controller('displayTanksCtrl', ['$http', '$scope', 'AppDataService',  '$cookies', function ($http, $scope, AppDataService, $cookies) {
	 $scope.apifolder = "tanks";
     //Upload "Add of Record"
    $scope.inventoryAdjsutment = function () {
      	//window.location.href = window.cfg.rootUrl + "/accounting/dual_box/" + window.cfg.Id;
      	//window.location.href = window.cfg.rootUrl + "/accounting/dual_box/" + $scope.recordAdd.unique_customer_id;
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/add.php";
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
                 toastr.error("RIGHT HERE.");
                 //toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");
         });
    };
    
    $scope.LoadData = function () {
        myblockui();
		
        var api_url = window.cfg.apiUrl + "tanks/get_tank_status.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	             $scope.ticket = result.data;
	             console.log("We are here again Yep ")       
					$.unblockUI();
            }).error(function (result) {
                toastr.error("Get list error");
            	});
            
            // LOAD OUTGOING TICKET TYPES FOR THE MODAL WINDOW	
            $http.get(window.cfg.apiUrl + "outgoingtickettypes/get.php").success(function (result) {
            $scope.OutgoingTicketTypes = result.data;
            if (isEmpty($scope.SearchOutgoingTicketTypes)) $scope.SearchOutgoingTicketTypes = "0";
			if(!isEmpty(window.cfg.type_id))
			{		
				$scope.SearchOutgoingTicketTypes=window.cfg.type_id;
			}
        });

    };  
    
    	$scope.loadOtherTankList = function (params) {
	    	$scope.tankList2 = [];
		 	AppDataService.loadTankList2(params, null, function (result) {
		 		$scope.tankList2 = result.data;
        	}, function (result) { });
	    };
    
        $scope.loadOtherFluidList = function (params) {
	    	$scope.fluidTypeList = [];
		 	AppDataService.loadFluidTypeList2(params, null, function (result) {
		 		$scope.fluidTypeList = result.data;
        	}, function (result) { });
	    };
    
        $scope.LoadData2 = function () {
		 	$scope.adjustment = {
		 		AddorSubtract: 'Add',
    		};

		 	$scope.tankList = [];
		 	AppDataService.loadTankList(null, null, function (result) {
		 		$scope.tankList = result.data;
        	}, function (result) { });
        	
        	//$scope.loadFluidList = function () {
	    	$scope.fluidTypeList = [];
		 	AppDataService.loadFluidTypeList(null, null, function (result) {
		 		$scope.fluidTypeList = result.data;
        	}, function (result) { });
        	
    	   AppDataService.loadTruckingCompanyList(null, null, function (result) {
           		$scope.truckingCompanyList = result.data;
        	}, function (result) { });
        	
        	$scope.AddorSubtract = AppDataService.AddorSubtract;
         };
        
        $scope.LoadData3 = function () {
		 	$scope.ledger = {
		 		start_date: new Date(),
		 		end_date: new Date(),
    		};
         };

         
    $scope.adjustmentCtrl = function () {
	     $scope.LoadData2();
       console.log("HERE 3")
        $("#tankAdjustment").modal("show");
    };
    
    $scope.transferCtrl = function () {
	     $scope.LoadData2();
       
        $("#tankTransfer").modal("show");
    };
    
    $scope.ledgerCtrl = function () {
	     $scope.LoadData3();
       
        $("#tankLedger").modal("show");
    };
    
    $scope.outgoingCtrl = function () {
		//$scope.LoadOutgoingTicketTypes();
       
        $("#outgoingSelect").modal("show");
    };
    
    $scope.LoadOutgoingTicketTypes=function()
    {
        $http.get(window.cfg.apiUrl + "outgoingtickettypes/get.php").success(function (result) {
            $scope.OutgoingTicketTypes = result.data;
            if (isEmpty($scope.SearchOutgoingTicketTypes)) $scope.SearchOutgoingTicketTypes = "0";
			if(!isEmpty(window.cfg.type_id))
			{		
				$scope.SearchOutgoingTicketTypes=window.cfg.type_id;
			}
        });
    }
    
    $scope.outgoingSelect = function () {
		 $("#tankTransfer").modal("hide");
		 if($scope.SearchOutgoingTicketTypes == 1) {
			 window.location.href = window.cfg.rootUrl + "/ticket/outgoinglandfill/";
		 } else if ($scope.SearchOutgoingTicketTypes == 2) {
			 window.location.href = window.cfg.rootUrl + "/ticket/oilsale/";
		 } else {
		 	window.location.href = window.cfg.rootUrl + "/ticket/outgoingwater/";
		 }
    }
     //Update "Edit of Record"
    $scope.tankTransfer = function () {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update_transfer.php";
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.transfer),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 toastr.success(result.message);
                 //$scope.loadRecordList();		 
                 $("#tankTransfer").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Update error.");
         });
    };
    
    $scope.feet_to_barrels_adjustment = function() {
	    if($scope.adjustment.tank_to_id2 != 8)  // Big Tank for WWL
	    	$scope.adjustment.barrels = $scope.adjustment.feet*20;
	    else
		    $scope.adjustment.barrels = $scope.adjustment.feet*300;
    }
    $scope.barrels_to_feet_adjustment = function() {
	     if($scope.adjustment.tank_to_id2 != 8)  // Big Tank for WWL
		 	$scope.adjustment.feet = $scope.adjustment.barrels/20;
		 else
		 	$scope.adjustment.feet = $scope.adjustment.barrels/300;
    }
    
    $scope.feet_to_barrels_transfer = function() {
	    if($scope.transfer.tank_from_id != 8)  // Big Tank for WWL
		    $scope.transfer.barrels = $scope.transfer.feet*20;
		else
			$scope.transfer.barrels = $scope.transfer.feet*300;  
    }
    $scope.barrels_to_feet_transfer= function() {
		if($scope.transfer.tank_from_id != 8)  // Big Tank for WWL
			$scope.transfer.feet = $scope.transfer.barrels/20;
		else
			$scope.transfer.feet = $scope.transfer.barrels/300;
    }
    
	$scope.tankAdjustment = function () {
	    console.log("The id here is " + $scope.adjustment.tank_to_id2)
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update_adjustment.php";
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.adjustment),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 toastr.success(result.message);
                 //$scope.loadRecordList();
                 $("#tankAdjustment").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Update error.");
         });
    };
    
    $scope.tankLedger = function () {

        var api_url = window.cfg.apiUrl + "export/exportLedger_WWL.php";
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.adjustment),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 toastr.success(result.message);
                 //$scope.loadRecordList();
                 $("#tankLedger").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Update error.");
         });
    };

    
    $scope.init = function () {
    	$scope.LoadData();
    }
    $scope.init();
    
}]);


swdApp.controller('fluidTypeCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "fluidtype";
    $scope.entityName = "Fluid Type";
    $scope.SearchText = "";

    //Load "List of Record"
    $scope.loadRecordList = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/get.php?SearchText=" + $scope.SearchText;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.recordList = result.data;
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.Search = function () {
        
        $scope.loadRecordList();
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
                        //$scope.TotalRecords = $scope.TotalRecords - 1;
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

    //Upload "Add of Record"
    $scope.uploadAddRecord= function () {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/add.php";
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.recordAdd),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 //$.unblockUI();
                 toastr.success(result.message);
                 var newrecord = {
                     id: result.data.id,
                    type: $scope.recordAdd.type,
                    priority: $scope.recordAdd.priority
                 };
                 location.reload();
                 console.log("Loaded OK")
                 $scope.recordList.push(newrecord);
                 $("#modalAddRecord").modal("hide");
             }
             else {
	             console.log("error time")
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new operator error.");
         });
    };

    //Load "Edit of Record"
    $scope.loadRecordEdit = function (id) {
        myblockui();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/get.php?id=" + id;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.recordEdit = result.data;
            $scope.placeholder_type = result.data[0].type;
            $scope.placeholder_priority = result.data[0].priority ;            

            $("#modalEditRecord").modal("show");
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    //Update "Edit of Record"
    $scope.updateRecordEdit = function () {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update.php?id="  + $scope.recordEdit.id;
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
                     common_name: $scope.recordEdit.common_name,
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
        $("#modalAddRecord").modal("show");
    };

    $scope.loadRecordList();
}]);




swdApp.controller('Batch_List', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Batch";
	$scope.printed_or_not = '0';
	$scope.batch = {};
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
 
		
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
	$scope.ShippedDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
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
        var api_url = window.cfg.apiUrl + "alclair_batch/get_batches.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.TotalRecords)
	            
                $scope.BatchesList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
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
                toastr.error("Get batches error.");
            });
    };
    
    $scope.UpdateList = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        console.log("ID2 is " + $scope.batch_type_id2)
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
        var api_url = window.cfg.apiUrl + "alclair_batch/get_batches.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&group_type_id=" + $scope.batch_type_id2;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.TotalRecords)
	            
                $scope.BatchesList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
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
                toastr.error("Get batches error.");
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

    $scope.Receive_Batch= function (id) {
        console.log(id);
        if (confirm("Are you sure you want to receive this batch?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair_batch/receive_batch.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.BatchesList.length; i++) {
                if ($scope.BatchesList[i].id == id) {
                    toastr.success("Batch received successfully!", "Message");
                    window.location.href = window.cfg.rootUrl + "/alclair_batch/batch_list/";
					//$scope.BatchesList.splice(i, 1);
                    //$scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete form, please try again.");
        });
    };

	$scope.Edit_Batch = function (ID_is) {	
		console.log("The ID is " + ID_is)
		window.location.href = window.cfg.rootUrl + "/alclair_batch/edit_batch/"+ID_is;
	};
	
    $scope.deleteBatch= function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this batch?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair_batch/delete_batch.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.BatchesList.length; i++) {
                if ($scope.BatchesList[i].id == id) {
                    toastr.success("Delete batch successful!", "Message");
                    $scope.BatchesList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete form, please try again.");
        });
    };

	$scope.archiveBatch= function (id) {
        console.log(id);
        if (confirm("Are you sure you want to archive this batch?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair_batch/archive_batch.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.BatchesList.length; i++) {
                if ($scope.BatchesList[i].id == id) {
                    toastr.success("Delete batch successful!", "Message");
                    $scope.BatchesList.splice(i, 1);
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
	    $scope.PRINTED_OR_NOT = AppDataService.PRINTED_OR_NOT;
	    AppDataService.loadBatchTypeList(null, null, function (result) {
           $scope.batchTypeList2 = result.data;
           $scope.batch.batch_type_id = 1;
        }, function (result) { });
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";


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
    			        
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
    }
            
     $scope.showAddBatch = function () {
	    //$scope.batch = [];
        $("#addBatch").modal("show");
		
        AppDataService.loadBatchTypeList(null, null, function (result) {
           $scope.batchTypeList = result.data;
           $scope.batch.batch_type_id = 1;
        }, function (result) { });
		AppDataService.loadBatchStatusList(null, null, function (result) {
           $scope.batchStatusList = result.data;
           $scope.batch.batch_status_id = 1;
        }, function (result) { });

    };
    
    $scope.Submit_Batch = function () {
		
		//console.log("Batch Status ID is " + $scope.batch.batch_status_id)
		//console.log("Batch Ship date  is " + $scope.ShippedDate)
		//console.log("Batch Name is " + $scope.batch.batch_notes)
	
        var api_url = window.cfg.apiUrl + 'alclair_batch/add_batch.php?ShippedDate=' + moment($scope.ShippedDate).format("MM/DD/YYYY");
		//console.log(api_url+"?"+$scope.batch);
		myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.batch),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log("TEST IS " + result.test);
             //console.log("message " + result.code);
            if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                //if (result.result.id !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     toastr.success("Form saved successfully.");
                     $("addBatch").modal("hide");
                     window.location.href = window.cfg.rootUrl + "/alclair_batch/batch_list/";
                 }
                 //else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (result) {
	         console.log("Code is " + result.code)
             toastr.error("Add batch error.");
         });
    
    };
    
    $scope.init();
    	
    }]);
	
	swdApp.controller('Batch_Entry', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Batch";
	$scope.printed_or_not = '0';
	$scope.add_item = {};
    
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
   
   	$scope.updateInHouseNestSteps=function(customer_status) {
		console.log("Customer Status ID is " + customer_status)
        AppDataService.loadInHouseNextStepsList(customer_status, null, function (result) {
           $scope.inHouseNextStepList = result.data;
           $scope.add_item.next_step_id = 1;
           console.log("TEST IS " + result.test)
        }, function (result) { });
    }
    $scope.updateInHouseNestSteps2=function(customer_status) {
		console.log("Customer Status ID is " + customer_status)
        AppDataService.loadInHouseNextStepsList(customer_status, null, function (result) {
           $scope.inHouseNextStepList = result.data;
           $scope.edit_item.next_step_id = 1;
           console.log("TEST IS " + result.test)
        }, function (result) { });
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
	$scope.ImpressionDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
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

        var api_url = window.cfg.apiUrl + "alclair_batch/get_items.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&batch_log_id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.TotalRecords)
	            
                $scope.ItemsList = result.data;
                console.log("Batch name is " + result.Batch_Name)
                $scope.Batch_Name = result.Batch_Name;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
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
                toastr.error("Get batches error.");
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


    $scope.deleteItem= function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this batch?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair_batch/delete_batch.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.BatchesList.length; i++) {
                if ($scope.BatchesList[i].id == id) {
                    toastr.success("Delete batch successful!", "Message");
                    $scope.BatchesList.splice(i, 1);
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
////////////////////////////////////////////////////////////////////////////////////////////////              
	$scope.open_modal=function() {
		var api_url = window.cfg.apiUrl + "alclair_batch/get_batch_name.php?batch_log_id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.edit_batch = result.data[0];
	            console.log("RESULT IS " + $scope.edit_batch.batch_name)
            }).error(function (result) {
                toastr.error("Can not find batch name.");
            });

        $("#editBatchName").modal("show");  
    }
    
	$scope.Update_BatchName=function() {
		console.log("adsf" + $scope.edit_batch.batch_name)
		var api_url = window.cfg.apiUrl + 'alclair_batch/update_batch_name.php?batch_id=' + window.cfg.Id;
		//console.log(api_url+"?"+$scope.batch);
		myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.edit_batch),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log("TEST IS " + result.test);
             //console.log("message " + result.code);
            if (result.code == "success") {
                 $.unblockUI();
                     toastr.success("Updated Name.");
                     $("addItem").modal("hide");
                     window.location.href = window.cfg.rootUrl + "/alclair_batch/edit_batch/"+window.cfg.Id;
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (result) {
	         console.log("Code is " + result.code)
             toastr.error("Add batch error.");
         });    }
//////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;      
    }
    
     $scope.Back2Batches = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair_batch/batch_list/";
	 }
            
     $scope.showAddItem = function () {
	     var api_url = window.cfg.apiUrl + "alclair_batch/get_batches.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.THE_BATCH = result.THE_BATCH
	            $scope.THE_ID = result.THE_ID
            }).error(function (result) {
                toastr.error("Get batches error.");
            });
	    
        $("#addItem").modal("show");
		$scope.customerStatus = AppDataService.customer_status;
		$scope.add_item.customer_status = '0';
        AppDataService.loadInHouseNextStepsList($scope.add_item.customer_status, null, function (result) {
           $scope.inHouseNextStepList = result.data;
           //$scope.batch.batch_type_id = 1;
        }, function (result) { });
        $scope.add_item.next_step_id = 1;
		AppDataService.loadBatchStatusList(null, null, function (result) {
           $scope.batchStatusList = result.data;
           //$scope.batch.batch_status_id = 1;
        }, function (result) { });
    };
    
    $scope.Submit_Item = function () {
		console.log("The ID is " + $scope.add_item.customer_status)

		if($scope.add_item.same_name == true) {
			$scope.add_item.ordered_by = $scope.add_item.designed_for;
		}

        var api_url = window.cfg.apiUrl + 'alclair_batch/add_item.php?ImpressionDate=' + moment($scope.ImpressionDate).format("MM/DD/YYYY") + '&batch_id=' + window.cfg.Id;
		//console.log(api_url+"?"+$scope.batch);
		myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.add_item),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log("TEST IS " + result.test);
             //console.log("message " + result.code);
            if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                //if (result.result.id !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     toastr.success("Item saved successfully.");
                     $("addItem").modal("hide");
                     window.location.href = window.cfg.rootUrl + "/alclair_batch/edit_batch/"+window.cfg.Id;
                 }
                 //else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (result) {
	         console.log("Code is " + result.code)
             toastr.error("Add batch error.");
         });
    };
    
	$scope.Update_Item = function (THE_ID) {
		console.log("The ID is " + $scope.THE_ID)

		if($scope.add_item.same_name == true) {
			$scope.add_item.ordered_by = $scope.add_item.designed_for;
		}

        var api_url = window.cfg.apiUrl + 'alclair_batch/edit_item.php?ImpressionDate=' + moment($scope.ImpressionDate).format("MM/DD/YYYY") + '&item_id=' + THE_ID;
		//console.log(api_url+"?"+$scope.batch);
		myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.edit_item),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         
             console.log("TEST IS " + result.test);
             //console.log("message " + result.code);
            if (result.code == "success") {
                 $.unblockUI();
                     toastr.success("Item saved successfully.");
                     $("editItem").modal("hide");
                     window.location.href = window.cfg.rootUrl + "/alclair_batch/edit_batch/"+window.cfg.Id;
                     console.log("SUCCESS" );
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
                 console.log("ELSE" );
             }
         }).error(function (result) {
	         //console.log("Code is " + result.code)
	         console.log("ERROR" );
             toastr.error("Add batch error.");
         });
    };

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
    $scope.Edit_Item = function (ID_is) {	
	    console.log("ID is " + ID_is)
	     var api_url = window.cfg.apiUrl + "alclair_batch/get_items.php?item_id=" + ID_is;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //$scope.THE_BATCH = result.THE_BATCH;
	            //$scope.THE_ID = result.THE_ID;
	            console.log("ID FOR NEXT STEP IS " + JSON.stringify(result.data[0].next_step_id))
	            $scope.edit_item = result.data[0];
	             if($scope.edit_item.same_name == true) {
		            $scope.edit_item.same_name = 1;
	            } else {
		            $scope.edit_item.same_name = 0;
	            }

	            if($scope.edit_item.paid == true) {
		            $scope.edit_item.paid = 1;
	            } else {
		            $scope.edit_item.paid = 0;
	            }
	             $("#editItem").modal("show");
				 $scope.customerStatus = AppDataService.customer_status;
				 //$scope.add_item.customer_status = '0';
				 AppDataService.loadInHouseNextStepsList(result.data[0]["customer_status"], null, function (result) {
				 	$scope.inHouseNextStepList = result.data;
				 	//$scope.batch.batch_type_id = 1;
				 }, function (result) { });
				 //$scope.add_item.next_step_id = 1;
				 //window.location.href = window.cfg.rootUrl + "/alclair_batch/edit_batch/"+ID_is;
            }).error(function (result) {
                toastr.error("Get batches error.");
            });
	};
	
    $scope.deleteItem= function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this item?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair_batch/delete_item.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ItemsList.length; i++) {
                if ($scope.ItemsList[i].id == id) {
                    toastr.success("Delete item successful!", "Message");
                    $scope.ItemsList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete item, please try again.");
        });
    };
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
   $scope.init = function () {
	
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";

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

    
    $scope.init();
   
	}]);
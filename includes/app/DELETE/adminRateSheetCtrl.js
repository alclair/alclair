swdApp.controller('adminRateSheetCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.productTypeAdd = {};    //Add of Record
    $scope.apifolder = "ratesheet";
    $scope.apifolder2 = "watertype";
    $scope.entityName = "Rate Sheet";
    $scope.entityName2 = "Product Type";
    $scope.rate_sheet_list = window.cfg.rate_sheet_list;
    
    $scope.recordAdd = {
	    bill_to_id: '0',
    }
    
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ratesheet/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText;
        $http.get(api_url).success(function (result) {
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
			$.unblockUI();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get operator error.");
        });
    }

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

    //Upload "Add of Record"
    $scope.uploadAddRecord = function () {
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

    //Load "Edit of Record"
    $scope.loadRecordEdit = function (id) {
        myblockui();
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/get.php?id=" + id;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.recordEdit = result.data;
            
            $("#modalEditRecord").modal("show");
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    //Update "Edit of Record"
    $scope.updateRecordEdit = function () {
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
                 $scope.LoadData();
                 //$scope.LoadAllData();
                 $("#modalEditRecord").modal("hide");
             }
         }).error(function (data) {
             toastr.error("Update error.");
         });
    };

    $scope.loadRecordAddModal = function () {
    	
        $("#modalAddRecord").modal("show");
        
    };
    
    $scope.loadProductTypeAddModal = function () {
    
        $("#modalAddProductType").modal("show");
        $scope.getProduct_Types();
        
    };
    
     //Upload "Add of Record"
    $scope.uploadAddProductType = function () {

	var api_url = window.cfg.apiUrl + "ratesheet/does_exist.php";
	$http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.productTypeAdd),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
			.success(function (result) {
             	console.log(result.product_type_exists);
			 	if (result.product_type_exists != null ) {
			 		toastr.error("Not allowed to have duplicate product types!");  
			 		$.unblockUI(); 
			 		}
			 	else {
	
        var api_url = window.cfg.apiUrl + $scope.apifolder2 + "/add.php";
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.productTypeAdd),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success(result.message);
                 var newrecord = {
                     id: result.data.id,
                     name: $scope.productTypeAdd.name,
                 };
                 $scope.recordList.push(newrecord);
                 //$scope.TotalRecords += 1;
                 $("#modalAddProductType").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error("Product type add error.");
                 //toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Add new product type error.");
         });
                            }  // END THE ELSE STATEMENT
         });
       //}  // END THE ELSE STATEMENT
    };


	$scope.getProduct_Types = function () {
        var api_url = window.cfg.apiUrl + "ratesheet/get_product_types.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.producttypes = data.data;
            //alert($scope.wells.length);
        })
    }
    
    $scope.product_type_warning = function() {
		var api_url = window.cfg.apiUrl + 'ratesheet/does_exist.php';
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.productTypeAdd),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result.product_type_exists);
             console.log(result.post);
             console.log(result.request);
             //console.log($scope.ticket.ticket_num)
             if (result.product_type_exists != null) {
			 //if ($scope.ticket.ticket_number.id != null) {
			 	toastr.error("This is a duplicate product type!");
                 $.unblockUI();
             }
             else {
                 $.unblockUI();
                 //toastr.error("Not a repeated ticket.");
             }
         });
    }   


    $scope.init=function()
    {
	    $scope.trd_bill_to = AppDataService.trd_bill_to;
	    
	    $scope.uniqueCustomerList = [];    
        AppDataService.loadUniqueCustomerList(null, null, function (result) {
            $scope.uniqueCustomerList = result.data;
            //$scope.uniqueCustomerList.unshift({ id: 0, name: "Pick a customer (not required)" });
        }, function (result) { });
        //$scope.waterTypeList = [];    
        AppDataService.loadWaterTypeList(null, null, function (result) {
            $scope.waterTypeList = result.data;
        }, function (result) { });
		AppDataService.loadTruckingCompanyList(null, null, function (result) {
            $scope.truckingCompanyList = result.data;
        }, function (result) { });
        AppDataService.loadDisposalWellList(null, null, function (result) {
            $scope.disposalWellList = result.data;
        }, function (result) {
        });
        $scope.rigsList = [];    
        AppDataService.loadRigsList(null, null, function (result) {
            $scope.rigsList = result.data;
            //$scope.uniqueCustomerList.unshift({ id: 0, name: "Pick a customer (not required)" });
        }, function (result) { });


    $scope.recordAdd.delivery_method = '0';
    $scope.recordAdd.water_source_type = '0';
    $scope.DeliveryMethodList = AppDataService.DeliveryMethodList;
    $scope.WaterSourceTypeList = AppDataService.WaterSourceTypeList;

	    
        $scope.LoadData();

    }
    $scope.init();
}]);
 


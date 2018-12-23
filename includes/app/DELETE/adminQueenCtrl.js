swdApp.controller('adminQueenCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "queens";
    $scope.entityName = "Queen";
    $scope.parent_operator_list = window.cfg.parent_operator_list;

    
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
   // if (window.cfg.Id > 0)
    //    $scope.PageIndex = window.cfg.Id;
    $scope.LoadData = function () {
        myblockui();
        //var api_url = window.cfg.apiUrl + "queens/get.php";
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/get.php";
        $http.get(api_url).success(function (result) {
	        	$scope.recordList = result.data;
	        	$scope.TotalRecords = result.TotalRecords;

				$.unblockUI();
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get queens error.");
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
                 console.log("BEFORE HIDE")
                 $("#modalEditRecord").modal("hide");
				 console.log("AFTER HIDE")
             }
         }).error(function (data) {
             toastr.error("Update error.");
         });
    };

    $scope.loadRecordAddModal = function () {
        $("#modalAddRecord").modal("show");
    };
    
    $scope.init=function()
    {
		
		var api_url = window.cfg.apiUrl + $scope.apifolder + "/get.php?find_customer_id=" + 1;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            $scope.customer_info = result.test;
			 //console.log("Testing is " + JSON.stringify($scope.customer_info.customer_id))
			 //console.log("Testing is " + result.test)
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
		
		AppDataService.loadCustomers_LNG(null, null, function (result) {
            $scope.customerList = result.data;
        }, function (result) {});
        
        AppDataService.loadLngQueensList(null, null, function (result) {
            $scope.queensList = result.data;
        }, function (result) {});
        
        console.log("Testing is " + $scope.queensList)

        $scope.LoadData();
        


        
        //console.log("Session user name is " + $_SESSION["UserName"]);
        
    }
    $scope.init();
}]);
 


swdApp.controller('adminRigsCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "rigs";
    $scope.entityName = "Rig";
    $scope.SearchText = "";
    
    $scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.wells = data.data;
            //alert($scope.wells.length);
        })
    }

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
        if (confirm("Delete this rig?") == false) {
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
            toastr.error("Failed to delete rig, please try again.");
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
                 //var newrecord = {
                 //    id: $scope.recordEdit.id,
                 //    name: $scope.recordEdit.name,               
                 //};
                 for (var i = 0; i < $scope.recordList.length; i++) {
                     if ($scope.recordList[i].id == $scope.recordEdit.id) {
                         $scope.recordList[i] = newrecord;
                         break;
                     }
                 }

                 $("#modalEditRecord").modal("hide");
             }
         }).error(function (data) {
             toastr.error("Update error.");
         });
          location.reload();
    };

    $scope.loadRecordAddModal = function () {
        $("#modalAddRecord").modal("show");
    };
    
	
	$scope.init=function() 
	{
		$scope.getWells();
	}
	$scope.init();
    $scope.loadRecordList();
}]);
 


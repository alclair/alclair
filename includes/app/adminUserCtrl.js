swdApp.controller('adminUserCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "users";
    $scope.entityName = "User";
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
                        $scope.loadRecordList();
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
		//console.log(api_url+"?"+$.param($scope.recordAdd));
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
                 $scope.loadRecordList();
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
	    console.log("The id here is " + $scope.recordEdit.id)
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update.php?id=" + $scope.recordEdit.id;
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
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
                 $scope.loadRecordList();

                 $("#modalEditRecord").modal("hide");
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
 


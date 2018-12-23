swdApp.controller('adminOperatorCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.recordList = {};   //List of Record
    $scope.recordAdd = {};    //Add of Record
    $scope.recordEdit = {};   //Edit of Record
    $scope.apifolder = "operators";
    $scope.entityName = "Producer";
    $scope.parent_operator_list = window.cfg.parent_operator_list;

    
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
        var api_url = window.cfg.apiUrl + "operators/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText;
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
                 $scope.LoadAllData();
                 $("#modalEditRecord").modal("hide");
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
        $scope.LoadData();
        
    }
    $scope.init();
}]);
 


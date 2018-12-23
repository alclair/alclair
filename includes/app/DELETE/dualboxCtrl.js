swdApp.controller('ticketAddCtrl', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
	//$scope.to= {};

	//$scope.right_listbox="";


    /*$scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/json_get.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.wells = data.data;
            //alert($scope.wells.length);
        })
    }*/
    
    $scope.SaveData = function () {
        
        //var SearchDisposalWell = $scope.SearchDisposalWell;
        //var ctrl.timepicker = moment($scope.ctrl.timepicker).format("H:i:s");

        //myblockui();

        var params = [];

        var api_url = window.cfg.apiUrl + "wells/customer_source_wells_table.php?"; 
		console.log(api_url+"?");
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            //data: $.param($scope.to),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);

             if (result.code == "success") {
                // $.unblockUI();
                 //alert(result.data.id);
                 if (result.data.id !=undefined)
                 {
                     $scope.ticket.id = result.data.id;
                   //  $scope.UploadFile();
                 }
                 else
                 {
                     
                 }
                 //redirect
             }
             else {
                 //$.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert ticket error.");

         });
    };



    $scope.init=function()
    {
        //$scope.getWells();
    }
    $scope.init();
}]);
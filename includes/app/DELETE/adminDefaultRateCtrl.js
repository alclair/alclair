swdApp.controller('adminDefaultRateCtrl', ['$http', '$scope', function ($http, $scope) {    
    $scope.recordEdit = {};   //Edit of Record
	
    $scope.apifolder = "rates";
    $scope.entityName = "Default Rate";    
   $scope.deleteRecord=function(id)
   {
	   if(confirm("Are you sure you want to delete this record?"))
	   {
		   var api_url=window.cfg.apiUrl+$scope.apifolder+"/deleteDefaultRate.php?id="+id;
			myblockui();
			$http.get(api_url).success(function(data){
				$scope.loadRecords();
				$.unblockUI();			
			});
	   }
   }
    //Update "Edit of Record"
    $scope.updateRecordEdit = function () {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/updateDefaultRate.php";
        //console.log(api_url + "?" + $.param($scope.recordEdit));
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
				 $scope.loadRecords();
                 toastr.success(result.message);               
             }
         }).error(function (data) {
             toastr.error("Update error.");
         });
    };
	$scope.loadRecords=function()
	{
		var api_url=window.cfg.apiUrl+$scope.apifolder+"/getDefaultRates.php";
		myblockui();
		$http.get(api_url).success(function(data){
			$scope.defaultRates=data.data;
			$.unblockUI();			
		});
	}
    
    $scope.init=function()
    {        
        $scope.water_type_list = window.cfg.water_type_list;
        $scope.disposal_well_list = window.cfg.disposal_well_list;
		$scope.loadRecords();
    }
    $scope.init();
}]);



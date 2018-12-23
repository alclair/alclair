swdApp.controller('cronCtrl', ['$http', '$scope', function ($http, $scope) {
	$scope.vm={};
	
	$scope.SendEmailNow=function(mode)
	{		
		$scope.SaveData(mode);		
	}
	
	$scope.LoadData=function()
	{
		var api_url=window.cfg.rootUrl+"/api/cron/get.php";
		$http.get(api_url).success(function(data){
		
			$scope.vm.daily_emails=data.daily_emails;
			$scope.vm.weekly_emails=data.weekly_emails;
			$scope.vm.monthly_emails=data.monthly_emails;
		});
	}
	$scope.SaveData=function(mode)
	{
		var api_url=window.cfg.rootUrl+"/api/cron/save.php";
		//console.log(api_url+"?"+$.param($scope.vm));
		$http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (data) {		
			//console.log(data);	
			
			if(data.code=="success")
			{
				if(mode!=undefined)
				{
					var api_url=window.cfg.rootUrl+"/api/cron/cron."+mode+".php";
					myblockui();
					$http.get(api_url).success(function(data){
						toastr.success("Emails are sent successfully.");
						$.unblockUI();
					});
				}
				else
				{
					toastr.success("Settings are saved successfully.");
				}
			}
			else
			{
				toastr.error(data.message);	
			}
		});
	}
    $scope.init=function()
	{
		$scope.LoadData();
	}
	$scope.init();
}]);
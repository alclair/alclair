swdApp.controller('edit_Customer_Comments', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    
    $scope.EditComment = function (key, ID) {			
	    console.log("HERE is " + key + " and id is " + ID)
		var api_url = window.cfg.apiUrl + 'customer_comments/get_comments.php?id=' + ID;
        $http.get(api_url)
            .success(function (result) {
	            $scope.editComment = result.data;
	            console.log("After comment " + result.data)
	            $('#modalEditComment').modal("show");
            }).error(function (result) {
                $.unblockUI();
				toastr.error(result.message == undefined ? result.data : result.message);
            });   
	}
	
	$scope.SaveComment = function (ID) {		
		var api_url = window.cfg.apiUrl + 'customer_comments/save_comment.php?id=' + ID; 
		console.log("The ID to EDIT is " + ID)
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.editComment),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
			 	$.unblockUI();
			  	setTimeout(function(){
			  		location.reload();				 	
				}, 500);                
         }).error(function (data) {
             toastr.error("Error saving notes.");
         });	
	}

    $scope.LoadData = function () {
        myblockui();
        console.log("HERE WE WANT " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "customer_comments/get_comments.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            for(i=0; i < result.count; i++) {
		            if(!result.data[i]["comment"]) {
			            result.data[i]["date"] = "EMPTY";
		            }
		        }
	            
	           $scope.commentList = result.data;
	           console.log("TEST IS " + result.test)
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get travelers error.");
            });
        /*AppDataService.loadFileList({ traveler_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });*/
    }
    
    $scope.init = function () {
        $scope.LoadData();

        AppDataService.loadMonitorList_not_Universals(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadImpressionColorList(null, null, function (result) {
           $scope.impressionColorList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.OrderStatusList = result.data;
        }, function (result) { });
    }
    $scope.init();
}]);

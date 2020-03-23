swdApp.controller('ImportFile', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
	$scope.month = "06";
	$scope.year = 2019;
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }

    
    $scope.UploadData = function () {
        var api_url = window.cfg.apiUrl + 'horizon/form16.php?Month=' + $scope.month + '&Year='  + $scope.year;
        
        //alert(api_url);)
        if ($scope.selectedFiles.length == $scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.qc_form,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
	               //console.log("Test2222 is " + data.test)
	               //console.log("Testing3 is " + data.testing3)
                  if (data.code == "success") {
                  	toastr.success("Document is saved successfully.");
						console.log("Testing0 is " + data.testing0)
						console.log("Testing1 is " + data.testing1)
						console.log("Testing2 is " + data.testing2)
						console.log("Testing3 is " + data.testing3)
						console.log("Testing4 is " + data.testing4)
						console.log("Testing5 is " + data.testing5)

					    $scope.Print2Screen = data.print2screen;
					    $scope.facility2_open = data.facilty2_open;
					    $scope.facility2_close = data.facilty2_close;
					    $scope.TotalRows = data.TotalRows;
                   }
                   else {
	                   console.log("Code is " + data.code)
	                   console.log("Error message is " + data.error_message)
	                   //console.log("Testing1 in else" + data.testing1)
                       if(data.message)
	                       toastr.error(data.message);
	                   if(data.error_message)
	                       toastr.error(data.error_message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
	               console.log("Stuck here 2nd Else")
	                console.log("Testing1 in error " + data.testing1)
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
        }

    }
               
    $scope.init=function()
    {

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
        });
    }
    $scope.init();
}]);
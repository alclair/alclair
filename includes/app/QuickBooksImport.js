swdApp.controller('importQuickBooks', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }

    
    $scope.UploadData = function () {
        var api_url = window.cfg.apiUrl + 'alclair/import_quickbooks.php';
        //alert(api_url);)
        if ($scope.selectedFiles.length == $scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            //var file = new File([""], "/Users/TYLER/Downloads/YTD Marketing Expenses (1).csv");
            console.log("The selected filed is " + file)
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $upload.upload({
                url: api_url,
                method: 'POST',
                data: $scope.qc_form,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   console.log("Testing1 is " + data.testing1)
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
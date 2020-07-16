swdApp.controller('ImportFile', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
	$scope.month = "2";
	$scope.year = 2020;
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    
    //$scope.store_data = new Array();
	$scope.store_data = {
 		customer_id: '',
 		date: '',
 		author: '',
 		body: '',
	};
	
	$scope.c_id ='';
	$scope.body = '';
	$scope.date = '';
	$scope.author = '';
	$scope.no_record = '';
	$scope.UploadData2 = function () {
		//console.log("Files are " + $scope.selectedFiles.length)
	    var file2 = [];
	    var file3 = [];
	    for(i=0; i < $scope.selectedFiles.length; i++) {
		    file2[i] = $scope.selectedFiles[i];
		    file3[i] = $scope.selectedFiles[i]["name"];
		    console.log("File is " + file2[i]["name"])
		 }
		 var x = file3.toString();
		 console.log("STRING is " + x)
        var api_url = window.cfg.apiUrl + 'yaml/read.php?THE_FILES=' + x; 

		$http.get(api_url)
		.success(function (data) {
			console.log("TEST IS " + data.test)				;
			//$.unblockUI();
        }).error(function (result) {
			//toastr.error("Did not grab all orders.");
        });
	};
    
    $scope.UploadData = function ($files) {

	//for(i=0; i < $scope.selectedFiles.length; i++) {
		//setTimeout(function(){
		var the_files = $scope.selectedFiles;
		the_files.forEach(function(item, index, the_files) {
			//console.log("item: " + item + " at index: " + index + " in the array: " + the_files)
		//setTimeout(function(){
			setTimeout(function(){
				console.log("Index Is " + index)
			}, 250 * (index+1) )  
		  var api_url = window.cfg.apiUrl + 'yaml/read.php' ; 
	        //if ($scope.selectedFiles.length == $scope.selectedFiles.length > 0) {
		    //if ($scope.selectedFiles.length > 0) {
				
	            //var file = $scope.selectedFiles;
	            var file = item;
	            myblockui();
	            /*if (file.size > 5097152) {
	                $scope.error = 'File size cannot exceed 5 MB';
	                toastr.error($scope.error);
	            }*/
	            $upload.upload({
	                url: api_url,
	                method: 'POST',
	                headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
	                withCredentials: true,
	                data: $scope.store_data,
	                file: file,
	                fileFormDataName: 'documentfile'
	            })
	               .success(function (data) {
				   		//console.log("Test is " + data.test)
		               $scope.store_data = {
					   		customer_id: data.customer_id,
					   		date: data.date,
					   		author: data.author,
					   		body: data.body,
					   	};
					   	$scope.body = $scope.body + data.body;
					   	$scope.c_id = $scope.c_id + data.customer_id;
					   	$scope.date = $scope.date + data.date;
					   	$scope.author = $scope.author + data.author;
					   	$scope.no_record = $scope.no_record + data.no_record;
					   	//body2 = body2 + data.body;
					   	
	                  if (data.code == "success") {
	                  	toastr.success("Document is saved successfully.");
							console.log("TESTING IS " + data.test)
	                   } else {
						
						}
	                   $.unblockUI();
	               })
	               .error(function (data) {
		               console.log("Stuck here 2nd Else")
		                //console.log("Testing1 in error " + data.testing1)
	                   toastr.error("Error to save the document.");
	                   $.unblockUI();
	               });
	        //}
	        //else
	        //{
	            //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
	        //}
    	//}, 500 )    
    //} // CLOSE FOR LOOP		
    //}, 1000 * (index+1) )   			
	    }) // CLOSE FOREACH  
	    setTimeout(function(){
	    	console.log("All the dates " + $scope.date)
	    	
			var api_url = window.cfg.apiUrl + "yaml/create_excel.php?customer_id=" + $scope.c_id + "&date=" + $scope.date + "&author=" + $scope.author + "&body=" + $scope.body + "&no_record=" + $scope.no_record;
			//var api_url = window.cfg.apiUrl + 'yaml/create_excel.php';
			console.log("asfdasf")
			$http.get(api_url)
				.success(function (result) {
					console.log("Who did not get in is " + result.yaml_not_working)
					$scope.print_YAML = result.yaml_not_working;
					$.unblockUI();
   				}).error(function (result) {
   					console.log("Order number that could not be found was ")
    		});
	    	
	    }, 350 * the_files.length )  
    }
               
    $scope.init=function()
    {

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
        });
    }
    $scope.init();
}]);
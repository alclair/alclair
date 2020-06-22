swdApp.controller('Tickets', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	    
	$scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
        $scope.UploadFile($files);
    }
    
    $scope.UploadFile = function ($files) {
	    console.log("JSON is " + JSON.stringify($files))
		for(i=0; i < $files.length; i++) {
			console.log(($files[i].name))

			var api_url = window.cfg.apiUrl + 'trd/import.php?files=' + $files[i];
				 //alert(api_url);
	        if ($scope.selectedFiles.length > 0) {
	            var file = $scope.selectedFiles[0];
	            myblockui();
	            if (file.size > 5097152) {
	                $scope.error = 'File size cannot exceed 5 MB';
	                toastr.error($scope.error);
	            }
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
		               console.log(data);
		               console.log(data.test)
	                   if (data.code == "success") {
	                       toastr.success("Document is saved successfully.");
	                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_list/";
	                   }
	                   else {
	                       toastr.error(data.message);
	                   }
	                   $.unblockUI();
	               })
	               .error(function (data) {
	                   toastr.error("Error uploading documents.");
	                   $.unblockUI();
	               });
	        }
	        else
	        {
	           // window.location.href = window.cfg.rootUrl + "/alclair/qc_list/";
	        }
		} // CLOSE FOR LOOP
    }
    
    $scope.Test123 = function () {
	 	console.log("CLICKED")
	 	console.log("output is " + $scope.TicketList[0].id)
	 	for(i=0; i < $scope.TicketList.length; i++) {
	 		id = "id_" + i;
	 		document.getElementById(id).checked = true;
		}
	 }
	 $scope.Test456 = function (key) {
	 	console.log("2nd one")
	 	console.log("Key is  " + key + " and checkbox is " + $scope.TicketList[key].checkbox)
	 	document.getElementById("id_3").checked = true;
	 }
    
    $scope.open_add_order = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair/add_order/";
 	}
	
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
	$scope.openEnd = function ($event) {        
        $scope.openedEnd = true;
    };
	$scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;//OctoberOne;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
	//$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";
        $scope.cust_name = "";
		$scope.qc_form = {
	        cust_name: "",
    		};
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
    }
    
    $scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		
        var api_url = window.cfg.apiUrl + "trd/get_tickets.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
                $scope.TicketList = result.data;
                for(i=0; i < $scope.TicketList.length; i++) {
	                $scope.TicketList[i].checkbox = 4;
                }
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                //console.log("Pass or Fail is " + result.testing1)

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
                toastr.error("Error loading data.");
            });
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this order?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair/delete_order.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.OrdersList.length; i++) {
                if ($scope.OrdersList[i].id == id) {
                    toastr.success("Delete Order successful!", "Message");
                    $scope.OrdersList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete form, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {

   		/*if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}*/
        $scope.LoadData();
    }
    			    
    $scope.PDF = function (id) {
	    //var $order_id = 9729;
        
        //myblockui();
        console.log("Order num is " +  id)
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF.php?ID=" + id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Result is " + result.data);

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }
    
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
      
    }
        
    $scope.DONE = function () {
	    //var $order_id = 9729;
        myblockui();
        
        var api_url = window.cfg.apiUrl + "alclair/move_to_done.php?ID=" + $scope.id_to_make_done +"&DoneDate=" + moment($scope.done_date).format("MM/DD/YYYY");
		//console.log("api url is " + api_url)
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Test1 is " + result.test1);
			toastr.success("Successfully moved to Done.");
			$scope.LoadData();
			$('#SelectDateModal').modal("hide");

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
/*        
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.done_date),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .success(function (result) {
             if (result.code == "success") {
			 	 $.unblockUI();
			 	 console.log("Test is " + result.test1);
			 	 toastr.success("Successfully moved to Done.");
			 	 $scope.LoadData();
			 	 $('#SelectDateModal').modal("hide");       
             }
             else {
                 $.unblockUI();
				 console.log("Test is " + result.test1);
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save traveler error.");
         });
*/
    }
    
    $scope.init();
	}]);
	
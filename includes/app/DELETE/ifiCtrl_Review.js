swdApp.controller('enter_Person', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.reviewer = {
 	title_id: 1,
 	tag_id: 1,
 	iclub_id: 1,
 	status_id: 1,
    country_id: 1,
	qc_date: new Date,
};

	$scope.addJobs = function(reviewers_id) {
		for (i = 0; i < $scope.jobs.length; i++) {
			console.log("Length is  " + $scope.jobs.length)
			console.log("Reviewer ID is still " + reviewers_id)
			var api_url = window.cfg.apiUrl + 'ifi_tbl/add_jobs.php?reviewers_id=' + reviewers_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.jobs[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
	}
	$scope.addUsernames = function(reviewers_id) {
		for (i = 0; i < $scope.usernames.length; i++) {
			console.log("Length is USERNAMES  " + $scope.usernames.length)
			console.log("Reviewer ID USERNAMES " + reviewers_id)
			var api_url = window.cfg.apiUrl + 'ifi_tbl/add_usernames.php?reviewers_id=' + reviewers_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.usernames[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
	}

    $scope.Submit = function(){
		var api_url = window.cfg.apiUrl + 'ifi_tbl/add_reviewer.php';
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.reviewer),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        		})
				.success(function (result) {                          
					if (result.code == "success") {
						$scope.reviewers_id = result.reviewers_id;
						console.log("Reviewer's ID is " + $scope.reviewers_id)
						$scope.addJobs($scope.reviewers_id);
						$scope.addUsernames($scope.reviewers_id);
		
						$.unblockUI();
           			} else {
			 			$.unblockUI();
			 			toastr.error(result.message == undefined ? result.data : result.message);
			 		}
			 	}).error(	function (data) {
					console.log("Code is " + result.code)
		 			toastr.error("Insert reviwer error.");
		 		});

    	//console.log($scope.jobs);  
		//console.log("The SNs are " + $scope.jobs);  
		//console.log("The SNs are " + $scope.jobs[0].serial_numbers);  
		console.log("Count is " + $scope.jobs.length)
		//console.log("Zero is " + $scope.jobs[0].serial_numbers.length)
		//console.log("One is " + $scope.jobs[1].serial_numbers.length)

        // myService.savejobs($scope.jobs);
    }
        
    $scope.jobs = [];
    $count_jobs = 0;
    $scope.newJob = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.jobs.push({});
        $scope.jobs[$count_jobs] = {
			sector_id: 1
		}
        $count_jobs = $count_jobs + 1;
    }
    $scope.removeJob = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.jobs.pop({});
    }
    
    $scope.usernames = [];
    $count_usernames = 0;
    $scope.newUsername = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.usernames.push({});
        $scope.usernames[$count_usernames] = {
			sector_id: 1
		}
        $count_usernames = $count_usernames + 1;
    }
    $scope.removeUsername = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.usernames.pop({});
    }
           
    $scope.init=function()
    {

        AppDataService.loadEmployeeList(null, null, function (result) {
           $scope.employeeList = result.data;
        }, function (result) { });
        AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_ProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
        
        AppDataService.load_tbl_titleList(null, null, function (result) {
           $scope.titleList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_tagList(null, null, function (result) {
           $scope.tagList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_iclubList(null, null, function (result) {
           $scope.iclubList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_statusList(null, null, function (result) {
           $scope.statusList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_sectorList(null, null, function (result) {
           $scope.sectorList = result.data;
        }, function (result) { });
         AppDataService.load_tbl_countryList(null, null, function (result) {
           $scope.countryList = result.data;
        }, function (result) { });
        


        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ifi_Reviewers', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Reviewers";
	$scope.printed_or_not = '0';
    
	$scope.update_products=function(category_id) {
		console.log("Category ID is " + category_id)
	    AppDataService.loadProductsInCategoryList(category_id, null, function (result) {
           $scope.productsInCategoryList = result.data;
        }, function (result) { });
    }
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
	
    
    $scope.serial_number = {
        cust_name: '',
        pass_or_fail: '0',
        qc_date: new Date,
    };

	
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
	$scope.SearchStartDate=window.cfg.OctoberOne; //CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	console.log("Date is " + window.cfg.OctoberOne)
	console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
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
		console.log("Product id is " + $scope.product_id)
		console.log("Search text is " + $scope.SearchText)
		console.log("Status Value is " + $scope.status_value)
        var api_url = window.cfg.apiUrl + "ifi_tbl/get_reviewers.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&product_id=" + $scope.product_id + "&status=" + $scope.status_value;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.test2)
                $scope.ReviewersList = result.data;
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
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
                toastr.error("Get QC Form error.");
            });
    };

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteReviewer = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this reviewer?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "ifi_tbl/delete_reviewer.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ReviewersList.length; i++) {
                if ($scope.ReviewersList[i].id == id) {
                    toastr.success("Reviewer successfully deleted.", "Message");
                    $scope.ReviewersList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete reviewer, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
	
	$scope.peopless = [];
	$scope.getReviewersForSearch= function () {
	    var api_url = window.cfg.apiUrl + "ifi_tbl/get_reviewers_for_search.php";
	        $http.get(api_url).success(function (data) {
	            $scope.peoples = data.data;
	    })
	}
       
    $scope.init = function () {
		$scope.getReviewersForSearch();
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";

        AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
		AppDataService.loadCategoryTypeList(null, null, function (result) {
           $scope.categoryTypeList = result.data;
        }, function (result) { });
        $scope.StatusList = AppDataService.loadStatusList;

        
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
    
    $scope.init();
}]);

swdApp.controller('Edit_Reviewer', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
	
	var reviewers_id = 	window.cfg.Id;
    $scope.jobs = [];
    var jobs_IDs = [];
    $scope.newJob = function($event){
        // prevent submission
        $count_jobs = $scope.jobs.length;
        $event.preventDefault();
        $scope.jobs.push({});
        $scope.jobs[$count_jobs] = {
			sector_id: 1
		}
        $count_jobs = $count_jobs + 1;
    }
    $scope.removeJob = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.jobs.pop({});
    }
    
    $scope.usernames = [];
    var usernames_IDs = [];
    $scope.newUsername = function($event){
        // prevent submission
        $count_usernames = $scope.usernames.length;
        $event.preventDefault();
        $scope.usernames.push({});
        $scope.usernames[$count_usernames] = {
			sector_id: 1
		}
        $count_usernames = $count_usernames + 1;
    }
    $scope.removeUsername = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.usernames.pop({});
    }

    
    $scope.Update = function(){
	    console.log("IDs are " + jobs_IDs)
	    		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////// DETERMINING IF UPDATING, ADDING OR DELETING A JOB ////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for (k = 0; k < jobs_IDs.length; k++) {
		    for (i = 0; i < $scope.jobs.length; i++) {
			    if(jobs_IDs[k] == $scope.jobs[i].id) {
					// UPDATE JOB
					console.log("UPDATE: The ID is " + $scope.jobs[i].id)
					
					var api_url = window.cfg.apiUrl + 'ifi_tbl/update_job.php'; //?id=' + JOBS_IDs[m];
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.jobs[i]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {                          
						if (result.code == "success") {		
							$.unblockUI();
           				} else {
			 				$.unblockUI();
			 				console.log("adsfasdfasdfasdfsdf")
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
						console.log("Code is " + result.code)
						toastr.error("Insert job error.");
		 			});
					
					break;
				} else if(i == $scope.jobs.length-1) {
					// DELETE JOB AT ID = k
					console.log("DELETE: The ID is " + jobs_IDs[k])
					$http.get(window.cfg.apiUrl + "ifi_tbl/delete_job.php?id=" + jobs_IDs[k]).success(function (result) {
                    }).error(function (result) {
						toastr.error("Failed to delete job, please try again.");
        			});					
				}
			}	
		}
		for (i = 0; i < $scope.jobs.length; i++) {
	    	if(!$scope.jobs[i].id)  {	
			    // ADD JOB
				console.log("ADD: There is no ID " + $scope.jobs[i].id)
				
				var api_url = window.cfg.apiUrl + 'ifi_tbl/add_jobs.php?reviewers_id=' + reviewers_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.jobs[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert job error.");
				});
			}
		}
		///////////////////////    END DEALING WITH USERNAMES   /////////////////////////////////
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////// DETERMINING IF UPDATING, ADDING OR DELETING A USERNAME /////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for (m = 0; m < usernames_IDs.length; m++) {
		    for (n = 0; n < $scope.usernames.length; n++) {
			    if(usernames_IDs[m] == $scope.usernames[n].id) {
					// UPDATE JOB
					console.log("UPDATE: The ID is " + $scope.usernames[n].id)
					
					var api_url = window.cfg.apiUrl + 'ifi_tbl/update_username.php'; //?id=' + usernames_IDs[m];
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.usernames[n]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {                          
						if (result.code == "success") {		
							$.unblockUI();
           				} else {
			 				$.unblockUI();
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
						console.log("Code is " + result.code)
						toastr.error("Insert username error.");
		 			});
					
					break;
				} else if(n == $scope.usernames.length-1) {
					// DELETE USERNAME AT ID = m
					$http.get(window.cfg.apiUrl + "ifi_tbl/delete_username.php?id=" + usernames_IDs[m]).success(function (result) {
                    }).error(function (result) {
						toastr.error("Failed to delete username, please try again.");
        			});	
				}
			}	
		}
		for (n = 0; n < $scope.usernames.length; n++) {
	    	if(!$scope.usernames[n].id)  {	
			    // ADD USERNAME
				console.log("ADD: There is no ID " + $scope.usernames[n].id)
				
				var api_url = window.cfg.apiUrl + 'ifi_tbl/add_usernames.php?reviewers_id=' + reviewers_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.usernames[n]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert username error.");
				});

			}
		}
		///////////////////////    END DEALING WITH USERNAMES   /////////////////////////////////
		var api_url = window.cfg.apiUrl + 'ifi_tbl/update_reviewer.php';
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.reviewer),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        		})
				.success(function (result) {                          
					if (result.code == "success") {		
						$.unblockUI();
           			} else {
			 			$.unblockUI();
			 			toastr.error(result.message == undefined ? result.data : result.message);
			 		}
			 	}).error(	function (data) {
					console.log("Code is " + result.code)
		 			toastr.error("Insert reviwer error.");
		 		});

    	//console.log($scope.jobs);  
		//console.log("Count is " + $scope.jobs.length)
		console.log("Right here")
        // myService.savejobs($scope.jobs);
    }

                
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ifi_tbl/get_reviewers.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
                    $scope.reviewer = result.data[0];
                    $scope.shippingRequest = result.data_shipping_requests;
                    //$scope.jobsList = result.data_jobs;
                    $scope.jobs = result.data_jobs;
                    $scope.usernames = result.data_usernames;
                    $scope.num_of_jobs = $scope.jobs.length;
                    $scope.num_of_usernames = $scope.usernames.length;
                    //var jobs_IDs = [];
                    for (i = 0; i < $scope.jobs.length; i++) {
						jobs_IDs[i] = $scope.jobs[i].id;
					}
					for (j = 0; j < $scope.usernames.length; j++) {
						usernames_IDs[j] = $scope.usernames[j].id;
					}
					//console.log("The IDs are " + $scope.jobs_IDs)
                    //console.log("Jobs are " + JSON.stringify(result.data_jobs))
  
                    // SET BOOLEAN VALUES FOR EDIT REVIEWER
					// REVIEW AGREEEMENT
					if ($scope.reviewer.ra_sent == true) {
						$scope.reviewer.ra_sent = 1;    
					} else {
						$scope.reviewer.ra_sent = 0;
        			}
        			if ($scope.reviewer.ra_signed == true) {
						$scope.reviewer.ra_signed = 1;    
					} else {
						$scope.reviewer.ra_signed = 0;
        			}
					// END REVIEW AGREEMENT
					
					// NDA
					if ($scope.reviewer.nda_sent == true) {
						$scope.reviewer.nda_sent = 1;    
					} else {
						$scope.reviewer.nda_sent = 0;
        			}
        			if ($scope.reviewer.nda_signed == true) {
						$scope.reviewer.nda_signed = 1;    
					} else {
						$scope.reviewer.nda_signed = 0;
        			}
					// END NDA			
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
    
    $scope.init=function()
    {
		
		$scope.LoadData();
        AppDataService.load_tbl_titleList(null, null, function (result) {
           $scope.titleList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_tagList(null, null, function (result) {
           $scope.tagList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_iclubList(null, null, function (result) {
           $scope.iclubList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_statusList(null, null, function (result) {
           $scope.statusList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_sectorList(null, null, function (result) {
           $scope.sectorList = result.data;
        }, function (result) { });
         AppDataService.load_tbl_countryList(null, null, function (result) {
           $scope.countryList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ifi_ShippingRequest', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.reviewer = {
 	info: 0,
 	reason_id: 1,
 	unit_type_id: 1,
};

 $scope.save = function(){
 	console.log("INFO is " + ($scope.reviewer.info))
  	var api_url = window.cfg.apiUrl + 'ifi_shipping_request/save_request.php?reviewer_id=' + $scope.reviewer.info;
	myblockui();
	$http({
		method: 'POST',
		url: api_url,
		data: $.param($scope.reviewer),
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
	.success(function (result) {                          
		if (result.code == "success") {
			$scope.log_id = result.log_id;
			console.log("Log ID is " + $scope.log_id)
			for (i = 0; i < $scope.users.length; i++) {
				console.log("Log ID is still " + $scope.log_id)
				console.log("QUANTITY IS " + $scope.users[i].quantity)
				var api_url = window.cfg.apiUrl + 'ifi_shipping_request/requested_product.php?log_id=' + $scope.log_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.users[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {   
					console.log("Code is " + result.code)                       
					console.log("Test1 is " + result.test1)
					if (result.code == "success") {
						console.log("In HERE")
						$.unblockUI();
						location.reload();
						toastr.success("Successful.");
					} else {
						console.log("In ELSE HERE")
						//toastr.error(result.message);
						$.unblockUI();
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					console.log("In ERROR")
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
			$.unblockUI();
        } else {
			$.unblockUI();
			console.log("Test is " + $result.test)
			toastr.error(result.message == undefined ? result.data : result.message);
		}
	}).error(	function (data) {
		console.log("Code is " + result.code)
		toastr.error("Insert shp to error.");
	});
	console.log("Count is " + $scope.users.length)
}

$scope.persons = [];
$scope.getPersons = function () {
    var api_url = window.cfg.apiUrl + "ifi_shipping_request/get_persons.php";
     //alert(api_url);
    $http.get(api_url).success(function (data) {
        $scope.persons = data.data;
    })
}

$scope.getReviewerInfo= function() {
	if($scope.reviewer.info != 0) {
		console.log("Full Name is " + JSON.stringify($scope.reviewer.their_name))
		console.log("Name is " + ($scope.reviewer.their_name))
		console.log("INFO is " + ($scope.reviewer.info))
		storing_reviewer_info = $scope.reviewer.info
		var api_url = window.cfg.apiUrl + 'ifi_shipping_request/get_persons.php?id=' + $scope.reviewer.info;

        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.reviewer),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result.output.country);
             console.log("Name from " + result.data.name)
             $scope.reviewer = result.output;
			 $scope.reviewer.their_name = result.data.name;
			 $scope.reviewer.info = storing_reviewer_info;
             //console.log(result.post);
             //console.log(result.request);
             //console.log($scope.ticket.ticket_num)
         });
    }
}   

$scope.LoadData = function (the_id) {
        myblockui();
        
        $testing = the_id - 2;
		if ($scope.users[0].category_id != undefined) {
			console.log("Category ID is " + $scope.users[the_id].category_id)
			console.log("Product ID is " + $scope.users[the_id].product_id)
	    	AppDataService.loadProductsInCategoryList($scope.users[the_id].category_id, null, function (result) {
			$scope.users[the_id].productsInCategoryList = result.data;
        	}, function (result) { });
        	
			$scope.users[the_id].new_or_demo = 1;
			//$('select[name=selValue]').val(1);
			//$('.selectpicker').selectpicker('refresh')
        } else {
	        console.log("Category doesn t exist")
        }
		
    //}

		if(!$scope.SearchText) {
			$scope.SearchText = '';
		}

        var api_url = window.cfg.apiUrl + "ifi/get_products.php?category_type_id=" + $scope.category_id + "&product_id=" + $scope.product_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.recordList = result.data;
	            console.log("TESTING IS  " + result.testing)
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;
                $scope.Passed = result.Passed;

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
                toastr.error("Get Repair Form error.");
            });
    };    
        
    $scope.users = [];
    
    $scope.newUser = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.users.push({});
    }
    $scope.removeUser = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.users.pop({});
    }
           
    $scope.init=function()
    {
		$scope.getPersons();
		
        AppDataService.loadEmployeeList(null, null, function (result) {
           $scope.employeeList = result.data;
        }, function (result) { });

        AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
		AppDataService.loadCategoryTypeList(null, null, function (result) {
           $scope.categoryTypeList = result.data;
        }, function (result) { });
        AppDataService.loadNewOrDemoList(null, null, function (result) {
           $scope.newOrDemoList = result.data;
        }, function (result) { });
        
        AppDataService.load_tbl_ProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
         AppDataService.load_tbl_ProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
        
        AppDataService.load_tbl_titleList(null, null, function (result) {
           $scope.titleList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_tagList(null, null, function (result) {
           $scope.tagList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_iclubList(null, null, function (result) {
           $scope.iclubList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_statusList(null, null, function (result) {
           $scope.statusList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_sectorList(null, null, function (result) {
           $scope.sectorList = result.data;
        }, function (result) { });
         AppDataService.load_tbl_countryList(null, null, function (result) {
           $scope.countryList = result.data;
        }, function (result) { });

		AppDataService.load_tbl_reasonList(null, null, function (result) {
           $scope.reasonList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_unitTypeList(null, null, function (result) {
           $scope.unitTypeList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('reviewsList', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Reviewers";
	$scope.printed_or_not = '0';
    
	$scope.update_products=function(category_id) {
		console.log("Category ID is " + category_id)
	    AppDataService.loadProductsInCategoryList(category_id, null, function (result) {
           $scope.productsInCategoryList = result.data;
        }, function (result) { });
    }
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
	
    
    $scope.serial_number = {
        cust_name: '',
        pass_or_fail: '0',
        qc_date: new Date,
    };

	
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
	$scope.SearchStartDate=window.cfg.OctoberOne; //CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	console.log("Date is " + window.cfg.OctoberOne)
	console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
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
		console.log("Product id is " + $scope.product_id)
		console.log("Search text is " + $scope.SearchText)
		console.log("Status Value is " + $scope.status_value)
        var api_url = window.cfg.apiUrl + "ifi_shipping_request/get_shipping_requests.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&product_id=" + $scope.product_id + "&status=" + $scope.status_value;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                $scope.ReviewsList = result.data;
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
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
                toastr.error("Get QC Form error.");
            });
    };

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteReviewer = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this reviewer?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "ifi_tbl/delete_reviewer.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ReviewersList.length; i++) {
                if ($scope.ReviewersList[i].id == id) {
                    toastr.success("Reviewer successfully deleted.", "Message");
                    $scope.ReviewersList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete reviewer, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
	
	$scope.peopless = [];
	$scope.getReviewersForSearch= function () {
	    var api_url = window.cfg.apiUrl + "ifi_tbl/get_reviewers_for_search.php";
	        $http.get(api_url).success(function (data) {
	            $scope.peoples = data.data;
	    })
	}
       
    $scope.init = function () {
		$scope.getReviewersForSearch();
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";

        AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
		AppDataService.loadCategoryTypeList(null, null, function (result) {
           $scope.categoryTypeList = result.data;
        }, function (result) { });
        $scope.StatusList = AppDataService.loadApprovedList;

        
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
    
    $scope.init();
}]);

swdApp.controller('Open_Review', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
	
	var reviewers_id = 	window.cfg.Id;
    $scope.jobs = [];
    var jobs_IDs = [];
    
    
    $scope.newJob = function($event){
        // prevent submission
        $count_jobs = $scope.jobs.length;
        $event.preventDefault();
        $scope.jobs.push({});
        $scope.jobs[$count_jobs] = {
			sector_id: 1
		}
        $count_jobs = $count_jobs + 1;
    }
    $scope.removeJob = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.jobs.pop({});
    }
    
    $scope.usernames = [];
    var usernames_IDs = [];
    $scope.newUsername = function($event){
        // prevent submission
        $count_usernames = $scope.usernames.length;
        $event.preventDefault();
        $scope.usernames.push({});
        $scope.usernames[$count_usernames] = {
			sector_id: 1
		}
        $count_usernames = $count_usernames + 1;
    }
    $scope.removeUsername = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.usernames.pop({});
    }
                
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ifi_shipping_request/get_shipping_requests.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
                    $scope.reviewer = result.data[0];
                    $scope.productList = result.data_product;
                    //$scope.shippingRequest = result.data_shipping_requests;
                    $scope.the_user_is = result.data_product[0].username;
                    console.log("TEST is " + result.data_product[0].the_user_is)
					console.log("TEST is " + result.test)

                    //$scope.jobsList = result.data_jobs;
                    $scope.jobs = result.data_jobs;
                    $scope.usernames = result.data_usernames;
  
                    // SET BOOLEAN VALUES FOR EDIT REVIEWER
					// REVIEW AGREEEMENT
					if ($scope.reviewer.ra_sent == true) {
						$scope.reviewer.ra_sent = 1;    
					} else {
						$scope.reviewer.ra_sent = 0;
        			}
        			if ($scope.reviewer.ra_signed == true) {
						$scope.reviewer.ra_signed = 1;    
					} else {
						$scope.reviewer.ra_signed = 0;
        			}
					// END REVIEW AGREEMENT
					
					// NDA
					if ($scope.reviewer.nda_sent == true) {
						$scope.reviewer.nda_sent = 1;    
					} else {
						$scope.reviewer.nda_sent = 0;
        			}
        			if ($scope.reviewer.nda_signed == true) {
						$scope.reviewer.nda_signed = 1;    
					} else {
						$scope.reviewer.nda_signed = 0;
        			}
					// END NDA			
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
    
    $scope.Unapprove = function () {
	    if ($scope.reviewer.unapprove_notes === undefined || $scope.reviewer.unapprove_notes == null || $scope.reviewer.unapprove_notes.length <= 0 )  {
			toastr.error("Please explain explain why.")
		} else {
			console.log("Requestor is " + ($scope.reviewer.request_made_by))
			var api_url = window.cfg.apiUrl + 'ifi_shipping_request/unapproved_request.php?id=' + window.cfg.Id + "&request_made_by=" + $scope.reviewer.request_made_by;
			myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.reviewer),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    		})
			.success(function (result) {                          
				if (result.code == "success") {
					toastr.success("Successfully Unapproved")
					$.unblockUI();
        		} else {
					$.unblockUI();
					console.log("Test is " + $result.test)
					toastr.error(result.message == undefined ? result.data : result.message);
				}
			}).error(	function (data) {
				console.log("Code is " + result.code)
				toastr.error("Insert shp to error.");
			});
			
		} // END ELSE STATEMENT
	}
	    
    $scope.Approve = function (who_gets_it) {
		//toastr.error(" who gets it is " + the_person)
		if (who_gets_it == '0' )  {
			toastr.error("Please pick who gets it.")
		} else {
			var api_url = window.cfg.apiUrl + 'ifi_shipping_request/approved_request.php?id=' + window.cfg.Id + "&who_gets_it=" + who_gets_it + "&request_made_by=" + $scope.reviewer.request_made_by;
			myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.reviewer),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    		})
			.success(function (result) {                          
				if (result.code == "success") {
					toastr.success("Successfully Approved")
					$.unblockUI();
        		} else {
					$.unblockUI();
					console.log("Test is " + result.test)
					toastr.error(result.message == undefined ? result.data : result.message);
				}
			}).error(	function (data) {
				console.log("Code is " + result.code)
				toastr.error("Insert shp to error.");
			});
			
		} // END ELSE STATEMENT
	}
    
    $scope.init=function()
    {
		
		$scope.LoadData();
        AppDataService.load_tbl_titleList(null, null, function (result) {
           $scope.titleList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_tagList(null, null, function (result) {
           $scope.tagList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_iclubList(null, null, function (result) {
           $scope.iclubList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_statusList(null, null, function (result) {
           $scope.statusList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_sectorList(null, null, function (result) {
           $scope.sectorList = result.data;
        }, function (result) { });
         AppDataService.load_tbl_countryList(null, null, function (result) {
           $scope.countryList = result.data;
        }, function (result) { });
        
        AppDataService.load_tbl_reasonList(null, null, function (result) {
           $scope.reasonList = result.data;
        }, function (result) { });
        AppDataService.load_tbl_unitTypeList(null, null, function (result) {
           $scope.unitTypeList = result.data;
        }, function (result) { });
        
		$scope.whoGetsItList = AppDataService.loadWhoGetsShippingRequest
		$scope.who_gets_it = '0';
        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ImportFile', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }

    
    $scope.UploadData = function () {
        var api_url = window.cfg.apiUrl + 'ifi_shipping_request/import.php';
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
	               //console.log(data);
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   console.log("Testing1 is " + data.testing1)
					   //console.log("Testing2 is " + data.testing2)
					   //console.log("Testing3 is " + data.testing3)
					   //console.log("Testing4 is " + data.testing4)
					   //console.log("Testing5 is " + data.testing5)

					    console.log("Message is " + data.message)
                   }
                   else {
	                   console.log("Code is " + data.code)
	                   console.log("Stuck here")
	                   console.log("Testing1 is " + data.testing1)
	                   console.log("Testing2 is " + data.testing2)
	                   //console.log("Testing1 in else" + data.testing1)
                       toastr.error(data.message);
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

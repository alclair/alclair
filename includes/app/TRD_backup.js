swdApp.controller('Contact_Page', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.contact = {
 	ifi_tag_id: 1,
 	iclub_id: 1,
 	status_id: 1,
    country_id: 0,
    region_id: 0,
	new_address: 0,
};

   $scope.create_new= 0;
    $scope.Create_New = function () { 
	 	$scope.create_new = 1;   
	 	console.log("CREATE NEW " + $scope.create_new)
	 }

	$scope.LoadAddress2 = function (ship_to_id) {
        //myblockui();
        $scope.contact = [];
        console.log("ship to id is " + ship_to_id)
        
        var api_url = window.cfg.apiUrl + "z_contacts/grab_contact_info.php?ship_to_id=" + ship_to_id;
        //alert(api_url);
        $http.get(api_url)
		.success(function (result) {
			$scope.contact = result.data[0];
			$scope.jobs = result.jobs;
			$scope.usernames = result.usernames;
			console.log("Search Text is " + result.test)
			if(result.data[0]) {
				//console.log("RESULT is " + JSON.stringify(result.data[0]))
				
				if($scope.contact.customertype_id == 4) {
					//console.log("contact nda is " + $scope.contact.nda)
					if ($scope.contact.nda == true) { 
						$scope.contact.nda = 1; 
					} else { 
						$scope.contact.nda = 0; 
					} 
					if ($scope.contact.review_agreement == true) {
		    			$scope.contact.review_agreement = 1;    
	        		} else {
						$scope.contact.review_agreement = 0;
	        		}
	        	}

			} else {
				console.log("INSIDE ELSE");
			}
			if($scope.shipto == '') {
		       $scope.contact.firstname = "";  
		       $scope.contact.lastname = "";    
		       $scope.contact.customertype_id = 0;
		       $scope.contact.store_as_business = 0;  
		       $scope.contact.address_1 = "";  
		       $scope.contact.address_2 = ""; 
		       $scope.contact.city = "";  
		       $scope.contact.state = "";  
		       $scope.contact.zipcode = "";  	    
		       $scope.contact.country_id = 1;  
		       $scope.contact.region_id = 1;      		       
		       $scope.contact.email = "";  
		       $scope.contact.phone = "";  
		       $scope.contact.review_agreement = 0;  
		       $scope.contact.ifi_tag_id = 0;  
		       $scope.contact.club_id = 0;  
		       $scope.contact.status_id = 0;  
		       $scope.contact.nda = 0;
		       $scope.contact.notes = "";  		         
		         
		       	$scope.SearchText = [];    
			}
           // $.unblockUI();
		}).error(function (result) {
			toastr.error("Get load address error.");
		});
    };  

   $scope.ShipTos = [];
	$scope.getShipTosForSearch2= function () {
		$scope.ShipTos = 0;
		var api_url = window.cfg.apiUrl + "z_contacts/search_contacts.php";
			$http.get(api_url).success(function (data) {
			console.log("HERE2222")
			$scope.ShipTos = data.data;
			if($scope.SearchText) {
				console.log("Search Text is " + $scope.SearchText)
				//$scope.LoadAddress2($scope.SearchText);		
			}
		})
	}
     
     $scope.NewAddress = function(){
	  	$scope.contact.address_1 = [];	    
	  	$scope.contact.address_2 = [];	    
	  	$scope.contact.city = [];	    
	  	$scope.contact.state = [];	    
	  	$scope.contact.zipcode = [];	    
	  	$scope.contact.new_address = 1;
    }      
    
    $scope.DeleteAddress = function(){
	    console.log("Search is " + $scope.SearchText)
	  	var api_url = window.cfg.apiUrl + "z_contacts/delete_address.php?contact_address_link_id2=" + $scope.SearchText;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.contact),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
       		})
			.success(function (result) {        
				$.unblockUI();                
				console.log(result.test)
				if (result.code == "success") {	
					alert("ADDRESS DELETED");
					toastr.success("Address deleted."); 
					setTimeout(function(){
						location.reload("");	
						//$scope.LoadAddress2($scope.SearchText)
					}, 500); 
          		} else {
		 			//toastr.error(result.message == undefined ? result.data : result.message);
		 		}
			 }).error(	function (data) {
				console.log("Code is " + result.code)
	 			toastr.error("Insert reviwer error.");
		 	});
	  	
    }      
    
    $scope.Submit = function(){
	    console.log("IS NEW ADDRESS " + $scope.contact.new_address)
	    //return;
	    
	    if($scope.contact.country_id == undefined) {
			toastr.error("Please choose a country.");
			
			return;
 		}
	    if($scope.contact.region_id == undefined) {
			toastr.error("Please choose a region.");
			return;
 		} 
	    
	    if($scope.SearchText > 1  ) {
	    	console.log("Search Text is " + $scope.SearchText)
			var new_contact = 0; // NO, NOT A NEW CONTACT
			var contact_address_link_id = $scope.SearchText;
		} else {
			var new_contact = 1; // YES, NEW CONTACT
			var contact_address_link_id = 0;
			$scope.contact.new_address = 0;
			//console.log("Search Text is BLANK " + $scope.SearchText + " and CAL ID is " + contact_address_link_id + " new contact is " + new_contact + " and old is " + $scope.contact.new_address)
			//console.log("First is " + $scope.contact.firstname)
		}
	   
	    /*
	    if($scope.SearchText == '' || $scope.SearchText == undefined || $scope.SearchText > 1) {
		    console.log("Search Text is BLANK " + $scope.SearchText)
			var new_contact = 1; // YES, NEW CONTACT
			var contact_address_link_id = 0;
		} else { // SEARCH TEXT EXISTS MEANING NOT A NEW CONTACT
			console.log("Search Text Else is " + $scope.SearchText)
			var new_contact = 0; // NO, NOT A NEW CONTACT
			var contact_address_link_id = $scope.SearchText;
		}
		*/
		//console.log("contact address link id is " + contact_address_link_id )
		 //return;
		 
		var api_url = window.cfg.apiUrl + "z_contacts/create_contact.php?contact_address_link_id2=" + contact_address_link_id + "&new_contact=" + new_contact + "&old_contact_new_address=" + $scope.contact.new_address;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.contact),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
       		})
			.success(function (result) {        
				$.unblockUI();                
				console.log("TEST IS " + result.test)
				//return;
				if (result.code == "success") {
					//$scope.reviewers_id = result.reviewers_id;)
					console.log("BEFORE JOB FUNCTION")
					contact_address_link_id = result.contact_address_link_id;
					$scope.saveJob(contact_address_link_id, $scope.jobs.length, "from JS")
					$scope.saveUsername(contact_address_link_id, $scope.usernames.length, "from JS")
					
					setTimeout(function(){
						alert("CONTACT ENTERED");
						location.reload("");	
						//$scope.LoadAddress2($scope.SearchText)
					}, 500); 
          		} else {
		 			//toastr.error(result.message == undefined ? result.data : result.message);
		 		}
			 }).error(	function (data) {
				console.log("Code is " + result.code)
	 			toastr.error("Insert reviwer error.");
		 	});
    }
      
    $scope.saveJob = function(contact_address_link_id, jobs_length, where_from) {
	    if($scope.SearchText == '' || $scope.SearchText == undefined) {
		    //console.log("NOT A REVIEWER SO NOT SAVING!");
		} else {
			//console.log("Job # is " + )
		}
		    
		//for (i = 0; i < $scope.jobs.length; i++) {
		if(where_from == 'from PHP') { // GET HERE FROM JavaScript
			console.log("IT IS FROM PHP & " + contact_address_link_id)
			key = jobs_length;
			i = jobs_length;
			var api_url = window.cfg.apiUrl + 'z_contacts/jobs.php?key=' + key + '&contact_address_link_id3=' + contact_address_link_id;
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.jobs[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {    
					console.log(result.test)                      
					if (result.code == "success") {
						$.unblockUI();
						setTimeout(function(){
							//location.reload();
							$scope.LoadAddress2($scope.SearchText)	
						}, 1000); 
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
		} else { // GET HERE FROM z_contacts/create_contact.php
			console.log("LENGTH IS " + $scope.jobs.length + " ID is " + contact_address_link_id)
			for (i = 0; i < jobs_length; i++) {
				//console.log("Length is  " + $scope.jobs.length)
				//console.log("ConTACT add link id is " + contact_address_link_id)
				console.log("IN FOR LOOP and ID is " + contact_address_link_id)
				//var api_url = window.cfg.apiUrl + 'ifi_tbl/add_jobs.php?reviewers_id=' + reviewers_id;
				var api_url = window.cfg.apiUrl + 'z_contacts/jobs.php?key=' + i + '&contact_address_link_id3=' + contact_address_link_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.jobs[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {       
					console.log("Test is " + result.test)                   
					if (result.code == "success") {
						$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
		}
	}
	
	$scope.saveUsername = function(contact_address_link_id, usernames_length, where_from) {
	    if($scope.SearchText == '' || $scope.SearchText == undefined) {
		    //console.log("NOT A REVIEWER SO NOT SAVING!");
		} else {
			//console.log("Job # is " + )
		}
		    
		//for (i = 0; i < $scope.jobs.length; i++) {
		if(where_from == 'from PHP') { // GET HERE FROM z_contacts/create_contact.php
			console.log("IT IS FROM PHP & " + contact_address_link_id)
			key = usernames_length;
			i = usernames_length;
			var api_url = window.cfg.apiUrl + 'z_contacts/usernames.php?key=' + key + '&contact_address_link_id3=' + contact_address_link_id;
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.usernames[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {    
					console.log(result.test)                      
					if (result.code == "success") {
						$.unblockUI();
						setTimeout(function(){
							//location.reload();	
							$scope.LoadAddress2($scope.SearchText)
						}, 1000); 
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
		} else { // GET HERE FROM JavaScript
			console.log("IT IS FROM JS")
			for (i = 0; i < usernames_length; i++) {
				console.log("Length is  " + $scope.usernames.length)
				console.log("ConTACT add link id is " + contact_address_link_id)
				//var api_url = window.cfg.apiUrl + 'ifi_tbl/add_jobs.php?reviewers_id=' + reviewers_id;
				var api_url = window.cfg.apiUrl + 'z_contacts/usernames.php?key=' + i + '&contact_address_link_id3=' + contact_address_link_id;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.usernames[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {       
					console.log("Test is " + result.test)                   
					if (result.code == "success") {
						$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (data) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
		}
	}
	
	$scope.removeJob2 = function(contact_address_link_id, jobs_length, where_from) {
	    if($scope.SearchText == '' || $scope.SearchText == undefined) {
		    console.log("NOT A REVIEWER SO NOT SAVING!");
		} else {
			//console.log("Job # is " + )
		}
		    
		//for (i = 0; i < $scope.jobs.length; i++) {
		key = jobs_length;
		i = jobs_length;
		var api_url = window.cfg.apiUrl + 'z_contacts/jobs_remove.php?key=' + key + '&contact_address_link_id3=' + contact_address_link_id;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.jobs[i]),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			})
			.success(function (result) {    
				console.log(result.test)                      
				if (result.code == "success") {
					$.unblockUI();
					setTimeout(function(){
						//location.reload();	
						$scope.LoadAddress2($scope.SearchText)
					}, 1000); 
				} else {
					toastr.error(result.message == undefined ? result.data : result.message);
				}
			}).error(	function (data) {
					console.log("Code is " + result.code)
				toastr.error("Insert ship to error.");
			});
	}
	
	$scope.removeUsername2 = function(contact_address_link_id, usernames_length, where_from) {
	    if($scope.SearchText == '' || $scope.SearchText == undefined) {
		    console.log("NOT A REVIEWER SO NOT SAVING!");
		} else {
			//console.log("Job # is " + )
		}
		    
		//for (i = 0; i < $scope.jobs.length; i++) {
		key = usernames_length;
		i = usernames_length;
		var api_url = window.cfg.apiUrl + 'z_contacts/usernames_remove.php?key=' + key + '&contact_address_link_id3=' + contact_address_link_id;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.usernames[i]),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			})
			.success(function (result) {    
				console.log(result.test)                      
				if (result.code == "success") {
					$.unblockUI();
					setTimeout(function(){
						//location.reload();	
						$scope.LoadAddress2($scope.SearchText)
					}, 1000); 
				} else {
					toastr.error(result.message == undefined ? result.data : result.message);
				}
			}).error(	function (data) {
					console.log("Code is " + result.code)
				toastr.error("Insert ship to error.");
			});
	}
    

	$scope.Check4Reviewer = function(){
		console.log("In Check 4 Reviewer")
		var api_url = window.cfg.apiUrl + 'ifi_tbl/check_for_reviewer.php';
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.reviewer),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        		})
				.success(function (result) {                          
					if (result.code == "success") {		
						if(result.reviewer_exists > 0) {	
							alert("The contact name already exists in the system!");
							$.unblockUI();
							return;
						} else {
							$.unblockUI();
							return;
						}
           			} else {
			 			$.unblockUI();
			 			toastr.error(result.message == undefined ? result.data : result.message);
			 		}
			 	}).error(	function (data) {
					console.log("Code is " + result.code)
		 			toastr.error("Insert contact error.");
		 		});
    }
        
    $scope.jobs = [];
    $count_jobs = 0;
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
    $count_usernames = 0;
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
           
    $scope.init=function()
    {
	    
	    $scope.getShipTosForSearch2();
	    // TYPE OF CUSTOMER
	    AppDataService.loadSenderTypesList(null, null, function (result) {
           $scope.senderTypesList = result.data;
        }, function (result) { });

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
        AppDataService.load_tbl_regionList(null, null, function (result) {
           $scope.regionList = result.data;
        }, function (result) { });


        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);
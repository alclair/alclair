swdApp.controller('edit_Traveler', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';

	$scope.traveler = {
	 	versa: 0,
	 	dual: 0,
	 	dual_xb: 0,
	 	reference: 0,
	 	tour: 0,
	 	rsm: 0,
	 	cmvk: 0,
	 	spire: 0,
	 	studio4: 0,
	 	other: 0,
	 	additional_items: 0,
	 	consult_highrise: 0,
	 	international: 0,
	 	universals: 0,
	 	hearing_protection: 0,
	 	musicians_plugs: 0,
	 	musicians_plugs_9db: 0,
	 	musicians_plugs_15db: 0,
	 	musicians_plugs_25db: 0,
	 	pickup: 0,
	 	override: 1,
	 	//nashville_order: 0,
	};
    $scope.selectedFiles = [];
    
    $scope.EditNotes = function (key, ID) {			
		var api_url = window.cfg.apiUrl + 'notes_for_traveler/get_note_from_order_for_traveler.php?id=' + ID;
		console.log("START IS " + window.cfg.Id)
        $http.get(api_url)
            .success(function (result) {
	            $scope.editNotes = result.data;
	            console.log("THE ID IS " + $scope.editNotes.the_id)
	            $('#modalEditNotes').modal("show");
            }).error(function (result) {
                $.unblockUI();
				toastr.error(result.message == undefined ? result.data : result.message);
            });   
	}
	$scope.SaveNotes = function (id_to_edit) {		
		var api_url = window.cfg.apiUrl + 'notes_for_traveler/update_note_from_order_for_traveler.php?id_to_edit=' + id_to_edit; 
		console.log("The ID to EDIT is " + id_to_edit)
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.editNotes),
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

    
     $scope.open = function ($event) {
        $scope.opened = true;
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
   	//$scope.SearchStartDate=window.cfg.CurrentDay;
   	
   	$scope.openImpressions = function ($event) {        
        $scope.openedImpressions = true;
    };
    $scope.openReceived = function ($event) {        
        $scope.openedReceived = true;
    };
    $scope.openShip = function ($event) {        
        $scope.openedShip = true;
    };
 
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.CableTypeList = AppDataService.CableTypeList;
    $scope.CustomerTypeList = AppDataService.CustomerTypeList;
    $scope.ArtworkTypeList = AppDataService.ArtworkTypeList;
    $scope.HEARING_PROTECTION_COLORS = AppDataService.HEARING_PROTECTION_COLORS;
        
    $scope.Print_Traveler = function (id_to_print) {		
		if (!$scope.traveler.impression_color_id ) {
			$scope.traveler.impression_color_id = 0;
		}
		
		if ($scope.traveler.impression_color_id == null) {
		    $scope.traveler.impression_color_id  = 0;
	    }
	    
	    if ($scope.traveler.hearing_protection == '0' ) {
			$scope.traveler.hearing_protection_color = '0';
		}
		
		// WHEN THE PAGE LOADS THE ORDER STATUS ID IS SAVED AS order_status_id 
		if (order_status_id == 1 && $scope.traveler.order_status_id == 1) {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler_backup.php?id=' + window.cfg.id;
		} else {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler.php?id=' + window.cfg.id;
		}
		
		if (!isEmpty($scope.traveler.date))
        {
            $scope.traveler.date = moment($scope.traveler.date).format("MM/DD/YYYY");
        }
        if (!isEmpty($scope.traveler.received_date))
        {
            $scope.traveler.received_date = moment($scope.traveler.received_date).format("MM/DD/YYYY");
        }
         if (!isEmpty($scope.traveler.estimated_ship_date))
        {
            $scope.traveler.estimated_ship_date = moment($scope.traveler.estimated_ship_date).format("MM/DD/YYYY");
        }
        
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.traveler),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 if($scope.traveler.email) {
					 setTimeout(function(){
					 	 var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
					 	 //return;
					 	 Email = $scope.traveler.email;
					 	 status_id_is = Number($scope.traveler.order_status_id);
					 	 if(status_id_is == 1) {
						 	 status_id_is = 2;
					 	 } else if(status_id_is == 99) {
						 	 status_id_is = 1;
					 	 } else if (status_id_is == 2) {
						 	 status_id_is = 3;
					 	 } else if (status_id_is == 3) {
						 	 status_id_is = 5;
					 	 } else if (status_id_is == 4) {
						 	 status_id_is = 6;
					 	 } else if (status_id_is == 5) {
						 	 status_id_is = 7;
					 	 } else if (status_id_is == 6) {
						 	 status_id_is = 8;
					 	 } else if (status_id_is == 7) {
						 	 status_id_is = 9;
					 	 } else if (status_id_is == 8) {
						 	 status_id_is = 10;
					 	 } else if (status_id_is == 9) {
						 	 status_id_is = 11;
					 	 } else if (status_id_is == 10) {
						 	 status_id_is = 12;
					 	 } else if (status_id_is == 11) {
						 	 status_id_is = 14;
					 	 }  else if (status_id_is == 12) {
						 	 status_id_is = 17;
					 	 } else if (status_id_is == 13) {
						 	 status_id_is = 15;
					 	 } else if (status_id_is == 14) {
						 	 status_id_is = 16;
					 	 } else if (status_id_is == 15) {
						 	 status_id_is = 4;
					 	 } 	else if (status_id_is == 16) {
						 	 status_id_is = 13;
					 	 } 				 
					 	 console.log("Status ID is now  " +  status_id_is)
					 	 Current_status = $scope.OrderStatusList[status_id_is-1].status_of_order;
						 Estimated_ship_date = moment($scope.traveler.estimated_ship_date).format("MM/DD/YYYY");
						 json_text= '{ "contact": { "email": "' +Email+'", "fieldValues":[{"field": 49, "value": "'+Current_status+'"}, {"field": 50, "value": "'+Estimated_ship_date+'"}] }}';
						$http({
							 method: 'POST',
							 url: 'https://otis.alclr.co:8080/https://alclair.api-us1.com/api/3/contact/sync',
							data: json_text,
						 	headers: {
								'Content-Type': 'application/json',					 	
								'Api-Token': key_is,
							 },
						}).then(function successCallback(response) {
							 console.log("First name is " + JSON.stringify(response.data.contact.firstName))
							 console.log("Last name is " + JSON.stringify(response.data.contact.lastName))
							 console.log("ID is " + JSON.stringify(response.data.contact.id))
							 json = response.data.data;
						}, function errorCallback(response) {
							console.log("ERROR HERE")
						});	 
						
						$scope.PDF(id_to_print);
						$.unblockUI();
						toastr.success(result.message)
						//console.log("Email is " + $scope.traveler.email)
						//return;
						 setTimeout(function(){ 
							 location.reload();				 	
						}, 2000);    
						
					}, 500);     
				} else {
					// EMAIL DOES NOT EXIST AND RELOAD PAGE ONLY
					$scope.PDF(id_to_print);
					$.unblockUI();
					toastr.success(result.message)
					setTimeout(function(){ 
						location.reload();				 	
					}, 2000);   
				}                
             }
             else {
                 $.unblockUI();
                 console.log("Error Here")
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save traveler error.");

         });
    };    
    
    $scope.showOnManufacturingScreen = function () {
	    myblockui();
		var api_url = window.cfg.apiUrl + 'alclair/move_to_manufacturing_screen.php?id=' + window.cfg.Id;
		console.log("START IS " + window.cfg.Id)
        $http.get(api_url)
            .success(function (result) {
	            if(result.code == "success") {
	            	console.log("The ID is " + result.test)
					//console.log("Message is " + result.message)
					toastr.success("Order moved to Manufacturing Screen.");    
					$.unblockUI();
					setTimeout(function(){
						location.reload();				 	
					}, 1000);
					//window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+result.ID_is;
                 } 
                 else {
				 		$.unblockUI();
				 		toastr.error(result.message == undefined ? result.data : result.message);    
                 }
            }).error(function (result) {
                $.unblockUI();
				toastr.error(result.message == undefined ? result.data : result.message);
            });
    }
    $scope.removeFromManufacturingScreen = function () {
	    myblockui();
		var api_url = window.cfg.apiUrl + 'alclair/remove_from_manufacturing_screen.php?id=' + window.cfg.Id;
		console.log("START IS " + window.cfg.Id)
        $http.get(api_url)
            .success(function (result) {
	            if(result.code == "success") {
	            	console.log("The ID is " + result.test)
					//console.log("Message is " + result.message)
					toastr.success("Order removed from the Manufacturing Screen.");    
					$.unblockUI();
					setTimeout(function(){
						location.reload();				 	
					 }, 1000);
					//window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+result.ID_is;
                 } 
                 else {
				 		$.unblockUI();
				 		toastr.error(result.message == undefined ? result.data : result.message);    
                 }
            }).error(function (result) {
                $.unblockUI();
				toastr.error(result.message == undefined ? result.data : result.message);
            });
    }
    
   
	// TWO LINES BELOW ARE HOW I MADE TEXT LINK TO A NEW PAGE - REQUEST WAS MADE TO MAKE TEXT A BUTTON - THE BUTTON REQUIRE SOME CODE TO BE ALTERED
	 //document.getElementById ("qcform").addEventListener ("click", gotoQC_Form, false);
	//function gotoQC_Form() {
	$scope.gotoQC_Form = function () {	
	    //var $order_id = 9729;
        myblockui();
        //myblockui();
		//  THE IF PORTION OF THE STATEMENT IS THE SIMPLE AND FAST WAY TO GET TO THE QC FORM
		//  THE FIRST PASS OF LINKING THE ORDER TO THE QC FORM DID  NOT INCLUDE A "ID OF QC FORM" ENTRY IN THE TABLE
		//  THE ELSE PORTION OF THE STATEMENT USES A FILE TO LOOK FOR THE QC FORM IF IT MAY EXIST
        if($scope.traveler.id_of_qc_form != null) { 
	        $.unblockUI();
	        window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+$scope.traveler.id_of_qc_form;
        } else {
			var api_url = window.cfg.apiUrl + "alclair/get_qc_form_id.php?customer_name=" + $scope.traveler.designed_for + "&order_id=" + $scope.traveler.order_id + "&id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            if(result.code == "success") {
	            	console.log("The ID is " + result.ID_is)
					console.log("Message is " + result.message)
					$.unblockUI();
					window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+result.ID_is;
                 } 
                 else {
	                 $.unblockUI();
	            	toastr.error(result.message == undefined ? result.data : result.message);    
                 }
            }).error(function (result) {
                $.unblockUI();
				toastr.error(result.message == undefined ? result.data : result.message);
            });
        } // CLOSE ELSE STATEMENT
    };
    
    $scope.PDF = function (id) {
	    //var $order_id = 9729;
        
        //myblockui();
        console.log("Order num is " +  id);
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF.php?ID=" + id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Data is " + result.data);
            console.log("TESTING IS " + result.test);
            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.CreateRMA = function () {
	    console.log("The ID is " + window.cfg.Id)
	    console.log("Designed for " + $scope.traveler.designed_for)
	    console.log("monitor id  " + $scope.traveler.monitor_id)
	    $scope.traveler.received_date = window.cfg.CurrentDay;
		//$scope.traveler.estimated_ship_date = window.cfg.CurrentDay_plus_2weeks;
		$scope.traveler.estimated_ship_date = window.cfg.CurrentDay_plus_3weeks;
		$scope.traveler.import_orders_id = window.cfg.Id;

	    var api_url = window.cfg.apiUrl + "alclair/get_next_id_from _from_repair_form.php";

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            //console.log(result.data);
			$scope.traveler.rma_number = result.next_id;
			
			console.log("NEXT RMA NUMBER IS " +$scope.traveler.rma_number)
			
			var api_url = window.cfg.apiUrl + 'alclair/rma_add_generate_from_order.php';
			console.log(api_url+"?"+$scope.traveler);
			myblockui();
			$http({
           		method: 'POST',
		   		url: api_url,
		   		data: $.param($scope.traveler),
		   		headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		   	})
		   	.success(function (result) {
            	 console.log(result);
			 	console.log("message " + result.message);
			 	console.log("Test is " + result.test)
			 	console.log("Original ship date of order is " + result.original_ship_date_of_order)
           //return;
			 	if (result.code == "success") {
                		$.unblockUI();
						//alert(result.data.id);
						//if (result.data.id !=undefined)
					{
                    	 	//$scope.repair_form.id = result.data.id;
						//toastr.success("Form saved successfully.");
						$scope.original_ship_date_of_order = result.original_ship_date_of_order;
						 window.location.href = window.cfg.rootUrl + "/alclair/edit_repair_form/" +  result.test;

                     //$scope.UploadFile();
                 	}
				 	//else
				 	{
                  	   //console.log("ID is " + reesult.data.id)
                 	}
				 	//redirect
             	}
			 	else {
	             	console.log("CODE IS " + result.code)
				 	$.unblockUI();
				 	toastr.error(result.message == undefined ? result.data : result.message);
             	}
         	}).error(function (data) {
             	toastr.error("Insert repair form error.");
         	});
			
			
        }).error(function () {
            $.unblockUI();
            toastr.error("Error!");
        });
    }

        
    $scope.Save = function () {
	   
	    if ($scope.traveler.impression_color_id == null) {
		    $scope.traveler.impression_color_id  = 0;
	    }
	    
	    if ($scope.traveler.hearing_protection == '0' ) {
			$scope.traveler.hearing_protection_color = '0';
		}
		
		// WHEN THE PAGE LOADS THE ORDER STATUS ID IS SAVED AS order_status_id 
		if (order_status_id == 1 && $scope.traveler.order_status_id == 1) {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler_backup.php?id=' + window.cfg.id;
			console.log("INSIDE THE IF " +	 $scope.traveler.international)
		} else {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler.php?id=' + window.cfg.id;
			//var api_url = window.cfg.apiUrl + 'alclair/update_traveler.php?id=' + 11345;
			console.log("INSIDE THE ELSE")
		}
		//return;
	/*		
		if ($scope.traveler.override == 1) {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler_backup.php?id=' + window.cfg.id;
		} else {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler.php?id=' + window.cfg.id;
		}
*/
        if (!isEmpty($scope.traveler.date))
        {
            $scope.traveler.date = moment($scope.traveler.date).format("MM/DD/YYYY");
        } else {
	        $scope.traveler.date = null;
        }
        if (!isEmpty($scope.traveler.received_date))
        {
            $scope.traveler.received_date = moment($scope.traveler.received_date).format("MM/DD/YYYY");
        } else {
			$scope.traveler.received_date = null;
        }
        if (!isEmpty($scope.traveler.estimated_ship_date))
        {
            $scope.traveler.estimated_ship_date = moment($scope.traveler.estimated_ship_date).format("MM/DD/YYYY");
        }
        
        //$scope.traveler.id = window.cfg.id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.traveler),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         console.log(result.test2)
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success("Updates saved!")
                 if($scope.traveler.email) {
					 setTimeout(function(){
					 	 var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
					 	 //return;
					 	 Email = $scope.traveler.email;
					 	 status_id_is = Number($scope.traveler.order_status_id);
						 console.log("Status ID2 IS " +  status_id_is)

					 	 if(status_id_is == 1) {
						 	 status_id_is = 2;
					 	 } else if(status_id_is == 99) {
						 	 status_id_is = 1;
					 	 } else if (status_id_is == 2) {
						 	 status_id_is = 3;
					 	 } else if (status_id_is == 3) {
						 	 status_id_is = 5;
					 	 } else if (status_id_is == 4) {
						 	 status_id_is = 6;
					 	 } else if (status_id_is == 5) {
						 	 status_id_is = 7;
					 	 } else if (status_id_is == 6) {
						 	 status_id_is = 8;
					 	 } else if (status_id_is == 7) {
						 	 status_id_is = 9;
					 	 } else if (status_id_is == 8) {
						 	 status_id_is = 10;
					 	 } else if (status_id_is == 9) {
						 	 status_id_is = 11;
					 	 } else if (status_id_is == 10) {
						 	 status_id_is = 12;
					 	 } else if (status_id_is == 11) {
						 	 status_id_is = 14;
					 	 }  else if (status_id_is == 12) {
						 	 status_id_is = 17;
					 	 } else if (status_id_is == 13) {
						 	 status_id_is = 15;
					 	 } else if (status_id_is == 14) {
						 	 status_id_is = 16;
					 	 } else if (status_id_is == 15) {
						 	 status_id_is = 4;
					 	 } 	else if (status_id_is == 16) {
						 	 status_id_is = 13;
					 	 } 				 	 
					 	 console.log("Status ID is now  " +  status_id_is)
					 	 Current_status = $scope.OrderStatusList[status_id_is-1].status_of_order;
					 	 console.log("STATUS IS " + Current_status);
					 	 //return;
						 Estimated_ship_date = moment($scope.traveler.estimated_ship_date).format("MM/DD/YYYY");
						 json_text= '{ "contact": { "email": "' +Email+'", "fieldValues":[{"field": 49, "value": "'+Current_status+'"}, {"field": 50, "value": "'+Estimated_ship_date+'"}] }}';
						$http({
							 method: 'POST',
							 url: 'https://otis.alclr.co:8080/https://alclair.api-us1.com/api/3/contact/sync',
							data: json_text,
						 	headers: {
								'Content-Type': 'application/json',					 	
								'Api-Token': key_is,
							 },
						}).then(function successCallback(response) {
							 console.log("First name is " + JSON.stringify(response.data.contact.firstName))
							 console.log("Last name is " + JSON.stringify(response.data.contact.lastName))
							 console.log("ID is " + JSON.stringify(response.data.contact.id))
							 json = response.data.data;
						}, function errorCallback(response) {
							console.log("ERROR HERE")
						});	 
						 setTimeout(function(){ 
							 location.reload();				 	
						}, 1000);    
					}, 500);     
				} else {
					// EMAIL DOES NOT EXIST AND RELOAD PAGE ONLY
					setTimeout(function(){ 
						location.reload();				 	
					}, 1000);   
					console.log("Email does not exist " + $scope.qrcode.email)	  
				}            
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save traveler error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        console.log("HERE WE WANT " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	           $scope.logList = result.data2;
	           $scope.ChangelogList = result.data3;
	           
	           console.log("THE USER IS " + result.the_user_is)
	           /*
	           if(result.data[0].manufacturing_screen == true) {
			   		$scope.manufacturing_screen = 1; 
	           } else {
		           $scope.manufacturing_screen = 0;
	           }
	           console.log("THE SCREEN IS  " + $scope.traveler.manufacturing_screen)
	           */
	           $scope.the_user_is = result.the_user_is;
	            //console.log("Log is " + JSON.stringify(result.data2))
	            //console.log("The data is " + JSON.stringify(result.data[0]))
	            //console.log("Test is " + result.test)
	            //console.log("Test 2 is " + result.test2)
                if (result.data.length > 0) {
                    $scope.traveler = result.data[0];   
                    $scope.traveler.override = 1;      
                    console.log("Length is "+($scope.traveler.designed_for))
                    if(($scope.traveler.designed_for == null) && $scope.traveler.musicians_plugs == true) {
	                    $scope.traveler.designed_for = $scope.traveler.shipping_name;
                    }
                    if($scope.traveler.left_custom_art != null && $scope.traveler.left_alclair_logo == null) {
	                    $scope.traveler.left_alclair_logo = "Custom"
                    }
                    if($scope.traveler.right_custom_art != null && $scope.traveler.right_alclair_logo == null) {
	                    $scope.traveler.right_alclair_logo = "Custom"
                    }
                    if(!$scope.traveler.artwork) {
	                    $scope.traveler.artwork = 'None';
                    }
                    //console.log("custom art left is " + $scope.traveler.left_custom_art)
                    order_status_id = $scope.traveler.order_status_id;
                    console.log("Order Status ID is " + order_status_id)
					/*
					if($scope.traveler.artwork == "Yes") {
	                    $scope.traveler.artwork = 'Yes'; // Custom
					} else if($scope.traveler.left_alclair_logo != null && $scope.traveler.left_alclair_logo.length > 1) {
			            $scope.traveler.artwork = 'Yes'; // Custom
		           } else if($scope.traveler.right_alclair_logo != null && $scope.traveler.right_alclair_logo.length > 1) {
			            $scope.traveler.artwork = 'Yes'; // Custom
		           } else if($scope.traveler.left_custom_art != null) {
				   		if( $scope.traveler.left_custom_art.length > 1 ) {
							$scope.traveler.artwork = 'Yes'; // Custom
							if($scope.traveler.left_alclair_logo == null || $scope.traveler.left_alclair_logo.length < 2) {
								$scope.traveler.left_alclair_logo = 'Custom';
							}
							if($scope.traveler.right_alclair_logo == null || $scope.traveler.right_alclair_logo.length < 2) {
								$scope.traveler.right_alclair_logo = 'Custom';
							}
						}
		            } else if($scope.traveler.right_custom_art != null) { 
			            if(  $scope.traveler.right_custom_art > 1 ) {
				            if($scope.traveler.right_alclair_logo == null || $scope.traveler.right_alclair_logo.length < 2) {
								$scope.traveler.right_alclair_logo = 'Custom';
							}
						}
		            } else {
			            $scope.traveler.artwork = 'None'; // Not Custom
		            }
					*/
									
					
					
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
					if($scope.traveler.artwork == "Yes") {
	                    $scope.traveler.artwork = 'Yes'; // Custom
					} else if($scope.traveler.left_alclair_logo != null && $scope.traveler.left_alclair_logo.length > 4) {
			            $scope.traveler.artwork = 'Yes'; // Custom
		           } else if($scope.traveler.right_alclair_logo != null && $scope.traveler.right_alclair_logo.length > 4) {
			            $scope.traveler.artwork = 'Yes'; // Custom
		           } else if( ($scope.traveler.left_custom_art != null && $scope.traveler.left_custom_art.length > 4) || ($scope.traveler.right_custom_art != null && $scope.traveler.right_custom_art.length > 4) ) {
				   		//if( $scope.traveler.left_custom_art != null ) { //|| $scope.traveler.left_custom_art.length > 1 ) {
					   	//if( $scope.traveler.left_custom_art.length > 2 && $scope.traveler.left_custom_art != null) {
						if( $scope.traveler.left_custom_art != null) {   	
								if( $scope.traveler.left_custom_art.length > 2) {
									$scope.traveler.artwork = 'Yes'; // Custom
									//console.log("LEFT ONLY") 
									if($scope.traveler.left_alclair_logo == null || $scope.traveler.left_alclair_logo.length < 4) {
										$scope.traveler.left_alclair_logo = 'Custom';
									}
								}
						}
						//if( $scope.traveler.right_custom_art != null) { //$scope.traveler.right_custom_art.length > 1 ) {
						//if( $scope.traveler.right_custom_art.length > 2 && $scope.traveler.right_custom_art != null ) {
						if($scope.traveler.right_custom_art != null ) {
							if( $scope.traveler.right_custom_art.length > 2) {
								$scope.traveler.artwork = 'Yes'; // Custom
								//console.log("RIGHT ONLY") 
								if($scope.traveler.right_alclair_logo == null || $scope.traveler.right_alclair_logo.length < 4) {
									$scope.traveler.right_alclair_logo = 'Custom';
								}
							}
						}
		            } 
		         
	

       		            
		            // GREATER OR EQUAL TO 10 IS STUDIO3, STUDIO4, REV X & ELECTRO
		            // THIS IF STATEMENT ALTERES THE DROPDOWN FOR THE TYPE/COLOR OF CABLE
		            //if($scope.traveler.monitor_id >= 10) {
			        if($scope.traveler.monitor_id == 9 && $scope.traveler.monitor_id == 10 && $scope.traveler.monitor_id == 12) {
			            $scope.traveler.cable_color = 'Other'
		            }          

	           /*if($scope.traveler.clear_canal = "Yes") {
		           $scope.traveler.left_tip = "Clear";
		           $scope.traveler.right_tip = "Clear";
	           }*/
             
					if ($scope.traveler.additional_items == true) {
			 			$scope.traveler.additional_items = 1;    
			 		} else {
			 			$scope.traveler.additional_items = 0;
					}
					if ($scope.traveler.consult_highrise == true) {
			 			$scope.traveler.consult_highrise= 1;    
			 		} else {
			 			$scope.traveler.consult_highrise = 0;
			 		}
			 		if ($scope.traveler.international == true) {
			 			$scope.traveler.international= 1;    
			 		} else {
			 			$scope.traveler.international = 0;
			 		}
			 		if ($scope.traveler.universals == true) {
			 			$scope.traveler.universals = 1;    
			 		} else {
			 			$scope.traveler.universals = 0;
			 		}
			 		if ($scope.traveler.hearing_protection == true) {
			 			$scope.traveler.hearing_protection= 1;    
			 		} else {
			 			$scope.traveler.hearing_protection = 0;
			 		}
			 		if ($scope.traveler.musicians_plugs == true) {
			 			$scope.traveler.musicians_plugs= 1;    
			 		} else {
			 			$scope.traveler.musicians_plugs = 0;
			 		}
			 		if ($scope.traveler.musicians_plugs_9db == true) {
			 			$scope.traveler.musicians_plugs_9db= 1;    
			 		} else {
			 			$scope.traveler.musicians_plugs_9db = 0;
			 		}
			 		if ($scope.traveler.musicians_plugs_15db == true) {
			 			$scope.traveler.musicians_plugs_15db= 1;    
			 		} else {
			 			$scope.traveler.musicians_plugs_15db = 0;
			 		}
			 		if ($scope.traveler.musicians_plugs_25db == true) {
			 			$scope.traveler.musicians_plugs_25db= 1;    
			 		} else {
			 			$scope.traveler.musicians_plugs_25db = 0;
			 		}
			 		if ($scope.traveler.pickup == true) {
			 			$scope.traveler.pickup= 1;    
			 		} else {
			 			$scope.traveler.pickup = 0;
			 		}
			 		
			 		if ($scope.traveler.nashville_order == true) {
			 			$scope.traveler.nashville_order= 1;    
			 		} else {
			 			$scope.traveler.nashville_order = 0;
			 		}
			 		
			 		if ($scope.traveler.rush_process == 'Yes') {
				 		$scope.traveler.rush_process = 1;    
			 		} else {
			 			$scope.traveler.rush_process = 0;
			 		}
			 		
			 		if($scope.traveler.cable_color == 'PREMIUM STUDIO CABLE') {
				 		$scope.traveler.cable_color = 'Other';
			 		}
				//console.log("d " + $scope.traveler.additional_items )
                    //alert($scope.traveler.id);
                    //$scope.traveler.date_delivered = new Date($scope.traveler.date_delivered);
                    //$scope.traveler.date = moment($scope.traveler.date).format("MM/DD/YYYY");
                    //console.log($scope.traveler);
                }
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

swdApp.controller('TicketPage', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
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
	 	bite_block_not_used: 0,
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
			 	 $scope.PDF(id_to_print);
                 $.unblockUI();
                 toastr.success(result.message)
                 setTimeout(function(){
					// location.reload();				 	
				}, 2000);                
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
    
           
    $scope.Save = function () {
	   
	    if ($scope.traveler.impression_color_id == null) {
		    $scope.traveler.impression_color_id  = 0;
	    }
	    
	    if ($scope.traveler.hearing_protection == '0' ) {
			$scope.traveler.hearing_protection_color = '0';
		}
		
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
	         console.log(result.test)
             if (result.code == "success") {
                 $.unblockUI();
                 //console.log("TESTING IS " + result.testing + " and the other is " + result.testing2)
                 console.log("CODE IS " + result.code + " and the MESSAGE is " + result.message)
                 toastr.success("Updates saved!")
                 setTimeout(function(){
						location.reload();				 	
					}, 2000);
                
                 //window.location.href = window.cfg.rootUrl + "/alclair/orders/";
                 //redirect
             }
             else {
                 $.unblockUI();
                 //console.log( result.test)
                 //location.reload();
                 //window.location.href = window.cfg.rootUrl + "/alclair/orders/";
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save traveler error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	           $scope.logList = result.data2;
	           $scope.ChangelogList = result.data3;
	           
	           console.log("THE USER IS " + result.the_user_is)
	           
	           console.log("THE SCREEN IS  " + $scope.traveler.manufacturing_screen)
	           $scope.the_user_is = result.the_user_is;

                if (result.data.length > 0) {
                    $scope.traveler = result.data[0];   
                    $scope.traveler.override = 1;      
                    order_status_id = $scope.traveler.order_status_id;
                    console.log("Order Status ID is " + order_status_id)					
					
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

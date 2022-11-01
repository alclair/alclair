swdApp.controller('QC_Form', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
 $scope.Ready2Ship = "NO";
 $scope.greeting = 'Hola!';
 $scope.qc_form = {
 	ticket_number: '',
    //artwork_none: 0,
	qc_date: new Date,
};

document.getElementById("start").oninput = function() {myFunction()};
	
function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("start").value;
				id_of_order = x;
				
				 var api_url = window.cfg.apiUrl + "alclair/get_qc_form_id.php?id=" + id_of_order
				 $http.get(api_url)
				 	.success(function (result) {
					 	id_of_qc_form = result.ID_is;
					 	console.log("Result is " + JSON.stringify(result))
					 	console.log("Did it work " + id_of_qc_form)
					 	console.log("Testing is " + result.testing)
					 	//console.log("The id is " + id_of_qc_form)
						window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form_active_hp/" + id_of_qc_form;
					    $.unblockUI();
            		}).error(function (result) {
						toastr.error("Not working.");
            	});

		}, 500); 
	};


 console.log("Ready 2 ship is " + $scope.Ready2Ship)
 $scope.readyToShip = function () {
	 $scope.Ready2Ship = "YES";
	 }
    
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
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
    
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_alclair_fr_active_hp.php';
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
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/alclair/qc_form_active_hp/";
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/alclair/qc_form_active_hp/";
            
        }

    }
        
    $scope.SaveData = function (saveORship, uploaddocument) {
        
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 

        
        // END ARTWORK
        // END SHIPPING -> PORTS
       
		if(saveORship == 'SAVE') {
	        	var api_url = window.cfg.apiUrl + 'alclair/add_active_hp.php';
        } else {
	        var api_url = window.cfg.apiUrl + 'alclair/add_ship_active_hp.php';
        }	
		console.log(api_url+"?"+$scope.qc_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.qc_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             console.log("message " + result.message);
             if(result.message == "Something is incomplete") {
	             toastr.error("Cannot ship product.  Please check that everything has passed QC.");
	             $.unblockUI();
             }
             else {            
             	if (result.code == "success") {
	             	if(result.update === "Halt") { //"Could not determine the correct Order ID.  The order's/repair's state was not updated.") {
							toastr.error(result.return_message)
							setTimeout(function(){
								if (result.data.id !=undefined) {
									$scope.qc_form.id = result.data.id;
									$scope.UploadFile();
                 				}
				 				else {}
							}, 2000); 
						} else {
							toastr.success("Order Updated!")
							setTimeout(function(){
								if (result.data.id !=undefined) {
									$scope.qc_form.id = result.data.id;
									$scope.UploadFile();
                 				}
				 				else {}
							}, 1000); 
						}
             	}
			 	else {
                	 $.unblockUI();
                	 
					 toastr.error(result.message == undefined ? result.data : result.message);
             	}
             } // END ELSE STATEMENT
         }).error(function (data) {
           	 toastr.error("Insert QC form error.");
       	});
    };
    
    $scope.populateBoxes = function (category) {
	    if(category == 'shells') {
				$scope.qc_form.shells_hp_material = 1;
				$scope.qc_form.shells_defects = 1;
				$scope.qc_form.shells_colors = 1;
				$scope.qc_form.shells_matched_height = 1;
				$scope.qc_form.shells_canal_length = 1;
				$scope.qc_form.shells_helix_trimmed = 1;
				$scope.qc_form.shells_label = 1;
				$scope.qc_form.shells_edges = 1;
				$scope.qc_form.shells_high_shine = 1;
	    } else if(category == 'faceplate') {
				$scope.qc_form.faceplate_colors = 1;
				$scope.qc_form.faceplate_buffing_material = 1;
				$scope.qc_form.faceplate_seam = 1;
				$scope.qc_form.faceplate_orientation = 1;
				$scope.qc_form.faceplate_lanyard_loop = 1;
				$scope.qc_form.faceplate_knob_buttons = 1;
	    } else if(category == 'battery_door') {
				$scope.qc_form.battery_door_closes = 1;
				$scope.qc_form.battery_door_correct = 1;
				$scope.qc_form.battery_door_opens_forward = 1;
	    } else if(category == 'ports') {
				$scope.qc_form.ports_cleaned = 1;
				$scope.qc_form.ports_mic_flush = 1;
				$scope.qc_form.ports_glued_correctly = 1;
	    } else if(category == 'sound') {
				$scope.qc_form.sound_chip_programmed = 1;
				$scope.qc_form.sound_battery_signal = 1;
				$scope.qc_form.sound_programs = 1;
				$scope.qc_form.sound_volume_control = 1;
				$scope.qc_form.sound_mic_signal = 1;
				$scope.qc_form.sound_balanced_volume = 1;
	    } else if(category == 'shipping') {
				$scope.qc_form.shipping_cable = 1;
				$scope.qc_form.shipping_card = 1;
				$scope.qc_form.shipping_additional = 1;
				$scope.qc_form.shipping_tools = 1;
				$scope.qc_form.shipping_case = 1;
	    } 
    } 
    
           
    $scope.init=function()
    {

        AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });


        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('QC_Form_Edit', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
		$scope.Ready2Ship = "YES";
		$scope.qc_form = {
		};
 
		$scope.readyToShip = function () {
			$scope.Ready2Ship = "YES";
	 	}

	 	$scope.greeting = 'Hola!';
	 	$scope.qc_form = {
        		ticket_number: '',
			//qc_date: new Date,
    		};
    
        $scope.deleteDocument = function (fileid) {
        	console.log(fileid);
			if (confirm("Are you sure you want to delete this image?") == false) {
            	return;
        	}

        $http.get(window.cfg.apiUrl + "file/delete_alclairimage.php?id=" + fileid).success(function (result) {
            for (var i = 0; i < $scope.qc_form_fileList.length; i++) {
                if ($scope.qc_form_fileList[i].id == fileid) {
                    toastr.success("Delete document " + $scope.qc_form_fileList[i].filepath + " successful!", "Message");
                    $scope.qc_form_fileList.splice(i, 1);
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete document, please try again.");
        });
    }
    
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
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
    
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_alclair_fr.php';
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
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/alclair/qc_list/";
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            //window.location.href = window.cfg.rootUrl + "/alclair/qc_list/";
            
        }

    }
    
    $scope.GenerateQCForm = function (saveORship, uploaddocument) {
        
        console.log("WORKING")
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 


        var api_url = window.cfg.apiUrl + 'alclair/generate_qc_form_active_hp_from_qc_form_active_hp.php?id=' + window.cfg.Id;
		console.log(api_url+"?"+$scope.qc_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.qc_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         console.log("Test is " + JSON.stringify(result.test))
	         console.log("Test 1 is " + result.test)
             if (result.code == "success") {
	             //console.log("Update is " + JSON.stringify(result.update))
				window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form_active_hp/" + result.the_ID;
				toastr.success("Order Updated!")
				$.unblockUI();
			 } else {
               	$.unblockUI();
			 	console.log("message " + result.code);
			 	toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Ship QC form error.");
         });
    };
        
    $scope.Move2Cart = function (qc_id, order_status_id, notes) {
         if(order_status_id == undefined) {
	    		console.log("Id is " + qc_id + " and cart UNDEFINED " + order_status_id)     
	    		alert("Select a cart where the order will be going.");
         } else {
		 		console.log("Id is " + qc_id + " and cart ID is " + order_status_id)
		 
		        var api_url = window.cfg.apiUrl + 'alclair/move_qc_to_cart.php?qc_id=' + qc_id + "&order_status_id=" + order_status_id + "&notes=" + notes;
		        myblockui();
		        $http({
		            method: 'POST',
		            url: api_url,
		            data: $.param($scope.qc_form),
		            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		        })
		         .success(function (result) {
			         console.log("Test 1 is " + result.test)
		             if (result.code == "success") {
			             $("#updateRMA").modal("hide");
			             toastr.success("Order Updated!")
						 	setTimeout(function(){
								$scope.UploadFile();
								$.unblockUI();
							}, 500); 			 
						} else {
		               	$.unblockUI();
					 		console.log("message " + result.code);
		             }
		         }).error(function (data) {
		             toastr.error("Error saving QC Form.");
		             $.unblockUI();
		         });
       		} // CLOSE ELSE STATEMENT
    }    
        
    $scope.SaveData = function (saveORship, uploaddocument) {
        
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 

		if(saveORship == 'SAVE') {
        	var api_url = window.cfg.apiUrl + 'alclair/update_active_hp.php';
        	console.log("IN SAVE")
        } else {
	        var api_url = window.cfg.apiUrl + 'alclair/add_ship_active_hp.php';
	        console.log("IN ELSE")
        }	
		console.log(api_url+"?"+$scope.qc_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.qc_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         console.log("Test is " + JSON.stringify(result.test))
	         console.log("Test 1 is " + result.show_modal_window)
	         
              if(result.message == "Something is incomplete") {
	             toastr.error("Cannot ship product.  Please check that everything has passed QC.");
	             $.unblockUI();
             }   
             else {         
             	if (result.code == "success") {
	             	//console.log("Update is " + JSON.stringify(result.update))
			           	if(result.update === "Could not determine the correct Order ID.  The order's manufacturing state was not updated.") {
							toastr.error(result.update)
							setTimeout(function(){
								$scope.UploadFile();
								$.unblockUI();
							}, 500); 
						} else {
							$.unblockUI();
							if(result.show_modal_window == "FAIL") {
								console.log("Modal window is " + result.show_modal_window)
								AppDataService.loadOrderStatusTableList(null, null, function (result) {
									$scope.OrderStatusList = result.data;
								}, function (result) { });
								AppDataService.loadRepairStatusTableList(null, null, function (result) {
									$scope.RepairStatusList = result.data;
								}, function (result) { });
								$("#updateRMA").modal("show");
								
							} else {
								toastr.success("Order Updated!")
								console.log("The email is " + result.email)
								//return;
/*
	CODE FOR ACTIVE CAMPAIGN STARTS HERE
	*/														
							if(result.email) {
							 	 setTimeout(function(){
								 	 var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
								 	 //return;
								 	 Email = result.email;
								 	 Current_status = 'done';//'RIGHT';
								 	 Estimated_ship_date = moment(result.estimated_ship_date).format("MM/DD/YYYY");
									 
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
									 	if(json == "empty") {
									 		//console.log("JSON is empty & i is " + i)
									 	} else {
						
										}
									}, function errorCallback(response) {
										console.log("ERROR HERE")
			
									});	 
								 	 
									 toastr.success("Order Updated!")
									 setTimeout(function(){
									 	$scope.UploadFile();
									 	$.unblockUI();
									}, 500); 
								}, 500);   
								 //console.log("Email exists " + result.email)	 
							 } else {
								 	// EMAIL DOES NOT EXIST AND RELOAD PAGE ONLY
								 	setTimeout(function(){
										$scope.UploadFile();
										$.unblockUI();
									}, 500); 								 	
									//console.log("Email does not exist " + $scope.qrcode.email)	  
							 }								
								
								
/*
	CODE FOR ACTIVE CAMPAIGN ENDS HERE
	*/								
							} // END OF IF/ELSE STATEMENT
						}

             	}
			 	else {
                 	$.unblockUI();
                 	//toastr.error(result.message)
				 	console.log("message " + result.code);
				 	toastr.error(result.message == undefined ? result.data : result.message);
             	}
			} // END ELSE STATEMENT
         }).error(function (data) {
             toastr.error("Ship QC form error.");
         });
    };
    
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "alclair/get_active_hp.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test 1 is " + result.test1)
	            console.log("Test 2 is " + result.test2)
	            console.log("Test 3 is " + result.test3)
                if (result.data.length > 0) {
                    $scope.qc_form = result.data[0];
                    $scope.qc_form_fileList = result.data2;
                    console.log("Data 2 is " + JSON.stringify(result.data2))
                    //$scope.qc_form.qc_date = moment($scope.qc_form.qc_date).format("MM/DD/YYYY");
                    
                    // SET BOOLEAN VALUES FOR QC FORM
        
        // SHELLS
        if ($scope.qc_form.shells_hp_material == true) {
	    		$scope.qc_form.shells_hp_material = 1;    
        } else {
	        $scope.qc_form.shells_hp_material = 0;
        }
		if ($scope.qc_form.shells_defects == true) {
	    		$scope.qc_form.shells_defects = 1;    
        } else {
	        $scope.qc_form.shells_defects = 0;
        }
		if ($scope.qc_form.shells_colors == true) {
	    		$scope.qc_form.shells_colors = 1;    
        } else {
	        $scope.qc_form.shells_colors = 0;
        }
		if ($scope.qc_form.shells_matched_height == true) {
	    	$scope.qc_form.shells_matched_height = 1;    
        } else {
	        $scope.qc_form.shells_matched_height = 0;
        }
		if ($scope.qc_form.shells_canal_length == true) {
	    	$scope.qc_form.shells_canal_length = 1;    
        } else {
	        $scope.qc_form.shells_canal_length = 0;
        }
		if ($scope.qc_form.shells_helix_trimmed == true) {
	    	$scope.qc_form.shells_helix_trimmed = 1;    
        } else {
	        $scope.qc_form.shells_helix_trimmed = 0;
        }
        if ($scope.qc_form.shells_label == true) {
	    	$scope.qc_form.shells_label = 1;    
        } else {
	        $scope.qc_form.shells_label = 0;
        }
        if ($scope.qc_form.shells_edges == true) {
	    	$scope.qc_form.shells_edges = 1;    
        } else {
	        $scope.qc_form.shells_edges = 0;
        }
		if ($scope.qc_form.shells_high_shine == true) {
	    	$scope.qc_form.shells_high_shine = 1;    
        } else {
	        $scope.qc_form.shells_high_shine = 0;
        }
        // END SHELLS

        // FACEPLATE
        if ($scope.qc_form.faceplate_colors == true) {
	    	$scope.qc_form.faceplate_colors = 1;    
        } else {
	        $scope.qc_form.faceplate_colors = 0;
        }
		if ($scope.qc_form.faceplate_buffing_material == true) {
	    	$scope.qc_form.faceplate_buffing_material = 1;    
        } else {
	        $scope.qc_form.faceplate_buffing_material = 0;
        }
		if ($scope.qc_form.faceplate_seam == true) {
	    	$scope.qc_form.faceplate_seam = 1;    
        } else {
	        $scope.qc_form.faceplate_seam = 0;
        }
        if ($scope.qc_form.faceplate_orientation == true) {
	    	$scope.qc_form.faceplate_orientation = 1;    
        } else {
	        $scope.qc_form.faceplate_orientation = 0;
        }
        if ($scope.qc_form.faceplate_lanyard_loop == true) {
	    	$scope.qc_form.faceplate_lanyard_loop = 1;    
        } else {
	        $scope.qc_form.faceplate_lanyard_loop = 0;
        }
        if ($scope.qc_form.faceplate_knob_buttons == true) {
	    	$scope.qc_form.faceplate_knob_buttons = 1;    
        } else {
	        $scope.qc_form.faceplate_knob_buttons = 0;
        }
        // END FACEPLATE

	    // BATTERY DOOR
        if ($scope.qc_form.battery_door_closes == true) {
	    	$scope.qc_form.battery_door_closes = 1;    
        } else {
	        $scope.qc_form.battery_door_closes = 0;
        }
		if ($scope.qc_form.battery_door_correct == true) {
	    	$scope.qc_form.battery_door_correct = 1;    
        } else {
	        $scope.qc_form.battery_door_correct = 0;
        }
		if ($scope.qc_form.battery_door_opens_forward == true) {
	    	$scope.qc_form.battery_door_opens_forward = 1;    
        } else {
	        $scope.qc_form.battery_door_opens_forward = 0;
        }
        // END BATTERY DOOR
        
        // PORTS
        if ($scope.qc_form.ports_cleaned == true) {
	    	$scope.qc_form.ports_cleaned = 1;    
        } else {
	        $scope.qc_form.ports_cleaned = 0;
        }
		if ($scope.qc_form.ports_mic_flush == true) {
	    	$scope.qc_form.ports_mic_flush = 1;    
        } else {
	        $scope.qc_form.ports_mic_flush = 0;
        }
        if ($scope.qc_form.ports_glued_correctly == true) {
	    	$scope.qc_form.ports_glued_correctly = 1;    
        } else {
	        $scope.qc_form.ports_glued_correctly = 0;
        }
        // END PORTS

        // SOUND
        if ($scope.qc_form.sound_chip_programmed == true) {
	    	$scope.qc_form.sound_chip_programmed = 1;    
        } else {
	        $scope.qc_form.sound_chip_programmed = 0;
        }
		if ($scope.qc_form.sound_battery_signal == true) {
	    	$scope.qc_form.sound_battery_signal = 1;    
        } else {
	        $scope.qc_form.sound_battery_signal = 0;
        }
        if ($scope.qc_form.sound_programs == true) {
	    	$scope.qc_form.sound_programs = 1;    
        } else {
	        $scope.qc_form.sound_programs = 0;
        }
        if ($scope.qc_form.sound_volume_control == true) {
	    	$scope.qc_form.sound_volume_control = 1;    
        } else {
	        $scope.qc_form.sound_volume_control = 0;
        }
        if ($scope.qc_form.sound_mic_signal == true) {
	    	$scope.qc_form.sound_mic_signal = 1;    
        } else {
	        $scope.qc_form.sound_mic_signal = 0;
        }
        if ($scope.qc_form.sound_balanced_volume == true) {
	    	$scope.qc_form.sound_balanced_volume = 1;    
        } else {
	        $scope.qc_form.sound_balanced_volume = 0;
        }
        // END SOUND
        
         // SHIPPING -> PORTS
        if ($scope.qc_form.shipping_cable == true) {
	    	$scope.qc_form.shipping_cable = 1;    
        } else {
	        $scope.qc_form.shipping_cable = 0;
        }
		if ($scope.qc_form.shipping_tools == true) {
	    	$scope.qc_form.shipping_tools = 1;    
        } else {
	        $scope.qc_form.shipping_tools = 0;
        }
        if ($scope.qc_form.shipping_card == true) {
	    	$scope.qc_form.shipping_card = 1;    
        } else {
	        $scope.qc_form.shipping_card = 0;
        }
        if ($scope.qc_form.shipping_case == true) {
	    	$scope.qc_form.shipping_case = 1;    
        } else {
	        $scope.qc_form.shipping_case = 0;
        }
        if ($scope.qc_form.shipping_additional == true) {
	    	$scope.qc_form.shipping_additional = 1;    
        } else {
	        $scope.qc_form.shipping_additional = 0;
        }
        // END SHIPPING -> PORTS

                    
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
        AppDataService.loadFileList({ ticket_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });
    }
    
     $scope.populateBoxes = function (category) {
	    if(category == 'shells') {
				$scope.qc_form.shells_hp_material = 1;
				$scope.qc_form.shells_defects = 1;
				$scope.qc_form.shells_colors = 1;
				$scope.qc_form.shells_matched_height = 1;
				$scope.qc_form.shells_canal_length = 1;
				$scope.qc_form.shells_helix_trimmed = 1;
				$scope.qc_form.shells_label = 1;
				$scope.qc_form.shells_edges = 1;
				$scope.qc_form.shells_high_shine = 1;
	    } else if(category == 'faceplate') {
				$scope.qc_form.faceplate_colors = 1;
				$scope.qc_form.faceplate_buffing_material = 1;
				$scope.qc_form.faceplate_seam = 1;
				$scope.qc_form.faceplate_orientation = 1;
				$scope.qc_form.faceplate_lanyard_loop = 1;
				$scope.qc_form.faceplate_knob_buttons = 1;
	    } else if(category == 'battery_door') {
				$scope.qc_form.battery_door_closes = 1;
				$scope.qc_form.battery_door_correct = 1;
				$scope.qc_form.battery_door_opens_forward = 1;
	    } else if(category == 'ports') {
				$scope.qc_form.ports_cleaned = 1;
				$scope.qc_form.ports_mic_flush = 1;
				$scope.qc_form.ports_glued_correctly = 1;
	    } else if(category == 'sound') {
				$scope.qc_form.sound_chip_programmed = 1;
				$scope.qc_form.sound_battery_signal = 1;
				$scope.qc_form.sound_programs = 1;
				$scope.qc_form.sound_volume_control = 1;
				$scope.qc_form.sound_mic_signal = 1;
				$scope.qc_form.sound_balanced_volume = 1;
	    } else if(category == 'shipping') {
				$scope.qc_form.shipping_cable = 1;
				$scope.qc_form.shipping_card = 1;
				$scope.qc_form.shipping_additional = 1;
				$scope.qc_form.shipping_tools = 1;
				$scope.qc_form.shipping_case = 1;
	    } 
    }
          
    $scope.init=function()
    {
		$scope.LoadData();
       AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('QC_List', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.QC_FormList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.Passed = 0;
    $scope.SearchText = "";
    $scope.cust_name = "";
    $scope.pass_or_fail = '0';
    
	$(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
    document.getElementById("start").oninput = function() {myFunction()};
		
	function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("start").value;
				id_of_order = x;
				
				 var api_url = window.cfg.apiUrl + "alclair/get_qc_form_id.php?id=" + id_of_order
				 $http.get(api_url)
				 	.success(function (result) {
					 	id_of_qc_form = result.ID_is;
					 	console.log("Did it work " + id_of_qc_form)
					 	//console.log("The id is " + id_of_qc_form)
					 	window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form_active_hp/" + id_of_qc_form;
					    $.unblockUI();
            		}).error(function (result) {
						toastr.error("Not working.");
            	});

		}, 500); 
	};
    
    $scope.qc_form = {
        cust_name: '',
        pass_or_fail: '0',
        qc_date: new Date,
    };

	$scope.customers = [];
	$scope.getCustomers = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_customers.php";
	        $http.get(api_url).success(function (data) {
	            $scope.customers = data.data;
	    })
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
	$scope.SearchStartDate=window.cfg.TwoMonthsPrior;//CurrentMonthFirstDate;//OctoberOne; 
	$scope.SearchEndDate=window.cfg.CurrentDay;
	//console.log("Date is " + window.cfg.TwoMonthsPrior)
	//console.log("Date2 is " + window.cfg.FirstDay2MonthsAgo)
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
		//console.log("Monitor id is " + $scope.monitor_id)
		//console.log("Search text is " + $scope.SearchText)
        var api_url = window.cfg.apiUrl + "alclair/get_active_hp.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PASS_OR_FAIL=" + $scope.pass_or_fail + "&MonitorID=" + $scope.monitor_id + "&BuildTypeID=" + $scope.build_type_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.testing)
                $scope.QC_FormList = result.data;
                $scope.fileList = result.data2;
                $scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;
                console.log("Num of pages " + result.TotalPages)
                $scope.Passed = result.Passed;
                //console.log("Pass or Fail is " + result.testing)

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


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this form?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair/delete_active_hp.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.QC_FormList.length; i++) {
                if ($scope.QC_FormList[i].id == id) {
                    toastr.success("Delete QC form successful!", "Message");
                    $scope.QC_FormList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    location.reload();			
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
	    $scope.getCustomers();
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        $scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        
        $scope.LoadData();
    }
    $scope.init();
	}]);

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
				 // AN ACTIVE HP VERSION OF THE GET NOT REQUIRED - INCLUDED IF/ELSE STATEMENT TO HANDLE
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

        $http.get(window.cfg.apiUrl + "file/delete_alclairimage_active_hp.php?id=" + fileid).success(function (result) {
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
                       window.location.href = window.cfg.rootUrl + "/alclair/qc_list_active_hp/";
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
            window.location.href = window.cfg.rootUrl + "/alclair/qc_list_active_hp/";
            
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
									 	// January 9th, 2023 - A PROXY SERVER WAS REQUIRED
									 	// Cross-Origin Resource Sharing (CORS) WAS AN ISSUE
									 	//https://corsproxy.io/ WAS USED
									 	//url: 'https://corsproxy.io/?https://alclair.api-us1.com/api/3/contact/sync',
									 	url: 'https://proxy.cors.sh/https://alclair.api-us1.com/api/3/contact/sync',	 
									 	data: json_text,
									 	headers: {
										 	//'Content-Type': 'application/json',					 	
										 	'x-cors-api-key': 'live_3961693df7a5f15e329746337e79b0eea7e3c6d0593a17bf81094674cd73d556',
										 	'Api-Token': key_is,
										 	//'Origin':'https://alclair.api-us1.com/api/3/',
										 	//'Origin': 'https://otis.alclr.co:8080',
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
				
				// AN ACTIVE HP VERSION OF THE GET NOT REQUIRED - INCLUDED IF/ELSE STATEMENT TO HANDLE
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
	    var api_url = window.cfg.apiUrl + "alclair/get_customers_active_hp.php";
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
	
swdApp.controller('Repair_Form', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.repair_form = {
 	ticket_number: '',
    artwork_none: 0,
	repair_date: new Date,
	received_date: window.cfg.CurrentDay,
	//estimated_ship_date: window.cfg.CurrentDay_plus_2weeks,
	estimated_ship_date: window.cfg.CurrentDay_plus_3weeks,
};

	$scope.faults = [];
    $count_faults = 0;
    $scope.newFault = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.faults.push({});
        $scope.faults[$count_faults] = {
			classification: 'Fit',
			description_id: 6,
			fault_sound: 1,
			fault_fit: 1,
			fault_design: 1,
			fault_customer: 1,
			fault_ownership_transfer: 1,
		}
        $count_faults = $count_faults + 1;
        console.log("Fault count is " + $count_faults)
    }
    $scope.removeFault = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.faults.pop({});
        $count_faults = $count_faults - 1;
        console.log("Fault count is " + $count_faults)
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
   	//$scope.SearchStartDate=window.cfg.CurrentDay;
   	
   	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
    $scope.openShip = function ($event) {        
        $scope.openedShip = true;
    };
    $scope.openOriginal = function ($event) {        
        $scope.openedOriginal = true;
    };
            
    $scope.SaveThenPrintTraveler = function (saveORship, uploaddocument) {
        
        if($scope.repair_form.estimated_ship_date)
        	$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
		if($scope.repair_form.received_date)
			$scope.repair_form.received_date = $scope.repair_form.received_date.toLocaleString();  
		if($scope.repair_form.original_ship_date_of_order)
			$scope.repair_form.original_ship_date_of_order = $scope.repair_form.original_ship_date_of_order.toLocaleString();  
			
        var api_url = window.cfg.apiUrl + 'alclair/rma_add_active_hp.php';
		console.log(api_url+"?"+$scope.repair_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.repair_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             console.log("message " + result.message);

             if (result.code == "success") {
                 $.unblockUI();
                 
                  $scope.id_of_repair = result.id_of_repair;
				  $scope.addFaults($scope.id_of_repair);

                 //alert(result.data.id);
                 //if (result.data.id !=undefined)
                 if (result.the_ID !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     $scope.repair_form.id = result.the_ID;
                     //$scope.PDF(id_to_print);
                     //$scope.PDF(result.data.id);
                     $scope.PDF(result.the_ID);
                     toastr.success("Form saved successfully.");
                     setTimeout(function(){
					 	location.reload();				 	
					}, 2000);    
                    //window.location.href = window.cfg.rootUrl + "/alclair/repair_form/";
					
                     //$scope.UploadFile();
                 }
                 else
                 {
                     toastr.error("Form saved.  Please navigate to Edit Form to Print.")
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert repair form error.");

         });
    };
    
     $scope.PDF = function (id) {
	    //var $order_id = 9729;
        console.log("In PDF")
        //myblockui();
        console.log("Order num is " +  id)
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF_repair.php?ID=" + id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Data is " + result.data);
console.log("Test 1 is " + result.test1)
            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }
    
    $scope.SaveRMA = function (saveORship, uploaddocument) {

        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 

        // SET BOOLEAN VALUES FOR QC FORM
        console.log("Name is " + $scope.repair_form.customer_name)
        console.log("Email is " + $scope.repair_form.email)
        console.log("Phone is " + $scope.repair_form.phone)      

        if($scope.repair_form.estimated_ship_date)
        	$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
		if($scope.repair_form.received_date)
			$scope.repair_form.received_date = $scope.repair_form.received_date.toLocaleString();  
		if($scope.repair_form.original_ship_date_of_order) {
			$scope.repair_form.original_ship_date_of_order = $scope.repair_form.original_ship_date_of_order.toLocaleString();  
			console.log("NOT NULL DATE IS " + $scope.repair_form.original_ship_date_of_order)
		} else {
			//$scope.repair_form.original_ship_date_of_order = null;
			console.log("NULL DATE IS " + $scope.repair_form.original_ship_date_of_order)
		}
		//return;
		console.log("Then est ship is " + $scope.repair_form.estimated_ship_date)
        var api_url = window.cfg.apiUrl + 'alclair/rma_add_active_hp.php';
		console.log(api_url+"?"+$scope.repair_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.repair_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {

	         if(result.move_on == "Break") {
		         
		         $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
		         return;
	         } else {
             	console.log(result);
			 	console.log("message " + result.message);
			 	console.log("Test is " + result.test)

			 	$scope.id_of_repair = result.id_of_repair;
			 	$scope.addFaults($scope.id_of_repair);
             }             
             if (result.code == "success") {
	  
                 $.unblockUI();
                 //alert(result.data.id);
                 //if (result.data.id !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     //toastr.success("Form saved successfully.");
                     toastr.success("PLEASE HAVE PATIENCE WITH ME. PROCESSING...");
                     //window.location.href = window.cfg.rootUrl + "/alclair/repair_list_active_hp/";

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
    };
    
    $scope.addFaults = function(id_of_repair) {

		for (i = 0; i < $scope.faults.length; i++) {
			console.log("Length is  @ line 1196 " + $scope.faults.length)
			//return;
			//console.log("Repair ID is still " + id_of_repair)
			//console.log("Description ID is " + $scope.faults[i].description_id)
			var api_url = window.cfg.apiUrl + 'alclair/add_faults_active_hp.php?id_of_repair=' + id_of_repair;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.faults[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
						console.log("IN SUCCESS")
						//return;

					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (result) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
				  
		}  // CLOSE FOR LOOP
			
		setTimeout(function() { 
			window.location.href = window.cfg.rootUrl + "/alclair/repair_list_active_hp/";
		}, 4000)
	}
    
    
    $scope.init=function()
    {
	    
	    var api_url = window.cfg.apiUrl + "alclair/get_next_id_from_repair_form_active_hp.php";

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            //console.log(result.data);
			$scope.repair_form.rma_number = result.next_id;
			console.log("THE ID IS 6 " + $scope.repair_form.rma_number)
        }).error(function () {
            $.unblockUI();
            toastr.error("Error!");
        });


        AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        
        $scope.FAULT_TYPES = AppDataService.FAULT_TYPES_ACTIVE_HP;
        
        AppDataService.loadSoundFaultsList_Active_HP(null, null, function (result) {
           $scope.soundFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadFitFaultsList_Active_HP(null, null, function (result) {
           $scope.fitFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadDesignFaultsList_Active_HP(null, null, function (result) {
           $scope.designFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadCustomerFaultsList_Active_HP(null, null, function (result) {
           $scope.customerFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadOwnershipTransferFaultsList_Active_HP(null, null, function (result) {
           $scope.ownershipTransferFaultsList = result.data;
        }, function (result) { });


        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('Repair_Form_Edit', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
				
	var id_of_repair = 	window.cfg.Id;
	$scope.faults = [];
	var faults_IDs = [];
		
   	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
    $scope.openShip = function ($event) {        
        $scope.openedShip = true;
    };
    $scope.openOriginal = function ($event) {        
        $scope.openedOriginal = true;
    };
    

	$scope.EditNotes = function (key, ID) {			
		var api_url = window.cfg.apiUrl + 'notes_for_repair_form/get_note_from_repair_active_hp_for_traveler.php?id=' + ID;
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
		var api_url = window.cfg.apiUrl + 'notes_for_repair_form/update_note_from_repair_active_hp_for_traveler.php?id_to_edit=' + id_to_edit; 
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


    $scope.faults = [];
    var faults_IDs = [];
    $scope.newFault = function($event){
        // prevent submission
        $count_faults = $scope.faults.length;
        $event.preventDefault();
        $scope.faults.push({});
        $scope.faults[$count_faults] = {
			classification: 'Fit',
			description_id: 9,
			fault_sound: 1,
			fault_fit: 1,
			fault_design: 1,
			fault_customer: 1,
		}
        $count_faults = $count_faults + 1;
    }
    $scope.removeFault = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.faults.pop({});
    }
    
    $scope.Date2Null = function($event){
        if($scope.repair_form.date_null == 1) {
        	$scope.repair_form.estimated_ship_date = '';
        }
    }

	$scope.addFaults = function(id_of_repair) {
		for (i = 0; i < $scope.faults.length; i++) {
			console.log("Length is  " + $scope.faults.length)
			console.log("Repair ID is still " + id_of_repair)
			var api_url = window.cfg.apiUrl + 'alclair/add_faults_active_hp.php?id_of_repair=' + id_of_repair;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.faults[i]),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				.success(function (result) {                          
					if (result.code == "success") {
						//$.unblockUI();
					} else {
						toastr.error(result.message == undefined ? result.data : result.message);
					}
				}).error(	function (result) {
					console.log("Code is " + result.code)
					toastr.error("Insert ship to error.");
				});
			}  // CLOSE FOR LOOP
	}
    
    
    document.getElementById ("qcform").addEventListener ("click", gotoQC_Form, false);
//    $scope.gotoQC_Form = function () {
	function gotoQC_Form() {
	    //var $order_id = 9729;
        myblockui();
        //myblockui();
		//  THE IF PORTION OF THE STATEMENT IS THE SIMPLE AND FAST WAY TO GET TO THE QC FORM
		//  THE FIRST PASS OF LINKING THE ORDER TO THE QC FORM DID  NOT INCLUDE A "ID OF QC FORM" ENTRY IN THE TABLE
		//  THE ELSE PORTION OF THE STATEMENT USES A FILE TO LOOK FOR THE QC FORM IF IT MAY EXIST
        if($scope.repair_form.id_of_qc_form != null) { 
	        $.unblockUI();
	        window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+$scope.repair_form.id_of_qc_form;
        } else {
	        // AN ACTIVE HP VERSION OF THE GET NOT REQUIRED - INCLUDED IF/ELSE STATEMENT TO HANDLE
			var api_url = window.cfg.apiUrl + "alclair/get_qc_form_id.php?customer_name=" + $scope.repair_form.customer_name + "&order_id=" + $scope.repair_form.rma_number + "&id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            if(result.code == "success") {
	            	console.log("The ID is " + result.ID_is)
					console.log("Message is " + result.message)
					if (result.ID_is == null) {
						toastr.error("Cannot find the QC Form.")
						$.unblockUI();
					} else {
						$.unblockUI();
						window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/"+result.ID_is;
					}
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
    
    $scope.PDF = function (id) {
	    //var $order_id = 9729;
        
        //myblockui();
        console.log("Order num is " +  id)
	    
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF_repair_active_hp.php?ID=" + id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);
            console.log("TESTING IS " + result.test)

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }

	 $scope.Print_Traveler = function (id_to_print) {
		 //$scope.updateRMA_Form(id_to_print);
		
		 ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////// DETERMINING IF UPDATING, ADDING OR DELETING A FAULT ////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for (k = 0; k < faults_IDs.length; k++) {
		    for (i = 0; i < $scope.faults.length; i++) {
			    if(faults_IDs[k] == $scope.faults[i].id) {
					// UPDATE JOB
					console.log("UPDATE: The ID is " + $scope.faults[i].id)
					
					var api_url = window.cfg.apiUrl + 'alclair/update_fault_active_hp.php'; //?id=' + faultS_IDs[m];
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.faults[i]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {                          
						if (result.code == "success") {		
							$.unblockUI();
           				} else {
			 				$.unblockUI();
			 				//console.log("adsfasdfasdfasdfsdf")
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
						console.log("Code is " + result.code)
						toastr.error("Insert fault error.");
		 			});
					
					break;
				} else if(i == $scope.faults.length-1) {
					// DELETE fault AT ID = k
					console.log("DELETE: The ID is " + faults_IDs[k])
					$http.get(window.cfg.apiUrl + "alclair/delete_fault_active_hp.php?id=" + faults_IDs[k]).success(function (result) {
                    }).error(function (result) {
						toastr.error("Failed to delete fault, please try again.");
        			});					
				}
			}	
		}
		for (i = 0; i < $scope.faults.length; i++) {
	    	if(!$scope.faults[i].id)  {	
			    // ADD fault
				console.log("ADD: There is no ID " + $scope.faults[i].id)
				
				var api_url = window.cfg.apiUrl + 'alclair/add_faults_active_hp.php?id_of_repair=' + id_of_repair;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.faults[i]),
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
					toastr.error("Insert fault error.");
				});
			}
		}
		///////////////////////    END DEALING WITH FAULTS   /////////////////////////////////
		if($scope.repair_form.estimated_ship_date == '') {
	       	$scope.repair_form.estimated_ship_date = null;
       	} else {
	   		$scope.repair_form.estimated_ship_date = moment($scope.repair_form.estimated_ship_date).format("MM/DD/YYYY");
	   	}
	   	if($scope.repair_form.original_ship_date_of_order == '' || $scope.repair_form.original_ship_date_of_order == null) {
	       	$scope.repair_form.original_ship_date_of_order = '';
	       	console.log("IN IF")
       	} else {
	   		$scope.repair_form.original_ship_date_of_order = moment($scope.repair_form.original_ship_date_of_order).format("MM/DD/YYYY");
	   		console.log("IN ELSE")
	   	}
		//return;
		// COMMENTED OUT LINE 2 LINES DOWN TO REPLACE WITH THE IF STATEMENT ABOVE 07/29/2019 
		if($scope.repair_form.estimated_ship_date)
        	//$scope.repair_form.estimated_ship_date = moment($scope.repair_form.estimated_ship_date).format("MM/DD/YYYY");
        	//$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
		if($scope.repair_form.received_date)
			$scope.repair_form.received_date = moment($scope.repair_form.received_date).format("MM/DD/YYYY");
			//$scope.repair_form.received_date = $scope.repair_form.received_date.toLocaleString();
 
		//console.log("received date is " + $scope.repair_form.estimated_ship_date)
		//console.log("Date is " + $scope.repair_form.received_date)
								
			$scope.repair_form.quotation = Number($scope.repair_form.quotation);
			$scope.repair_form.rma_number = Number($scope.repair_form.rma_number);
		var api_url = window.cfg.apiUrl + 'alclair/update_repair_active_hp_form.php';
		        
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.repair_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
	         console.log("Code is " + result.code)
	         console.log("received date is " + result.test)
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success("Updates saved!")
                 toastr.success(result.message)
                 $scope.PDF(id_to_print);
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
        
    $scope.updateRMA_Form = function (uploaddocument) {
	    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////// DETERMINING IF UPDATING, ADDING OR DELETING A FAULT ////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for (k = 0; k < faults_IDs.length; k++) {
		    for (i = 0; i < $scope.faults.length; i++) {
			    if(faults_IDs[k] == $scope.faults[i].id) {
					// UPDATE JOB
					console.log("UPDATE: The ID is " + $scope.faults[i].id)
					
					var api_url = window.cfg.apiUrl + 'alclair/update_fault_active_hp.php'; //?id=' + faultS_IDs[m];
						myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.faults[i]),
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
						toastr.error("Insert fault error.");
		 			});
					
					break;
				} else if(i == $scope.faults.length-1) {
					// DELETE fault AT ID = k
					console.log("DELETE: The ID is " + faults_IDs[k])
					$http.get(window.cfg.apiUrl + "alclair/delete_fault_active_hp.php?id=" + faults_IDs[k]).success(function (result) {
                    }).error(function (result) {
						toastr.error("Failed to delete fault, please try again.");
        			});					
				}
			}	
		}
		for (i = 0; i < $scope.faults.length; i++) {
	    	if(!$scope.faults[i].id)  {	
			    // ADD fault
				console.log("ADD: There is no ID " + $scope.faults[i].id)
				
				var api_url = window.cfg.apiUrl + 'alclair/add_faults_active_hp.php?id_of_repair=' + id_of_repair;
				myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.faults[i]),
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
					toastr.error("Insert fault error.");
				});
			}
		}
		///////////////////////    END DEALING WITH FAULTS   /////////////////////////////////
	    if($scope.repair_form.estimated_ship_date == '') {
	       	$scope.repair_form.estimated_ship_date = null;
       	} else {
	   		$scope.repair_form.estimated_ship_date = moment($scope.repair_form.estimated_ship_date).format("MM/DD/YYYY");
	   	}
	   	if($scope.repair_form.original_ship_date_of_order == '') {
	       	$scope.repair_form.original_ship_date_of_order = null;
       	} else {
	   		$scope.repair_form.original_ship_date_of_order = moment($scope.repair_form.original_ship_date_of_order).format("MM/DD/YYYY");
	   	}
	   	if($scope.repair_form.original_ship_date_of_order == '' || $scope.repair_form.original_ship_date_of_order == null) {
	       	$scope.repair_form.original_ship_date_of_order = '';
	       	console.log("IN IF")
       	} else {
	   		$scope.repair_form.original_ship_date_of_order = moment($scope.repair_form.original_ship_date_of_order).format("MM/DD/YYYY");
	   		console.log("IN ELSE  and " + $scope.repair_form.original_ship_date_of_order )
	   	}
		//return;

	   	//return;
        
       // COMMENTED OUT LINE 2 LINES DOWN TO REPLACE WITH THE IF STATEMENT ABOVE 07/29/2019 
       if($scope.repair_form.estimated_ship_date) {
	        	//$scope.repair_form.estimated_ship_date = moment($scope.repair_form.estimated_ship_date).format("MM/DD/YYYY");
        	//$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
        }
		if($scope.repair_form.received_date)
			$scope.repair_form.received_date = moment($scope.repair_form.received_date).format("MM/DD/YYYY");
			//$scope.repair_form.received_date = $scope.repair_form.received_date.toLocaleString();
					//if(saveORship == 'SAVE') {
						
		console.log("Date is " + $scope.repair_form.received_date)
								
			$scope.repair_form.quotation = Number($scope.repair_form.quotation);
			$scope.repair_form.rma_number = Number($scope.repair_form.rma_number);
        var api_url = window.cfg.apiUrl + 'alclair/update_repair_active_hp_form.php';
       	
		console.log(api_url+"?"+$scope.repair_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.repair_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {

             console.log("message  " + result.message);
             console.log("The Test is " + result.test);
             console.log("The JSON is " + JSON.stringify(result));
                          
             if (result.code == "success") {
                 
				 //$scope.UploadFile();
				 $.unblockUI();
				 window.location.href = window.cfg.rootUrl + "/alclair/repair_list_active_hp/";
             }
             else {
                 $.unblockUI();
                 console.log("message " + result.code);
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert repair form error.");

         });
    };
    
    $scope.showOnManufacturingScreen = function () {
	    myblockui();
		var api_url = window.cfg.apiUrl + 'alclair/move_to_manufacturing_screen_repair_active_hp.php?id=' + window.cfg.Id;
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
		var api_url = window.cfg.apiUrl + 'alclair/remove_from_manufacturing_screen_repair_active_hp.php?id=' + window.cfg.Id;
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
    
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "alclair/get_repair_active_hp_forms.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
	                $scope.logList = result.data2;
                    $scope.repair_form = result.data[0];
                    $scope.repair_form_fileList = result.data2;
                    
                    $scope.faults = result.data_faults;
                    $scope.num_of_faults = $scope.faults.length;
                    for (i = 0; i < $scope.faults.length; i++) {
						faults_IDs[i] = $scope.faults[i].id;
					}
					
					 if(result.data[0].manufacturing_screen == true) {
					 	$scope.manufacturing_screen = 1; 
					 	console.log("HERE")
	           	} else {
		           	$scope.manufacturing_screen = 0;
		           	console.log("HEREeeeeeeee")
	           	}
	           	$scope.the_user_is = result.the_user_is;
	           	console.log("USER IS " + $scope.the_user_is)


                    //console.log("Data 2 is " + JSON.stringify(result.data2))
                    
		// SET BOOLEAN VALUES FOR QC FORM
        
        // ARTWORK
        if ($scope.repair_form.artwork_white == true) {
	    		$scope.repair_form.artwork_white = 1;    
        } else {
	        $scope.repair_form.artwork_white = 0;
        }
		if ($scope.repair_form.artwork_black == true) {
	    		$scope.repair_form.artwork_black = 1;    
        } else {
	        $scope.repair_form.artwork_black = 0;
        }
		if ($scope.repair_form.artwork_logo == true) {
	    		$scope.repair_form.artwork_logo = 1;    
        } else {
	        $scope.repair_form.artwork_logo = 0;
        }
		if ($scope.repair_form.artwork_icon == true) {
	    	$scope.repair_form.artwork_icon = 1;    
        } else {
	        $scope.repair_form.artwork_icon = 0;
        }
		if ($scope.repair_form.artwork_stamp == true) {
	    	$scope.repair_form.artwork_stamp = 1;    
        } else {
	        $scope.repair_form.artwork_stamp = 0;
        }
		if ($scope.repair_form.artwork_script == true) {
	    	$scope.repair_form.artwork_script = 1;    
        } else {
	        $scope.repair_form.artwork_script = 0;
        }
        if ($scope.repair_form.artwork_custom == true) {
	    	$scope.repair_form.artwork_custom = 1;    
        } else {
	        $scope.repair_form.artwork_custom = 0;
        }

        // END ARTWORK

	    // CUSTOMER STUFF
        if ($scope.repair_form.customer_contacted == true) {
	    	$scope.repair_form.customer_contacted = 1;    
        } else {
	        $scope.repair_form.customer_contacted = 0;
        }
         if ($scope.repair_form.warranty_repair == true) {
	    	$scope.repair_form.warranty_repair = 1;    
        } else {
	        $scope.repair_form.warranty_repair = 0;
        }
		if ($scope.repair_form.customer_billed == true) {
	    	$scope.repair_form.customer_billed = 1;    
        } else {
	        $scope.repair_form.customer_billed = 0;
        }
		if ($scope.repair_form.consulted == true) {
	    	$scope.repair_form.consulted = 1;    
        } else {
	        $scope.repair_form.consulted = 0;
        }
        if ($scope.repair_form.personal_item == true) {
	    	$scope.repair_form.personal_item = 1;    
        } else {
	        $scope.repair_form.personal_item = 0;
        }
		if ($scope.repair_form.rep_fit_issue == true) {
	    	$scope.repair_form.rep_fit_issue = 1;    
        } else {
	        $scope.repair_form.rep_fit_issue = 0;
        }

        // END CUSTOMER STUFF

		// REPAIR PERFORMED STUFF
        if ($scope.repair_form.repaired_shell_right == true) {
	    	$scope.repair_form.repaired_shell_right = 1;    
        } else {
	        $scope.repair_form.repaired_shell_right = 0;
        }
        if ($scope.repair_form.repaired_shell_left == true) {
	    	$scope.repair_form.repaired_shell_left = 1;    
        } else {
	        $scope.repair_form.repaired_shell_left = 0;
        }
		if ($scope.repair_form.repaired_faceplate_right == true) {
	    	$scope.repair_form.repaired_faceplate_right = 1;    
        } else {
	        $scope.repair_form.repaired_faceplate_right = 0;
        }
        if ($scope.repair_form.repaired_faceplate_left == true) {
	    	$scope.repair_form.repaired_faceplate_left = 1;    
        } else {
	        $scope.repair_form.repaired_faceplate_left = 0;
        }
		if ($scope.repair_form.replaced_faceplate_right == true) {
	    	$scope.repair_form.replaced_faceplate_right = 1;    
        } else {
	        $scope.repair_form.replaced_faceplate_right = 0;
        }
        if ($scope.repair_form.replaced_faceplate_left == true) {
	    	$scope.repair_form.replaced_faceplate_left = 1;    
        } else {
	        $scope.repair_form.replaced_faceplate_left = 0;
        }
        if ($scope.repair_form.replaced_mic_right == true) {
	    	$scope.repair_form.replaced_mic_right = 1;    
        } else {
	        $scope.repair_form.replaced_mic_right = 0;
        }
        if ($scope.repair_form.replaced_mic_left == true) {
	    	$scope.repair_form.replaced_mic_left = 1;    
        } else {
	        $scope.repair_form.replaced_mic_left = 0;
        }
        if ($scope.repair_form.repaired_jacks_right == true) {
	    	$scope.repair_form.repaired_jacks_right = 1;    
        } else {
	        $scope.repair_form.repaired_jacks_right = 0;
        }
        if ($scope.repair_form.repaired_jacks_left == true) {
	    	$scope.repair_form.repaired_jacks_left = 1;    
        } else {
	        $scope.repair_form.repaired_jacks_left = 0;
        }
        if ($scope.repair_form.touched_up_wires_chip_right == true) {
	    	$scope.repair_form.touched_up_wires_chip_right = 1;    
        } else {
	        $scope.repair_form.touched_up_wires_chip_right = 0;
        }
        if ($scope.repair_form.touched_up_wires_chip_left == true) {
	    	$scope.repair_form.touched_up_wires_chip_left = 1;    
        } else {
	        $scope.repair_form.touched_up_wires_chip_left = 0;
        }
		if ($scope.repair_form.replaced_driver_right == true) {
	    	$scope.repair_form.replaced_driver_right = 1;    
        } else {
	        $scope.repair_form.replaced_driver_right = 0;
        }
        if ($scope.repair_form.replaced_driver_left == true) {
	    	$scope.repair_form.replaced_driver_left = 1;    
        } else {
	        $scope.repair_form.replaced_driver_left = 0;
        }
        
        
        if ($scope.repair_form.new_shells_right == true) {
	    	$scope.repair_form.new_shells_right = 1;    
        } else {
	        $scope.repair_form.new_shells_right = 0;
        }
        if ($scope.repair_form.new_shells_left == true) {
	    	$scope.repair_form.new_shells_left = 1;    
        } else {
	        $scope.repair_form.new_shells_left = 0;
        }
        if ($scope.repair_form.new_faceplate_right == true) {
	    	$scope.repair_form.new_faceplate_right = 1;    
        } else {
	        $scope.repair_form.new_faceplate_right = 0;
        }
        if ($scope.repair_form.new_faceplate_left == true) {
	    	$scope.repair_form.new_faceplate_left = 1;    
        } else {
	        $scope.repair_form.new_faceplate_left = 0;
        }
        if ($scope.repair_form.replaced_tubes_right == true) {
	    	$scope.repair_form.replaced_tubes_right = 1;    
        } else {
	        $scope.repair_form.replaced_tubes_right = 0;
        }
        if ($scope.repair_form.replaced_tubes_left == true) {
	    	$scope.repair_form.replaced_tubes_left = 1;    
        } else {
	        $scope.repair_form.replaced_tubes_left = 0;
        }
        if ($scope.repair_form.moved_mic_right == true) {
	    	$scope.repair_form.moved_mic_right = 1;    
        } else {
	        $scope.repair_form.moved_mic_right = 0;
        }
        if ($scope.repair_form.moved_mic_left == true) {
	    	$scope.repair_form.moved_mic_left = 1;    
        } else {
	        $scope.repair_form.moved_mic_left = 0;
        }
        if ($scope.repair_form.cleaned_right == true) {
	    	$scope.repair_form.cleaned_right = 1;    
        } else {
	        $scope.repair_form.cleaned_right = 0;
        }
        if ($scope.repair_form.cleaned_left == true) {
	    	$scope.repair_form.cleaned_left = 1;    
        } else {
	        $scope.repair_form.cleaned_left = 0;
        }
        if ($scope.repair_form.replaced_chip_right == true) {
	    	$scope.repair_form.replaced_chip_right = 1;    
        } else {
	        $scope.repair_form.replaced_chip_right = 0;
        }
        if ($scope.repair_form.replaced_chip_left == true) {
	    	$scope.repair_form.replaced_chip_left = 1;    
        } else {
	        $scope.repair_form.replaced_chip_left = 0;
        }
        if ($scope.repair_form.adjusted_fit_right == true) {
	    	$scope.repair_form.adjusted_fit_right = 1;    
        } else {
	        $scope.repair_form.adjusted_fit_right = 0;
        }
        if ($scope.repair_form.adjusted_fit_left == true) {
	    	$scope.repair_form.adjusted_fit_left = 1;    
        } else {
	        $scope.repair_form.adjusted_fit_left = 0;
        }
        // END REPAIR PERFORMED STUFF

		if($scope.repair_form.original_ship_date_of_order == '' || $scope.repair_form.original_ship_date_of_order == null) 
	       	$scope.repair_form.original_ship_date_of_order = '';
	    
		//return;

                    
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
    
    $scope.showUpdateRMAModal = function () {
        $("#updateRMA").modal("show");
    };
    
    $scope.updateRMA_Form_Performed = function (id, uploaddocument) {
        
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 

        // SET BOOLEAN VALUES FOR QC FORM
        var api_url = window.cfg.apiUrl + 'alclair/update_rma_performed_active_hp.php?id=' + id;
		console.log(api_url+"?"+$scope.repair_form);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.repair_form),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
		    console.log("TEST IS " + result.test)

             console.log(result);
             console.log("message " + result.code);
                          
             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 //if (result.data.id !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     toastr.success("Form saved successfully.");
                     $("#updateRMA").modal("hide");
                     //window.location.href = window.cfg.rootUrl + "/alclair/repair_form/";

                     //$scope.UploadFile();
                 }
                 //else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 console.log("Failed here")
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("Code is " + result.code)
             toastr.error("Insert repair performed error.");

         });
    };
           
    $scope.init=function()
    {
		
		$scope.LoadData();
        AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadRepairStatusTableList(null, null, function (result) {
           $scope.RepairStatusList = result.data;
        }, function (result) { });
        
        $scope.FAULT_TYPES = AppDataService.FAULT_TYPES;
        
        AppDataService.loadSoundFaultsList_Active_HP(null, null, function (result) {
           $scope.soundFaultsList = result.data;
           
        }, function (result) { });
        AppDataService.loadFitFaultsList_Active_HP(null, null, function (result) {
           $scope.fitFaultsList = result.data;  
        }, function (result) { });
        AppDataService.loadDesignFaultsList_Active_HP(null, null, function (result) {
           $scope.designFaultsList = result.data;
        }, function (result) { });
		AppDataService.loadCustomerFaultsList_Active_HP(null, null, function (result) {
           $scope.customerFaultsList = result.data;
        }, function (result) { });



        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);
swdApp.controller('Repair_List', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.Repair_FormList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.Passed = 0;
    $scope.SearchText = "";
    $scope.cust_name = "";
    $scope.repaired_or_not = '0';
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
    $(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
	
	 document.getElementById("barcode_rma").oninput = function() {myFunction()};
		
	 function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("barcode_rma").value;
				//pos = x.indexOf("-");
				//id_of_qc_form = x.slice(0,pos);
				id_of_order = x.substring(1,  x.length);
				console.log("The id is " + id_of_order)
				//var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + id_of_order;
				window.location.href = window.cfg.rootUrl + "/alclair/edit_repair_form_active_hp/" + id_of_order; 
		}, 500); 
	};    
    $scope.repair_form = {
        cust_name: '',
        pass_or_fail: '0',
        repair_date: new Date,
    };

	$scope.customers = [];
	$scope.getCustomers = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_customers_repairs_active_hp.php";
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
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;//OctoberOne;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;

	//$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";
        $scope.cust_name = "";
		$scope.repair_form = {
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
		console.log("Monitor id is " + $scope.monitor_id)
		console.log("Repaired or not is  " + $scope.repaired_or_not)
		console.log("TESTING IS FIRST  " + $scope.SearchText)
		// IF STATEMENT IS FOR WHEN THE SEARCH RETURNS NULL
		if(!$scope.SearchText) {
			$scope.SearchText = '';
		}
		//console.log("TESTING IS SECOND  " + $scope.SearchText)
		console.log("START DATE IS   " + $scope.SearchEndDate)
        var api_url = window.cfg.apiUrl + "alclair/get_repair_active_hp_forms.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+ "&MonitorID=" + $scope.monitor_id + "&REPAIRED_OR_NOT="+$scope.repaired_or_not + "&REPAIR_STATUS_ID=" + $scope.repair_status_id;
        
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("TESTING IS  " + result.test)
                $scope.Repair_FormList = result.data;
                //console.log("Status is " + $scope.Repair_FormList[0].status_of_repair)
                $scope.fileList = result.data2;
                $scope.Repair_Form = result.customer_name;
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

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteRepairForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this form?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair/deleteRepairForm_active_hp.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.Repair_FormList.length; i++) {
                if ($scope.Repair_FormList[i].id == id) {
                    toastr.success("Delete repair form successful!", "Message");
                    $scope.Repair_FormList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete form, please try again.");
        });
    };
    
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };
        
    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
    }
    
    $scope.DONE2 = function () {
		console.log("The ID is " + $scope.id_to_make_done)    
	}
        
    $scope.DONE = function () {
	    //var $order_id = 9729;
        console.log("The ID here is " + $scope.id_to_make_done)   
        myblockui();
        $('#SelectDateModal').modal("hide");
        var api_url = window.cfg.apiUrl + "alclair/move_repair_to_done_active_hp.php?ID=" + $scope.id_to_make_done +"&DoneDate="+moment($scope.done_date).format("MM/DD/YYYY");

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Result is " + result.data);
			toastr.success("Successfully moved to Done.");
			$scope.LoadData();

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }
    

    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
	    $scope.getCustomers();

        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        if (isEmpty($scope.repair_form.cust_name)) $scope.repair_form.cust_name = "";
        AppDataService.loadMonitorList_Active_HP(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        $scope.REPAIRED_OR_NOT = AppDataService.REPAIRED_OR_NOT;
        AppDataService.loadRepairStatusTableList(null, null, function (result) {
           $scope.repairStatusTableList = result.data;
        }, function (result) { });


        $scope.LoadData();
    }
    $scope.init();
	}]);


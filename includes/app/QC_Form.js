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
            window.location.href = window.cfg.rootUrl + "/alclair/qc_list/";
            
        }

    }
    
    $scope.GenerateQCForm = function (saveORship, uploaddocument) {
        
        console.log("WORKING")
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 


        var api_url = window.cfg.apiUrl + 'alclair/generate_qc_form_from_qc_form.php?id=' + window.cfg.Id;
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
				window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/" + result.the_ID;
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
        	var api_url = window.cfg.apiUrl + 'alclair/update.php';
        } else {
	        var api_url = window.cfg.apiUrl + 'alclair/add_ship.php';
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
								setTimeout(function(){
									$scope.UploadFile();
									$.unblockUI();
								}, 500); 
							}
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
        var api_url = window.cfg.apiUrl + "alclair/get.php?id=" + window.cfg.Id;
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
		if ($scope.qc_form.shells_faced_down == true) {
	    		$scope.qc_form.shells_faced_down = 1;    
        } else {
	        $scope.qc_form.shells_faced_down = 0;
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
		if ($scope.qc_form.shells_shine == true) {
	    	$scope.qc_form.shells_shine = 1;    
        } else {
	        $scope.qc_form.shells_shine = 0;
        }
        if ($scope.qc_form.shells_canal == true) {
	    	$scope.qc_form.shells_canal = 1;    
        } else {
	        $scope.qc_form.shells_canal = 0;
        }
        if ($scope.qc_form.shells_density == true) {
	    	$scope.qc_form.shells_density = 1;    
        } else {
	        $scope.qc_form.shells_density = 0;
        }
        // END SHELLS

        // FACEPLATE
        if ($scope.qc_form.faceplate_seams == true) {
	    	$scope.qc_form.faceplate_seams = 1;    
        } else {
	        $scope.qc_form.faceplate_seams = 0;
        }
		if ($scope.qc_form.faceplate_shine == true) {
	    	$scope.qc_form.faceplate_shine = 1;    
        } else {
	        $scope.qc_form.faceplate_shine = 0;
        }
		if ($scope.qc_form.faceplate_colors == true) {
	    	$scope.qc_form.faceplate_colors = 1;    
        } else {
	        $scope.qc_form.faceplate_colors = 0;
        }
        if ($scope.qc_form.faceplate_rounded == true) {
	    	$scope.qc_form.faceplate_rounded = 1;    
        } else {
	        $scope.qc_form.faceplate_rounded = 0;
        }
        if ($scope.qc_form.faceplate_foggy == true) {
	    	$scope.qc_form.faceplate_foggy = 1;    
        } else {
	        $scope.qc_form.faceplate_foggy = 0;
        }
        if ($scope.qc_form.faceplate_residue == true) {
	    	$scope.qc_form.faceplate_residue = 1;    
        } else {
	        $scope.qc_form.faceplate_residue = 0;
        }
        // END FACEPLATE

	    // JACKS
        if ($scope.qc_form.jacks_location == true) {
	    	$scope.qc_form.jacks_location = 1;    
        } else {
	        $scope.qc_form.jacks_location = 0;
        }
		if ($scope.qc_form.jacks_debris == true) {
	    	$scope.qc_form.jacks_debris = 1;    
        } else {
	        $scope.qc_form.jacks_debris = 0;
        }
		if ($scope.qc_form.jacks_cable == true) {
	    	$scope.qc_form.jacks_cable = 1;    
        } else {
	        $scope.qc_form.jacks_cable = 0;
        }
        // END JACKS
        
        // PORTS
        if ($scope.qc_form.ports_cleaned == true) {
	    	$scope.qc_form.ports_cleaned = 1;    
        } else {
	        $scope.qc_form.ports_cleaned = 0;
        }
		if ($scope.qc_form.ports_smooth == true) {
	    	$scope.qc_form.ports_smooth = 1;    
        } else {
	        $scope.qc_form.ports_smooth = 0;
        }
        if ($scope.qc_form.ports_glued_correctly == true) {
	    	$scope.qc_form.ports_glued_correctly = 1;    
        } else {
	        $scope.qc_form.ports_glued_correctly = 0;
        }
        if ($scope.qc_form.ports_kinked_tube == true) {
	    	$scope.qc_form.ports_kinked_tube = 1;    
        } else {
	        $scope.qc_form.ports_kinked_tube = 0;
        }
        if ($scope.qc_form.ports_crushed_damper == true) {
	    	$scope.qc_form.ports_crushed_damper = 1;    
        } else {
	        $scope.qc_form.ports_crushed_damper = 0;
        }
        // END PORTS

        // SOUND
        if ($scope.qc_form.sound_signature == true) {
	    	$scope.qc_form.sound_signature = 1;    
        } else {
	        $scope.qc_form.sound_signature = 0;
        }
		if ($scope.qc_form.sound_balanced == true) {
	    	$scope.qc_form.sound_balanced = 1;    
        } else {
	        $scope.qc_form.sound_balanced = 0;
        }
        if ($scope.qc_form.sound_correct_model == true) {
	    	$scope.qc_form.sound_correct_model = 1;    
        } else {
	        $scope.qc_form.sound_correct_model = 0;
        }
        // END SOUND

        // ARTWORK
        if ($scope.qc_form.artwork_none == true) {
	    	$scope.qc_form.artwork_none = 1;    
        } else {
	        $scope.qc_form.artwork_none = 0;
        }
        if ($scope.qc_form.artwork_required == true) {
	    	$scope.qc_form.artwork_required = 1;    
        } else {
	        $scope.qc_form.artwork_required = 0;
        }
        if ($scope.qc_form.artwork_added == true) {
	    	$scope.qc_form.artwork_added = 1;    
        } else {
	        $scope.qc_form.artwork_added = 0;
        }
        if ($scope.qc_form.artwork_placement== true) {
	    	$scope.qc_form.artwork_placement = 1;    
        } else {
	        $scope.qc_form.artwork_placement = 0;
        }
		if ($scope.qc_form.artwork_hq == true) {
	    	$scope.qc_form.artwork_hq = 1;    
        } else {
	        $scope.qc_form.artwork_hq = 0;
        }
        // END ARTWORK
        
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
				$scope.qc_form.shells_defects = 1;
				$scope.qc_form.shells_colors = 1;
				$scope.qc_form.shells_faced_down = 1;
				$scope.qc_form.shells_label = 1;
				$scope.qc_form.shells_edges = 1;
				$scope.qc_form.shells_shine = 1;
				$scope.qc_form.shells_canal = 1;
				$scope.qc_form.shells_density = 1;
	    } else if(category == 'faceplate') {
				$scope.qc_form.faceplate_seams = 1;
				$scope.qc_form.faceplate_shine = 1;
				$scope.qc_form.faceplate_colors = 1;
				$scope.qc_form.faceplate_rounded = 1;
				$scope.qc_form.faceplate_foggy = 1;
				$scope.qc_form.faceplate_residue = 1;
	    } else if(category == 'jacks') {
				$scope.qc_form.jacks_location = 1;
				$scope.qc_form.jacks_debris = 1;
				$scope.qc_form.jacks_cable = 1;
	    } else if(category == 'ports') {
				$scope.qc_form.ports_cleaned = 1;
				$scope.qc_form.ports_smooth = 1;
				$scope.qc_form.ports_glued_correctly = 1;
				$scope.qc_form.ports_kinked_tube = 1;
				$scope.qc_form.ports_crushed_damper = 1;
	    } else if(category == 'sound') {
				$scope.qc_form.sound_signature = 1;
				$scope.qc_form.sound_balanced = 1;
				$scope.qc_form.sound_correct_model = 1;
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
        AppDataService.loadMonitorList(null, null, function (result) {
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

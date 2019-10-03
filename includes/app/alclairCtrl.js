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
					 	window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/" + id_of_qc_form;
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
                       window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
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
            window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
            
        }

    }
        
    $scope.SaveData = function (saveORship, uploaddocument) {
        
        // INITIALIZE NUMBERIC VALUES HERE IF EMPTY 

        
        // END ARTWORK
        // END SHIPPING -> PORTS
       
		if(saveORship == 'SAVE') {
	        	var api_url = window.cfg.apiUrl + 'alclair/add.php';
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
				
				$scope.qc_form.faceplate_glued_correctly = 1;
				$scope.qc_form.faceplate_kinked_tube = 1;
				$scope.qc_form.faceplate_crushed_damper = 1;
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
					 	window.location.href = window.cfg.rootUrl + "/alclair/edit_qc_form/" + id_of_qc_form;
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
        var api_url = window.cfg.apiUrl + "alclair/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PASS_OR_FAIL=" + $scope.pass_or_fail + "&MonitorID=" + $scope.monitor_id + "&BuildTypeID=" + $scope.build_type_id;
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

        $http.get(window.cfg.apiUrl + "alclair/delete.php?id=" + id).success(function (result) {
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
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        
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
							}, 1000); 			 
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
							}, 2000); 
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
								}, 1000); 
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

swdApp.controller('Repair_Form', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.repair_form = {
 	ticket_number: '',
    artwork_none: 0,
	repair_date: new Date,
	received_date: window.cfg.CurrentDay,
	estimated_ship_date: window.cfg.CurrentDay_plus_2weeks,
};

	$scope.faults = [];
    $count_faults = 0;
    $scope.newFault = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.faults.push({});
        $scope.faults[$count_faults] = {
			classification: 'Sound',
			description_id:1,
			fault_sound: 1,
			fault_fit: 1,
			fault_design: 1,
		}
        $count_faults = $count_faults + 1;
    }
    $scope.removeFault = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.faults.pop({});
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
            
    $scope.SaveThenPrintTraveler = function (saveORship, uploaddocument) {
        
        if($scope.repair_form.estimated_ship_date)
        	$scope.repair_form.estimated_ship_date = $scope.repair_form.estimated_ship_date.toLocaleString();
		if($scope.repair_form.received_date)
			$scope.repair_form.received_date = $scope.repair_form.received_date.toLocaleString();  
			
        var api_url = window.cfg.apiUrl + 'alclair/rma_add.php';
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

		console.log("Then est ship is " + $scope.repair_form.estimated_ship_date)
        var api_url = window.cfg.apiUrl + 'alclair/rma_add.php';
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
             console.log("Test is " + result.test)

			 $scope.id_of_repair = result.id_of_repair;
             $scope.addFaults($scope.id_of_repair);
                          
             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 //if (result.data.id !=undefined)
                 {
                     //$scope.repair_form.id = result.data.id;
                     toastr.success("Form saved successfully.");
                     window.location.href = window.cfg.rootUrl + "/alclair/repair_list/";

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
			//console.log("Length is  " + $scope.faults.length)
			//console.log("Repair ID is still " + id_of_repair)
			console.log("Description ID is " + $scope.faults[i].description_id)
			var api_url = window.cfg.apiUrl + 'alclair/add_faults.php?id_of_repair=' + id_of_repair;
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
    
    
    $scope.init=function()
    {
	    
	    var api_url = window.cfg.apiUrl + "alclair/get_next_id_from _from_repair_form.php";

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            //console.log(result.data);
			$scope.repair_form.rma_number = result.next_id;
        }).error(function () {
            $.unblockUI();
            toastr.error("Error!");
        });


        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        
        $scope.FAULT_TYPES = AppDataService.FAULT_TYPES;
        
        AppDataService.loadSoundFaultsList(null, null, function (result) {
           $scope.soundFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadFitFaultsList(null, null, function (result) {
           $scope.fitFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadDesignFaultsList(null, null, function (result) {
           $scope.designFaultsList = result.data;
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
    

    $scope.faults = [];
    var faults_IDs = [];
    $scope.newFault = function($event){
        // prevent submission
        $count_faults = $scope.faults.length;
        $event.preventDefault();
        $scope.faults.push({});
        $scope.faults[$count_faults] = {
			classification: 'Sound',
			description_id:1,
			fault_sound: 1,
			fault_fit: 1,
			fault_design: 1,
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
			var api_url = window.cfg.apiUrl + 'alclair/add_faults.php?id_of_repair=' + id_of_repair;
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
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF_repair.php?ID=" + id;

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
					
					var api_url = window.cfg.apiUrl + 'alclair/update_fault.php'; //?id=' + faultS_IDs[m];
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
					$http.get(window.cfg.apiUrl + "alclair/delete_fault.php?id=" + faults_IDs[k]).success(function (result) {
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
				
				var api_url = window.cfg.apiUrl + 'alclair/add_faults.php?id_of_repair=' + id_of_repair;
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
		var api_url = window.cfg.apiUrl + 'alclair/update_repair_form.php';
		        
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
					
					var api_url = window.cfg.apiUrl + 'alclair/update_fault.php'; //?id=' + faultS_IDs[m];
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
					$http.get(window.cfg.apiUrl + "alclair/delete_fault.php?id=" + faults_IDs[k]).success(function (result) {
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
				
				var api_url = window.cfg.apiUrl + 'alclair/add_faults.php?id_of_repair=' + id_of_repair;
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
        var api_url = window.cfg.apiUrl + 'alclair/update_repair_form.php';
       	
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
				 window.location.href = window.cfg.rootUrl + "/alclair/repair_list/";
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
		var api_url = window.cfg.apiUrl + 'alclair/move_to_manufacturing_screen_repair.php?id=' + window.cfg.Id;
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
		var api_url = window.cfg.apiUrl + 'alclair/remove_from_manufacturing_screen_repair.php?id=' + window.cfg.Id;
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
        var api_url = window.cfg.apiUrl + "alclair/get_repair_forms.php?id=" + window.cfg.Id;
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
        if ($scope.repair_form.repaired_shell == true) {
	    	$scope.repair_form.repaired_shell = 1;    
        } else {
	        $scope.repair_form.repaired_shell = 0;
        }
        if ($scope.repair_form.repaired_shell_left == true) {
	    	$scope.repair_form.repaired_shell_left = 1;    
        } else {
	        $scope.repair_form.repaired_shell_left = 0;
        }
		if ($scope.repair_form.repaired_faceplate == true) {
	    	$scope.repair_form.repaired_faceplate = 1;    
        } else {
	        $scope.repair_form.repaired_faceplate = 0;
        }
        if ($scope.repair_form.repaired_faceplate_left == true) {
	    	$scope.repair_form.repaired_faceplate_left = 1;    
        } else {
	        $scope.repair_form.repaired_faceplate_left = 0;
        }
		if ($scope.repair_form.repaired_jacks == true) {
	    	$scope.repair_form.repaired_jacks = 1;    
        } else {
	        $scope.repair_form.repaired_jacks = 0;
        }
        if ($scope.repair_form.repaired_jacks_left == true) {
	    	$scope.repair_form.repaired_jacks_left = 1;    
        } else {
	        $scope.repair_form.repaired_jacks_left = 0;
        }
        if ($scope.repair_form.replaced_drivers == true) {
	    	$scope.repair_form.replaced_drivers = 1;    
        } else {
	        $scope.repair_form.replaced_drivers = 0;
        }
        if ($scope.repair_form.replaced_drivers_left == true) {
	    	$scope.repair_form.replaced_drivers_left = 1;    
        } else {
	        $scope.repair_form.replaced_drivers_left = 0;
        }
        
        if ($scope.repair_form.new_shells == true) {
	    	$scope.repair_form.new_shells = 1;    
        } else {
	        $scope.repair_form.new_shells = 0;
        }
        if ($scope.repair_form.new_shells_left == true) {
	    	$scope.repair_form.new_shells_left = 1;    
        } else {
	        $scope.repair_form.new_shells_left = 0;
        }
        if ($scope.repair_form.replaced_tubes == true) {
	    	$scope.repair_form.replaced_tubes = 1;    
        } else {
	        $scope.repair_form.replaced_tubes = 0;
        }
        if ($scope.repair_form.replaced_tubes_left == true) {
	    	$scope.repair_form.replaced_tubes_left = 1;    
        } else {
	        $scope.repair_form.replaced_tubes_left = 0;
        }
        if ($scope.repair_form.cleaned == true) {
	    	$scope.repair_form.cleaned = 1;    
        } else {
	        $scope.repair_form.cleaned = 0;
        }
        if ($scope.repair_form.cleaned_left == true) {
	    	$scope.repair_form.cleaned_left = 1;    
        } else {
	        $scope.repair_form.cleaned_left = 0;
        }
        if ($scope.repair_form.adjusted_fit == true) {
	    	$scope.repair_form.adjusted_fit = 1;    
        } else {
	        $scope.repair_form.adjusted_fit = 0;
        }
        if ($scope.repair_form.adjusted_fit_left == true) {
	    	$scope.repair_form.adjusted_fit_left = 1;    
        } else {
	        $scope.repair_form.adjusted_fit_left = 0;
        }
        // END REPAIR PERFORMED STUFF

                    
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

        var api_url = window.cfg.apiUrl + 'alclair/update_rma_performed.php?id=' + id;
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
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadRepairStatusTableList(null, null, function (result) {
           $scope.RepairStatusList = result.data;
        }, function (result) { });
        
        $scope.FAULT_TYPES = AppDataService.FAULT_TYPES;
        
        AppDataService.loadSoundFaultsList(null, null, function (result) {
           $scope.soundFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadFitFaultsList(null, null, function (result) {
           $scope.fitFaultsList = result.data;
        }, function (result) { });
        AppDataService.loadDesignFaultsList(null, null, function (result) {
           $scope.designFaultsList = result.data;
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
				window.location.href = window.cfg.rootUrl + "/alclair/edit_repair_form/" + id_of_order; 
		}, 500); 
	};    
    $scope.repair_form = {
        cust_name: '',
        pass_or_fail: '0',
        repair_date: new Date,
    };

	$scope.customers = [];
	$scope.getCustomers = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_customers_repairs.php";
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
        var api_url = window.cfg.apiUrl + "alclair/get_repair_forms.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+ "&MonitorID=" + $scope.monitor_id + "&REPAIRED_OR_NOT="+$scope.repaired_or_not + "&REPAIR_STATUS_ID=" + $scope.repair_status_id;
        
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

        $http.get(window.cfg.apiUrl + "alclair/deleteRepairForm.php?id=" + id).success(function (result) {
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
        var api_url = window.cfg.apiUrl + "alclair/move_repair_to_done.php?ID=" + $scope.id_to_make_done +"&DoneDate="+moment($scope.done_date).format("MM/DD/YYYY");

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
        AppDataService.loadMonitorList(null, null, function (result) {
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

swdApp.controller('importWooCommerce', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }

    
    $scope.UploadData = function () {
        var api_url = window.cfg.apiUrl + 'alclair/import.php';
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
	               console.log("Test is " + data.test)
	               console.log("Testing3 is " + data.testing3)
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   console.log("Testing1 is " + data.testing1)
					   console.log("Testing2 is " + data.testing2)
					   console.log("Testing3 is " + data.testing3)
					   //console.log("Testing4 is " + data.testing4)
					   //console.log("Testing5 is " + data.testing5)

					    console.log("Message is " + data.message)
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

swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
     document.getElementById("barcode_orders").oninput = function() {myFunction()};
		
	function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("barcode_orders").value;
				//pos = x.indexOf("-");
				//id_of_qc_form = x.slice(0,pos);
				id_of_order = x;
				console.log("The id is " + id_of_order)
				//var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + id_of_order;
				window.location.href = window.cfg.rootUrl + "/alclair/edit_traveler/" + id_of_order; 
		}, 500); 
	};
    
    $scope.open_add_order = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair/add_order/";
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
    
    $scope.qc_form = {
        cust_name: '',
        pass_or_fail: '0',
        qc_date: new Date,
    };

	$scope.customers = [];
	$scope.getDesignedFor = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_designed_for.php";
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
		
		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			console.log("DEFINED Impression checked " + $scope.use_impression_date)	
			$scope.use_impression_date = 1;
		}
		
        var api_url = window.cfg.apiUrl + "alclair/get_orders.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&ORDER_STATUS_ID=" + $scope.order_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not + "&USE_IMPRESSION_DATE=" + $scope.use_impression_date;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	            
                $scope.OrdersList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
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
	    $scope.getDesignedFor();
	    $scope.PRINTED_OR_NOT = AppDataService.PRINTED_OR_NOT;
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        $scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.orderStatusTableList = result.data;
        }, function (result) { });
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
	 	hearing_protection: 0,
	 	musicians_plugs: 0,
	 	musicians_plugs_9db: 0,
	 	musicians_plugs_15db: 0,
	 	musicians_plugs_25db: 0,
	 	pickup: 0,
	 	override: 1,
	};
    $scope.selectedFiles = [];
    
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
		$scope.traveler.estimated_ship_date = window.cfg.CurrentDay_plus_2weeks;
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
                          
			 	if (result.code == "success") {
                		$.unblockUI();
						//alert(result.data.id);
						//if (result.data.id !=undefined)
					{
                    	 	//$scope.repair_form.id = result.data.id;
						//toastr.success("Form saved successfully.");
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
		} else {
			var api_url = window.cfg.apiUrl + 'alclair/update_traveler.php?id=' + window.cfg.id;
		}
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
	         console.log("TEST IS " + result.test)
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
                 console.log("In here for an error.")
                 console.log("Test is " + result.test)
                 console.log("Code is " + result.code)
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
	           console.log("THE USER IS " + result.the_user_is)
	           
	           if(result.data[0].manufacturing_screen == true) {
			   		$scope.manufacturing_screen = 1; 
	           } else {
		           $scope.manufacturing_screen = 0;
	           }
	           console.log("THE SCREEN IS  " + $scope.traveler.manufacturing_screen)
	           $scope.the_user_is = result.the_user_is;
	            //console.log("Log is " + JSON.stringify(result.data2))
	            //console.log("The data is " + JSON.stringify(result.data[0]))
	            //console.log("Test is " + result.test)
	            //console.log("Test 2 is " + result.test2)
                if (result.data.length > 0) {
                    $scope.traveler = result.data[0];   
                    $scope.traveler.override = 1;      
                    order_status_id = $scope.traveler.order_status_id;
                    console.log("Order Status ID is " + order_status_id)
					
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
       
       		            
		            // GREATER OR EQUAL TO 10 IS STUDIO3, STUDIO4, REV X & ELECTRO
		            // THIS IF STATEMENT ALTERES THE DROPDOWN FOR THE TYPE/COLOR OF CABLE
		            if($scope.traveler.monitor_id >= 10) {
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
			 		if ($scope.traveler.rush_process == 'Yes') {
				 		$scope.traveler.rush_process = 1;    
			 		} else {
			 			$scope.traveler.rush_process = 0;
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

swdApp.controller('add_Order', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
 $scope.Ready2Ship = "NO";
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
	artwork: 'None',
	cable_color: '0',
	additional_items: 0,
	consult_highrise: 0,
	international: 0,
	hearing_protection: 0,
	hearing_protection_color: '0',
	musicians_plugs: 0,
	musicians_plugs_9db: 0,
	musicians_plugs_15db: 0,
	musicians_plugs_25db: 0,
	pickup: 0,
	date: window.cfg.CurrentDay,
};

 $scope.traveler.date=window.cfg.CurrentDay;
 $scope.CableTypeList = AppDataService.CableTypeList;
 $scope.ArtworkTypeList = AppDataService.ArtworkTypeList;
 $scope.HEARING_PROTECTION_COLORS = AppDataService.HEARING_PROTECTION_COLORS;
	
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
            
    $scope.Save = function () {
        //console.log("HERE IT IS" + $scope.traveler.received_date)
        if($scope.traveler.date)
        	$scope.traveler.date = $scope.traveler.date.toLocaleString();
        if($scope.traveler.estimated_ship_date)
        	$scope.traveler.estimated_ship_date = $scope.traveler.estimated_ship_date.toLocaleString();
		if($scope.traveler.received_date)
			$scope.traveler.received_date = $scope.traveler.received_date.toLocaleString();
			
		if (!$scope.traveler.impression_color_id ) {
			$scope.traveler.impression_color_id = 0;
		}
		
		if($scope.traveler.hearing_protection == 1 ) {
			if ($scope.traveler.hearing_protection_color == '0' ) {
				toastr.error("Please select a color for the hearing protection.")
				return;
			}
		}

		if ($scope.traveler.hearing_protection == '0' ) {
			$scope.traveler.hearing_protection_color = '0';
			console.log("Color is " + $scope.traveler.hearing_protection_color)
		}

        var api_url = window.cfg.apiUrl + 'alclair/add_order.php';

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
             if(result.message == "Something is incomplete") {
	             toastr.error("Cannot ship product.  Please check that everything has passed QC.");
	             $.unblockUI();
             }
             else {            
             	if (result.code == "success") {
                 	$.unblockUI();
				 	//alert(result.data.id);
				 	//if (result.data.id !=undefined)
				 	{
                    	 $scope.traveler.id = result.data.id;
						 //window.location.href = window.cfg.rootUrl + "/alclair/orders/";
						 location.reload();				 	
                 	}
				 	//else
				 	{
                     
                 	}
				 	//redirect
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
    
    $scope.Print_Traveler = function () {		
		 //console.log("HERE IT IS" + $scope.traveler.received_date)
        if($scope.traveler.date)
        	$scope.traveler.date = $scope.traveler.date.toLocaleString();
        if($scope.traveler.estimated_ship_date)
        	$scope.traveler.estimated_ship_date = $scope.traveler.estimated_ship_date.toLocaleString();
		if($scope.traveler.received_date)
			$scope.traveler.received_date = $scope.traveler.received_date.toLocaleString();
			
		if (!$scope.traveler.impression_color_id ) {
			$scope.traveler.impression_color_id = 0;
		}
		
		if($scope.traveler.hearing_protection == 1 ) {
			if ($scope.traveler.hearing_protection_color == '0' ) {
				toastr.error("Please select a color for the hearing protection.")
				return;
			}
		}
		
		if ($scope.traveler.hearing_protection == '0' ) {
			$scope.traveler.hearing_protection_color = '0';
		}

        var api_url = window.cfg.apiUrl + 'alclair/add_order.php';
        		
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
	         console.log("Test 1 is " + result.test1)
	         console.log("Test 1 is " + result)
             if (result.code == "success") {
	             id_to_print = result.id_of_order;
			 	 $scope.PDF(id_to_print);
                 $.unblockUI();
                 toastr.success(result.message)
                 setTimeout(function(){
					location.reload();				 	
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

    
              
    $scope.init=function()
    {

        AppDataService.loadMonitorList_not_Universals(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadImpressionColorList(null, null, function (result) {
           $scope.impressionColorList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('Orders_Done_By_Date', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
	
	$scope.customers = [];
	$scope.getDesignedFor = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_designed_for.php";
	        $http.get(api_url).success(function (data) {
	            $scope.customers = data.data;
	    })
	}
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
     document.getElementById("barcode_orders").oninput = function() {myFunction()};
		
	function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("barcode_orders").value;
				//pos = x.indexOf("-");
				//id_of_qc_form = x.slice(0,pos);
				id_of_order = x;
				console.log("The id is " + id_of_order)
				//var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + id_of_order;
				window.location.href = window.cfg.rootUrl + "/alclair/edit_traveler/" + id_of_order; 
		}, 500); 
	};
    
    $scope.open_add_order = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair/add_order/";
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
	//$scope.SearchStartDate=window.cfg.TwoMonthsPrior;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
	//console.log("Date is " + window.cfg.OctoberOne)
	//console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
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
		
		//console.log("rush is " + $scope.order_status_id)
        var api_url = window.cfg.apiUrl + "alclair/get_orders_done_by_date.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&ORDER_STATUS_ID=" + $scope.order_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //console.log("Testing is " + result.test)
	            //console.log("Test2 is " + result.test2)
	            
                $scope.OrdersList = result.data;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                //console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
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
	    $scope.getDesignedFor();
	    $scope.PRINTED_OR_NOT = AppDataService.PRINTED_OR_NOT;
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        //if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        //if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        //if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        //$scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.orderStatusTableList = result.data;
        }, function (result) { });
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
        
        var api_url = window.cfg.apiUrl + "alclair/change_done_date.php?ID=" + $scope.id_to_make_done +"&DoneDate=" + moment($scope.done_date).format("MM/DD/YYYY");
		//console.log("api url is " + api_url)
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Test1 is " + result.test1);
			//toastr.success("Successfully moved to Done.");
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

swdApp.controller('Repairs_Done_By_Date', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Traveler";
	$scope.printed_or_not = '0';
	
	$scope.customers = [];
	$scope.getDesignedFor = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_designed_for.php";
	        $http.get(api_url).success(function (data) {
	            $scope.customers = data.data;
	    })
	}
    
    //SearchStartDate = "10/1/2017";
    //SearchEndDate = new Date();
    
     document.getElementById("barcode_orders").oninput = function() {myFunction()};
		
	function myFunction() {
		setTimeout(function(){
			var x = document.getElementById("barcode_orders").value;
				//pos = x.indexOf("-");
				//id_of_qc_form = x.slice(0,pos);
				id_of_order = x;
				console.log("The id is " + id_of_order)
				//var api_url = window.cfg.apiUrl + "alclair/get_orders.php?id=" + id_of_order;
				window.location.href = window.cfg.rootUrl + "/alclair/edit_traveler/" + id_of_order; 
		}, 500); 
	};
    
    $scope.open_add_order = function () {
	 	window.location.href = window.cfg.rootUrl + "/alclair/add_order/";
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
	//$scope.SearchStartDate=window.cfg.TwoMonthsPrior;
	$scope.SearchEndDate=window.cfg.CurrentDay;
	$scope.done_date=window.cfg.CurrentDay;
	$scope.id_to_make_done = 0;
	//console.log("Date is " + window.cfg.OctoberOne)
	//console.log("Date2 is " + window.cfg.CurrentMonthFirstDate)
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
		
		//console.log("rush is " + $scope.order_status_id)
        var api_url = window.cfg.apiUrl + "alclair/get_repairs_done_by_date.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&REPAIR_STATUS_ID=" + $scope.repair_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //console.log("Testing is " + result.test)
	            console.log("Test2 is " + JSON.stringify(result.data[0]))
	            
                $scope.RepairsList = result.data;
                //$scope.OrdersList.num_done = result.data3;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                //console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
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


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this repair?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "alclair/delete_repair.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.RepairsList.length; i++) {
	            console.log("Test is " + result.test)
                if ($scope.RepairsList[i].id == id) {
                    toastr.success("Delete Repair successful!", "Message");
                    $scope.RepairsList.splice(i, 1);
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
	
	$scope.customers = [];
	$scope.getCustomers = function () {
	    var api_url = window.cfg.apiUrl + "alclair/get_customers.php";
	        $http.get(api_url).success(function (data) {
	            $scope.customers = data.data;
	    })
	}
       
    $scope.init = function () {
	    $scope.getDesignedFor();
	    $scope.getCustomers();
	    $scope.PRINTED_OR_NOT = AppDataService.PRINTED_OR_NOT;
        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        //if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        //if (isEmpty($scope.cust_name)) $scope.cust_name = "";
        //if (isEmpty($scope.qc_form.cust_name)) $scope.qc_form.cust_name = "";
        //$scope.PASS_OR_FAIL = AppDataService.PASS_OR_FAIL;
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadBuildTypeList(null, null, function (result) {
           $scope.buildTypeList = result.data;
        }, function (result) { });
        AppDataService.loadOrderStatusTableList(null, null, function (result) {
           $scope.orderStatusTableList = result.data;
        }, function (result) { });
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
        console.log("Repair # is " +  id)
        var api_url = window.cfg.apiUrl + "alclair/travelerPDF_repair.php?ID=" + id;

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
        console.log("The ID to make done is " + $scope.id_to_make_done)
        var api_url = window.cfg.apiUrl + "alclair/change_repair_done_date.php?ID=" + $scope.id_to_make_done +"&DoneDate=" + moment($scope.done_date).format("MM/DD/YYYY");
		//console.log("api url is " + api_url)
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log("Test1 is " + result.test1);
			//toastr.success("Successfully moved to Done.");
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
	
	swdApp.controller('Manufacturing_Screen_1', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    		
		
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
		
		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			console.log("DEFINED Impression checked " + $scope.use_impression_date)	
			$scope.use_impression_date = 1;
		}
		
        var api_url = window.cfg.apiUrl + "alclair/manufacturing_screen_1.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Test is " + result.test)
	            
              $scope.OrdersList = result.data;
              $scope.Shipped_Last_Year = result.Shipped_Last_Year;
  			 $scope.Shipped_Last_Year_This_Month = result.Shipped_Last_Year_This_Month;
			 $scope.Shipped_This_Year = result.Shipped_This_Year;
			 $scope.Shipped_This_Month = result.Shipped_This_Year_This_Month;
		  	 $scope.Shipped_Last_Month = result.Shipped_This_Year_Last_Month;
				
			$scope.this_year = result.this_year;
			$scope.last_year = result.last_year;
			$scope.this_month =  result.this_month.toUpperCase();
			$scope.last_month =  result.last_month.toUpperCase();
				
			$scope.avg = result.avg;           
             $scope.avg_repairs = result.avg_repairs;  
             $scope.orders_shipped_yesterday = result.orders_shipped_yesterday;
             $scope.orders_shipped_today = result.orders_shipped_today;
                
                //$scope.QC_Form = result.customer_name;
                $scope.TotalPages = result.TotalPages;
                //console.log("Num of pages " + result.TotalPages)
                $scope.TotalRecords = result.TotalRecords;
                $scope.Printed = result.Printed;
                //console.log("Pass or Fail is " + result.testing1)
                
                setTimeout(function(){
				  	window.location.href = window.cfg.rootUrl + "/admin/manufacturing_screen_2";
				  }, 300000); 

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
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {
        $scope.LoadData();
    }
    			        
    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
      
    }
            
    $scope.init();
}]);

swdApp.controller('Manufacturing_Screen_2', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
	
	$scope.labels5 = [];
	$scope.labelRange5 = [];
   
   $scope.monthRange = AppDataService.monthRange;
		
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
        
    $scope.loadImpressionsReceived = function () {
       myblockui();
		
		$scope.year_month = '2019';
		$scope.month_month = '05';
		//$scope.year_month = moment().format("YYYY");
		//$scope.month_month = moment().format("MM")
		console.log("Year is " + $scope.year_month + " and Month is " + $scope.month_month)
        var api_url = window.cfg.apiUrl + "reports/manufacturing_screen2.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result5) {

            $.unblockUI();
            //console.log(JSON.stringify(result5.data));
            //console.log("data length is " + result.data.length)
            if (result5.data.length == 0)
                return;
            $scope.labelRange5 = [];
            
            console.log("TESTING is " + result5.data.length)

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
			 //$scope.labels5 = "Count"; 
            var layers5 = [];
            /*for (var i = 0; i < $scope.labels5.length; i++) {
                var layer5 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer5.push({ y: 0 });
                }
                layers5.push(layer5);
            }*/
            for (var i = 0; i < 24; i++) { //$scope.labels5.length; i++) {
                var layer5 = [];
                for (var j = 0; j < 13; j++) {
                    layer5.push({ y: 0 });
                }
                layers5.push(layer5);
            }
			//console.log("LAYERS " + layers5[1][1].y + " and J is " +j)
            //var layers5 = [];
            //layers5 = ['1', '2' , '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
            
            console.log("Length is " + result5.data.length)
            var step = 0;
            for (var i = 0; i < result5.data.length; i++) {
				//console.log("TESTING " + result5.data[i].the_month_name)
				if(i == result5.data.length-1 && result5.data[i].the_year != '2019') {
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name);
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
					
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name);
					var pass_or_fail5 = '2019';
					var num_status5 = 0;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
				} else if(i > 0 && result5.data[i].the_year == '2018' && result5.data[i-1].the_year == '2018') {
	              var created5 = parseInt(result5.data[i-1].the_month);
	              //var created5 = (result5.data[i-1].the_month_name);
					var pass_or_fail5 = '2019';
					var num_status5 = 0;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
					
					var created5 = parseInt(result5.data[i].the_month);
					//var created5 = (result5.data[i].the_month_name)
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+"-"+pass_or_fail5+"-"+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
                } else {
	               var created5 = parseInt(result5.data[i].the_month);
	               //var created5 = (result5.data[i].the_month_name);
					var pass_or_fail5 = result5.data[i].the_year;
					var num_status5 = result5.data[i].num_in_month;
					console.log("I is " + i + " and " + created5+" year is "+pass_or_fail5+" num is "+num_status5 )
					layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
                }
            }

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels5.length; i++) {
                $scope.labelRange5.push({ text: $scope.labels5[i], color: color(i) });
            }
			//layers5 = [{'month':'Jan', '2018':11, '2019': 55}, {'month':'Jan', '2018':33, '2019': 2}] 			
            d3DarwStackGroup(layers5, "impressions_received_date", "grouped_impression_date", "stacked_impression_date", $scope.labels5, { category20: true });

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.CustomBarChart = function () {
       myblockui();
		
		console.log("Year is " + $scope.year_month + " and Month is " + $scope.month_month)
        var api_url = window.cfg.apiUrl + "reports/manufacturing_screen2.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result5) {

            $.unblockUI();
            
            var dataset = result5.num_in_month;
            var the_x_axis = result5.the_month_name;
            console.log("Stuff is " + the_x_axis)
            
            	var svgWidth = 1600, svgHeight = 800, barPadding = 10;
            	var shift_x_from_0 = 75;
			var barWidth = svgWidth / dataset.length;
			var svg = d3.select('svg')
		    		.attr("width", svgWidth+300)
				.attr("height", svgHeight+300);
			
			var xScale = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([0, svgWidth]);
				
			var xScale_axis = d3.scaleLinear()
				.domain([0, 24])
				.range([0, svgWidth]);
				    
			var yScale_axis = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([svgHeight, 0]);
			
			var yScale = d3.scaleLinear()
				.domain([0, d3.max(dataset)])
				.range([0, svgHeight]);
				
			var x_axis = d3.axisBottom().scale(xScale_axis);
			var y_axis = d3.axisLeft().scale(yScale_axis);
					  	
			var d = new Date();  
			var month_number = d.getMonth();
			var barChart = svg.selectAll("rect")
			    .data(dataset)
			    .enter()
			    .append("rect")
			    .attr("y", function(d) {
			         return svgHeight - yScale(d)  
			    })
			    .attr("height", function(d) { 
			        return yScale(d); 
			    })
			    .attr("width", barWidth - barPadding)
			    .attr("class", "bar")
			    //.style("fill","teal")
			    .style("fill", function(d, i) {
				    var bar_num = 12/i;
				    console.log("I is " + i*255/month_number)
					return "rgb(0, 0," + (255-(i*255/month_number))+" )";
				})
			    .attr("transform", function (d, i) {
			        var translate = [barWidth * i + shift_x_from_0, 0]; 
			        return "translate("+ translate +")";
			    });
			
			// TEXT ON TOP OF BARS
			var text = svg.selectAll("text")
			    .data(dataset)
			    .enter()
			    .append("text")
			    .text(function(d) {
			        return d;
			    })
			    .attr("y", function(d, j) {
			        return svgHeight - yScale(d) + 60; // -6 MAKES TEXT SIT 2 PIXELS ABOVE BAR
			    })
			    .attr("x", function(d, j) {
			        return barWidth * j  + 15 + barWidth/2; // 15 TO CENTER TEXT ON BAR
			    })
			    .attr("fill", "white")
			    .attr("font-size", 60);	 
			    
			var data = the_x_axis;
			var xScaleLabels = d3
			  .scalePoint()
			  .domain(data)
			  .rangeRound([50, svgWidth - barWidth/2]); // In pixels
			
			var axisTop2 = d3
			  .axisBottom()
			  .scale(xScaleLabels)
			  .ticks(data.length);
			
			var xAxisTranslate = svgHeight + 0;
			svg
			  .append("g")
			  .call(axisTop2)
			  .attr("transform", "translate(" + 54 + "," + xAxisTranslate + ")")
			  .selectAll("text")	
			  .style("text-anchor", "end")
			  .attr("dx", 0)
			  .attr("dy", barWidth/4)
             .attr("font-size", 40)
			  .attr("transform", "rotate(-65)");

         
			// X-AXIS
			/*
			svg.append("g")
				.attr("transform", "translate(54, " + xAxisTranslate   +")")
				.call(x_axis)
				.attr("font-size", 40);
			*/
			// Y-AXIS    
			svg.append("g")
				.attr("transform", "translate(" + shift_x_from_0 + ", 0)")
				.call(y_axis)
				.attr("font-size", 40);
				
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
$scope.LoadData = function () {
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);

		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);

		if($scope.use_impression_date != 1) {
			$scope.use_impression_date = 0;
		} else {
			$scope.use_impression_date = 1;
		}

        var api_url = window.cfg.apiUrl + "alclair/manufacturing_screen_2.php";
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {

	          //$scope.loadImpressionsReceived(); 
	          $scope.CustomBarChart();        
              $scope.OrdersList = result.data;     
              $scope.avg = result.avg;           
              $scope.avg_repairs = result.avg_repairs;  
              $scope.orders_shipped_yesterday = result.orders_shipped_yesterday;
              console.log("Day is " + result.minus_day);   
              console.log("Minute is " + result.minus_minute);


                setTimeout(function(){
				  	window.location.href = window.cfg.rootUrl + "/admin/manufacturing_screen_1";
				  }, 30000); 

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


    $scope.init = function () {
	    $scope.labels5.push('2018');
	    $scope.labels5.push('2019');
	    /*
	    AppDataService.loadStatusTypeList_orders(null, null, function (result) {
        		for (var i = 0; i < result.data.length; i++) {
				$scope.labels5.push(result.data[i].type);
        		}    
    		}, function () { });
    		*/

        //$scope.loadImpressionsReceived(); 
        //$("#impressions_received_date").html("");
        $scope.LoadData();
    }

    $scope.openDone = function ($event) {        
        $scope.openedDone = true;
    };

    $scope.LoadSelectDateModal=function(id) {
        $('#SelectDateModal').modal("show");   
        $scope.id_to_make_done = id;
    }

    $scope.init();
}]);

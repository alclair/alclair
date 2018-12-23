swdApp.controller('addInventoryCtrl', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.inventory = {
 	import_type: 'Return',
};
$scope.sound_off = 0;

$scope.clickText = function () {
	var audio = new Audio(window.cfg.apiUrl + 'ifi/just-like-magic.mp3');
	audio.play();
}

	$scope.loadRecordAddModal = function () {
        $("#modalAddRecord").modal("show");
    };   
    
    /*
    var x1 = document.getElementById("start_1");
    if (x1) {
	    console.log("X1 is " + x1)
    } else {
	    console.log("X1 is and not null" + x1)
    }
		console.log("Checkbox is " + $scope.import_entry_type)	
	*/	
		document.getElementById("start_1").oninput = function() {myFunction()};
		document.getElementById("start_2").oninput = function() {myFunction()};
		document.getElementById("start_3").oninput = function() {myFunction()};
		document.getElementById("start_4").oninput = function() {myFunction()};      
    /*
    $scope.checkboxClicked = function() {
		document.getElementById("start_1").oninput = function() {myFunction()};
		document.getElementById("start_2").oninput = function() {myFunction()};
		document.getElementById("start_3").oninput = function() {myFunction()};
		document.getElementById("start_4").oninput = function() {myFunction()};    
    }*/

	
	function myFunction() {
		 setTimeout(function(){
			var x = document.getElementById("start_1").value;
			console.log(x)
			//document.getElementById("demo").innerHTML = "You wrote: " + x;
			if($scope.sound_off == 0) {
				$scope.check4CorrectPrefixes();
				$scope.NumOfSNs();
			}
		}, 300); 
	};
	
	$scope.check4CorrectPrefixes = function () {
		myblockui();
        var api_url = window.cfg.apiUrl + 'ifi_uk/check_prefixes_uk.php';
        $http({
            method: 'POST',
			url: api_url,
			data: $.param($scope.inventory),
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
       .success(function (data) {
	        //console.log(data);
			if (data.make_a_beep == "Yes") {
				var audio = new Audio(window.cfg.apiUrl + 'ifi/just-like-magic.mp3');
				audio.play();
			}
			$.unblockUI();
		})
        .error(function (data) {
			console.log("Stuck here 2nd Else")
			$.unblockUI();
		}); 
	}
	/*$scope.TESTING = function () {
		console.log("Import type is " + $scope.inventory.import_type)
		console.log("Received From is " + $scope.inventory.received_from)
		if($scope.inventory.received_from == undefined) {
			$scope.inventory.received_from = "field left blank";
			console.log("It is now " + $scope.inventory.received_from)	
		}
		if ($scope.inventory.import_type == 'Return') {
			$scope.inventory.import_type = 4;
		} else if ($scope.inventory.import_type == 'Replenish') {
			$scope.inventory.import_type = 5;
		}
	}*/
	
	 $scope.NumOfSNs = function () {
	    //console.log("dsafasdfasd" + $scope.qrcode.barcode)
        myblockui();
        var api_url = window.cfg.apiUrl + 'ifi_uk/get_num_sn_in_import_uk.php';
        //alert(api_url);
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.inventory),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
               .success(function (data) {
	               //console.log(data);
                   if (data.code == "success") {
	                   $scope.total_sns = data.total_sns;
	                   $scope.total_new_sns = data.total_new_sns;
	                   $scope.total_demo_sns = data.total_demo_sns;
	                   $scope.total_amazon_sns = data.total_amazon_sns;
	                   $scope.total_faulty_sns = data.total_faulty_sns;
                       //toastr.success("Imported successfully.");
					   //console.log("T " + JSON.stringify(data.test1))
                   }
                   else {
	                   //console.log("Code is " + data.code)
	                   console.log("Failed " + data.test)
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
	               console.log("Stuck here 2nd Else")
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               }); 
    };

    $scope.Import = function () {
	    if ($scope.inventory.import_type == 'Return') {
			$scope.inventory.import_type = 4;
		} else if ($scope.inventory.import_type == 'Replenish') {
			$scope.inventory.import_type = 5;
		}
		if($scope.inventory.received_from == undefined || $scope.inventory.received_from.length < 2) {
			$scope.inventory.received_from = " ";
		}
		//console.log("# of SEALED is " + $scope.inventory.sealed.length)
		//console.log("# of AMAZON is " + $scope.inventory.amazon.length)
        var api_url = window.cfg.apiUrl + 'ifi_uk/prefixes_uk.php';
        //alert(api_url);
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.inventory),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
               .success(function (data) {
	               //console.log(data);
                   if (data.code == "success") {
                       toastr.success("Imported successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   console.log("Test is " + data.test)
					   console.log("T " + JSON.stringify(data.test1))
					   console.log("TEST2 is " + data.test2)
					   //console.log("Testing2 is " + data.testing2)
					   //console.log("Testing3 is " + (data.testing3))

					    console.log("Message is " + data.message)
                   }
                   else {
	                   console.log("Code is " + data.code)
	                   console.log("Failed " + data.test)
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
	               console.log("Stuck here 2nd Else")
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
    }

	 $scope.addWeirdSNs = function () {
        var api_url = window.cfg.apiUrl + 'ifi_uk/add_weird_sns_uk.php';
        //alert(api_url);
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.recordAdd),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
               .success(function (data) {
	               //console.log(data);
                   if (data.code == "success") {
                       toastr.success("Imported successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   $("#modalAddRecord").modal("hide");
					   console.log("Test is " + data.test)
					   
					   setTimeout(function(){
						   location.reload();
					   	}, 1000);     

					   //console.log("Message is " + data.message)
                   }
                   else {
	                   console.log("Code is " + data.code)
	                   console.log("Failed " + data.test)
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
	               console.log("Stuck here 2nd Else")
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
    }


        
   $scope.get_products=function(id) {
	   //console.log("Made it here " + id)
	   AppDataService.loadProductsInCategoryList (id, null, function (result) {
		   //console.log("stuff is " + JSON.stringify(result))
		   //console.log("category is " + result.test)
		   //myblockui();
		   $scope.productList2 = result.data;		 	
          
        }, function (result) { });

   }        
        
    $scope.init=function()
    {

         AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
		AppDataService.loadCategoryTypeList(null, null, function (result) {
           $scope.categoryTypeList = result.data;
        }, function (result) { });



        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ifi_SerialNumbers', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Serial Numbers";
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
	
	$scope.exportExcel = function () {
	   myblockui();
      
               var api_url = window.cfg.apiUrl + "export/ifi_excel_export_button_serial_numbers_uk.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&product_id=" + $scope.product_id + "&status_value=" + $scope.status_value;

        $http.get(api_url).success(function (result) {
            $.unblockUI();     
            $scope.data =  result.data;
            toastr.success("The Excel document was sent to your e-mail.");
            console.log("Data is " + JSON.stringify(result.data));
            console.log("Name is " + JSON.stringify(result.data2));
            console.log("Code is " + JSON.stringify(result.code));
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    
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
        var api_url = window.cfg.apiUrl + "ifi_uk/get_serial_numbers_uk.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&product_id=" + $scope.product_id + "&status=" + $scope.status_value;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.test2)
                $scope.OrdersList = result.data;
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


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this serial number?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "ifi/delete_serial_number.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.OrdersList.length; i++) {
                if ($scope.OrdersList[i].id == id) {
                    toastr.success("Serial number successfully deleted.", "Message");
                    $scope.OrdersList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete serial number, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {

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

swdApp.controller('ifi_Ship', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.greeting = 'Hola!';
 $scope.ship = {
 	new_ship_to: '',
    artwork_none: 0,
	qc_date: new Date,
};
$scope.sound_off = 0;
			
	$scope.update_products=function(category_id) {
	    console.log("Category ID is " + category_id)
	    AppDataService.loadProductsInCategoryList(category_id, null, function (result) {
           $scope.productsInCategoryList = result.data;
        }, function (result) { });
    }
    
    $scope.LoadAddress = function (ship_to_id) {
        myblockui();
        var api_url = window.cfg.apiUrl + "ifi_uk/get_ship_to_uk.php?ship_to_id=" + ship_to_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.ship = result.data[0];
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get load address error.");
            });
    };        
    
    $scope.users = [];
    $scope.newUser = function($event){
	    
        // prevent submission
        $event.preventDefault();
        $scope.users.push({}); 
	};
	    
    $scope.check4Prefixes = function () {
	    if($scope.sound_off == 0) {	
	    	setTimeout(function(){
			    for (i = 0; i < $scope.users.length; i++) {
					console.log("IN here")
					xx = $scope.users[i].serial_numbers.split(/\n/);
        
					myblockui();
					var api_url = window.cfg.apiUrl + 'ifi/check_prefixes_shipping.php';
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.users[i]),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					})
					.success(function (data) {
						//console.log(data);	
						if (data.make_a_beep == "Yes") {
							var audio = new Audio(window.cfg.apiUrl + 'ifi/just-like-magic.mp3');
							audio.play();
						} 
						$.unblockUI();
					})
					.error(function (data) {
						console.log("Stuck here 2nd Else")
						$.unblockUI();
					});
				}
			}, 2000); 
			
		}
	}

	
/*var e = document.getElementById('studio_9');
var top = e.getElementsByTagName('input');
function isHearingProtection_Checked() {
    var e = document.getElementById('studio_9');
    var top = e.getElementsByTagName('input');
    top[0].onclick = HearingProtection_Checked;
}*/
	
    $scope.removeUser = function($event){
        // prevent submission
        $event.preventDefault();
        $scope.users.pop({});
    }
    
    $scope.calcNumOfSNs = function($event) {
	    $scope.NumOfSNs();
	}
	
	 $scope.NumOfSNs = function () {
	    //console.log("dsafasdfasd" + $scope.qrcode.barcode)
        //myblockui();
        $scope.total_sns = 0;
        for (i = 0; i < $scope.users.length; i++) {
	        xx = $scope.users[i].serial_numbers.split(/\n/);
	        $scope.total_sns = $scope.total_sns + xx.length;
        }
    };

    
	
    $scope.save = function(){
	    /*for (i = 0; i < $scope.users.length; i++) {
		    if( $.isNumeric( $scope.users[i] )) {
			    console.log(i + " is and Numeric")
		    } else if (!$.isNumeric( $scope.users[i] )) {
			    console.log(i + " is and Not Numeric")
		    }
		}*/    
	    if ($scope.ship.new_ship_to == '') {
		    console.log("NEW SHIP TO IS NULL")
	    }

	    if ($scope.ship.new_ship_to) {
			var api_url = window.cfg.apiUrl + 'ifi_uk/add_ship_to_uk.php';
				myblockui();
					$http({
						method: 'POST',
						url: api_url,
						data: $.param($scope.ship),
						headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        			})
					.success(function (result) {                          
						if (result.code == "success") {
							$scope.new_title = result.new_title;
							$.unblockUI();
             			} else {
			 				$.unblockUI();
			 				toastr.error(result.message == undefined ? result.data : result.message);
			 			}
			 		}).error(	function (data) {
			 			console.log("Code is " + result.code)
			 			toastr.error("Insert shp to error.");
			 		});
		} // END IF STATEMENT
		
		console.log("Before GET log is " + $scope.ship.carrier_id)
		var api_url = window.cfg.apiUrl + 'ifi_uk/get_log_movement_id_uk.php';
			myblockui();
				$http({
					method: 'POST',
					url: api_url,
					data: $.param($scope.ship),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        		})
				.success(function (result) {                          
					if (result.code == "success") {
						$scope.log_id = result.log_id;
						console.log("Log ID is " + $scope.log_id)
											for (i = 0; i < $scope.users.length; i++) {
												console.log("Log ID is still " + $scope.log_id)
											
												var api_url = window.cfg.apiUrl + 'ifi_uk/export_uk.php?log_id=' + $scope.log_id	;
													myblockui();
													$http({
														method: 'POST',
														url: api_url,
														data: $.param($scope.users[i]),
														headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
													})
													.success(function (result) {                          
														if (result.code == "success") {
															console.log("In HERE")
															$.unblockUI();
															location.reload();
															toastr.success("Successful.");
														} else {
															console.log("In ELSE")
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

    	//console.log($scope.users);  
		//console.log("The SNs are " + $scope.users);  
		//console.log("The SNs are " + $scope.users[0].serial_numbers);  
		console.log("Count is " + $scope.users.length)
		//console.log("Zero is " + $scope.users[0].serial_numbers.length)
		//console.log("One is " + $scope.users[1].serial_numbers.length)

        // myService.saveUsers($scope.users);
        
    }
    
    $scope.Import = function () {

        var api_url = window.cfg.apiUrl + 'ifi_uk/prefixes_uk.php';
        //alert(api_url);
            myblockui();
            /*if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }*/
            $http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.inventory),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
               .success(function (data) {
	               //console.log(data);
                   if (data.code == "success") {
                       toastr.success("Imported successfully.");
                       //window.location.href = window.cfg.rootUrl + "/alclair/qc_form/";
					   console.log("Test is " + data.test)
					   console.log("T " + JSON.stringify(data.test1))
					   console.log("TEST2 is " + data.test2)
					   //console.log("Testing2 is " + data.testing2)
					   //console.log("Testing3 is " + (data.testing3))

					    console.log("Message is " + data.message)
                   }
                   else {
	                   console.log("Code is " + data.code)
	                   console.log("Failed " + data.test)
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
	               console.log("Stuck here 2nd Else")
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
    }
        
           
    $scope.init=function()
    {

        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadShipToList(null, null, function (result) {
           $scope.shipToList = result.data;
        }, function (result) { });
		AppDataService.loadCarriersList(null, null, function (result) {
           $scope.carriersList = result.data;
        }, function (result) { });
		AppDataService.loadWarehousesList(null, null, function (result) {
           $scope.warehousesList = result.data;
        }, function (result) { });
		AppDataService.loadOrderTypesList(null, null, function (result) {
           $scope.orderTypesList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ifi_Edit_Ship', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

 $scope.ship = {
 	new_ship_to: '',
    artwork_none: 0,
};

	$scope.update_products=function(category_id) {
	    console.log("Category ID is " + category_id)
	    AppDataService.loadProductsInCategoryList(category_id, null, function (result) {
           $scope.productsInCategoryList = result.data;
        }, function (result) { });
    }
    
    $scope.LoadAddress = function (ship_to_id) {
        myblockui();
        var api_url = window.cfg.apiUrl + "ifi_uk/get_ship_to_uk.php?ship_to_id=" + ship_to_id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            $scope.ship = result.data[0];
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get load address error.");
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
    
    $scope.LoadData = function () {
        myblockui();
        console.log("The id is " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_order_uk.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
                    $scope.ship = result.data[0];
                    console.log("Success")
                    console.log("Code is " + result.code)
                    console.log("Data 2 is " + JSON.stringify(result.data2))
                    $scope.LoadData2();
                    //$scope.qc_form.qc_date = moment($scope.qc_form.qc_date).format("MM/DD/YYYY");
                    
                    // SET BOOLEAN VALUES FOR QC FORM                    
                } else {
	                console.log("Length is less than 0")
	                 console.log("Test is " + result.test)
	                console.log("Code is " + result.code)
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                console.log("Error")
                console.log("Code is " + result.code)
                toastr.error("Get order error.");
            });
    }

	  $scope.LoadData2 = function () {
        myblockui();
        console.log("The ID is " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_serial_numbers_in_log_uk.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                //if (result.data.length > 0) {
	                $scope.TotalRecords = result.TotalRecords;
                    $scope.snList = result.data;
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    //$scope.order.date_delivered = moment($scope.order.date_delivered).format("MM/DD/YYYY");
					//console.log("The error is " + result.message)
                    console.log("The output is " + JSON.stringify($scope.snList))
                //}
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
    	
    $scope.update = function () {

	    if(!$scope.ship.carrier_id) {
			$scope.ship.carrier_id = 0;
	    }
	    if(!$scope.ship.warehouse_id) {
			$scope.ship.warehouse_id = 0;
	    }
	    if(!$scope.ship.ordertype_id) {
			$scope.ship.ordertype_id = 0;
	    }
	    if(!$scope.ship.shipping_cost) {
			$scope.ship.shipping_cost = 0;
	    }
	    console.log("Shipping is " + $scope.ship.shipping_cost)
        var api_url = window.cfg.apiUrl + 'ifi_uk/update_order_uk.php';
		console.log(api_url+"?"+$scope.ship);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ship),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log("message " + result.message);
             //console.log("Test is " + result.test)
             //console.log("Test 1 is " + result.test1)
              if(result.message == "Something is incomplete") {
	             toastr.error("Cannot ship product.  Please check that everything has passed QC.");
	             $.unblockUI();
             }   
             else {         
             	if (result.code == "success") {
					 $.unblockUI();
					 toastr.success("Update made!")
					 setTimeout(function(){
						 location.reload();
					 }, 500); 					 
             	}
			 	else {
                 	$.unblockUI();
				 	console.log("message " + result.code);
				 	console.log(	"In this spot")
				 	toastr.error(result.message == undefined ? result.data : result.message);
             	}
			} // END ELSE STATEMENT
         }).error(function (data) {
             toastr.error("Insert QC form error.");
         });
    };
               
    $scope.init=function()
    {
		$scope.LoadData();
        AppDataService.loadMonitorList(null, null, function (result) {
           $scope.monitorList = result.data;
        }, function (result) { });
        AppDataService.loadShipToList(null, null, function (result) {
           $scope.shipToList = result.data;
        }, function (result) { });
		AppDataService.loadCarriersList(null, null, function (result) {
           $scope.carriersList = result.data;
        }, function (result) { });
		AppDataService.loadWarehousesList(null, null, function (result) {
           $scope.warehousesList = result.data;
        }, function (result) { });
		AppDataService.loadOrderTypesList(null, null, function (result) {
           $scope.orderTypesList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
        });
    }
    $scope.init();
}]);

swdApp.controller('ifi_SerialNumber_History', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Serial Numbers";
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
	    console.log("The serial number is " +  window.cfg.Id)
        myblockui();
        //$cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchText", $scope.cust_name);
        
		//$cookies.put("SearchStartDate",$scope.SearchStartDate);
		//$cookies.put("SearchEndDate",$scope.SearchEndDate);
		console.log("Product id is " + $scope.product_id)
		console.log("Search text is " + $scope.SearchText)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_serial_number_history_uk.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&serial_number=" +  window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.test2)
                $scope.OrdersList = result.data;
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


    $scope.deleteForm = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this serial number?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "ifi_uk/delete_serial_number_uk.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.QC_FormList.length; i++) {
                if ($scope.QC_FormList[i].id == id) {
                    toastr.success("Serial number successfully deleted.", "Message");
                    $scope.QC_FormList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete serial number, please try again.");
        });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.customer_name,'width=760,height=600,menu=0,scrollbars=1');
	}
       
    $scope.init = function () {

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

swdApp.controller('ifi_LogID_SNs', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Serial Numbers";
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
    
    $scope.LoadData = function () {
        myblockui();
        console.log("The ID is " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_serial_numbers_in_log_uk.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                //if (result.data.length > 0) {
	                $scope.TotalRecords = result.TotalRecords;
                    $scope.snList = result.data;
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    //$scope.order.date_delivered = moment($scope.order.date_delivered).format("MM/DD/YYYY");
					//console.log("The error is " + result.message)
                    console.log("The output is " + JSON.stringify($scope.snList))
                //}
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
       
    $scope.init = function () {

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

swdApp.controller('ifi_LogHistory', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	//$scope.entityName = "Serial Numbers";
    
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
	//$scope.SearchStartDate=window.cfg.OctoberOne; //CurrentMonthFirstDate;
	$scope.SearchStartDate=window.cfg.CurrentDay;
	$scope.SearchEndDate=window.cfg.CurrentDay;
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
		console.log("Search text is " + $scope.SearchText)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_log_history_uk.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&company_id=" + $scope.company_id + "&movement=" + $scope.movement_value + "&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log("Testing is " + result.test)
	            console.log("Test2 is " + result.test2)

                $scope.OrdersList = result.data;
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;

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
                toastr.error("Get error.");
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
       
    $scope.exportExcel = function () {
	   myblockui();
      console.log("Start is " + $scope.SearchStartDate)
	  console.log("End is " + $scope.SearchEndDate)
	  console.log("Movement is " + $scope.movement_value)
	  console.log("Company ID is " + $scope.company_id)
               var api_url = window.cfg.apiUrl + "export/ifi_excel_export_button_uk.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText + "&category_id=" + $scope.category_id + "&company_id=" + $scope.company_id + "&movement=" + $scope.movement_value + "&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");

       
        $http.get(api_url).success(function (result) {
            $.unblockUI();     
            $scope.data =  result.data;
            toastr.success("The Excel document was sent to your e-mail.");
            console.log("Data is " + JSON.stringify(result.data));
            console.log("Name is " + JSON.stringify(result.data2));
            console.log("Code is " + JSON.stringify(result.code));
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.init = function () {

        //$scope.SearchText = $cookies.get("SearchText");
        //$scope.cust_name = $cookies.get("SearchText");
        //$scope.qc_form.cust_name = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        if (isEmpty($scope.cust_name)) $scope.cust_name = "";

        AppDataService.loadProductList(null, null, function (result) {
           $scope.productList = result.data;
        }, function (result) { });
		AppDataService.loadAddressesList(null, null, function (result) {
           $scope.addressesList = result.data;
        }, function (result) { });
		$scope.MovementList = AppDataService.loadMovementList;
        
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

swdApp.controller('ifi_LogID_SNs', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
	$scope.OrdersList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
	$scope.entityName = "Serial Numbers";
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
    
    $scope.LoadData = function () {
        myblockui();
        console.log("The ID is " + window.cfg.Id)
        var api_url = window.cfg.apiUrl + "ifi_uk/get_serial_numbers_in_log_uk.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                //if (result.data.length > 0) {
	                $scope.TotalRecords = result.TotalRecords;
                    $scope.snList = result.data;
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    //$scope.order.date_delivered = moment($scope.order.date_delivered).format("MM/DD/YYYY");
					//console.log("The error is " + result.message)
                    console.log("The output is " + JSON.stringify($scope.snList))
                //}
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
    }
       
    $scope.init = function () {

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
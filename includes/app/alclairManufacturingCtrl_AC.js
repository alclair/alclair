swdApp.controller('QR_Code_Scanner', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
$scope.qrcode= {
 	barcode: '',
};

	$(function(){
		$('.press-enter').keypress(function(e){
    		if(e.which == 13) {
				//dosomething
				$(".js-new").first().focus();
				//alert('Enter pressed');
    		}
  		})
	});
	
	/*
	var x = document.getElementById("start");
	if (x.addEventListener) {
		//var x = document.getElementById("start").value;
		//console.log("This part")
    	x.addEventListener("oninput", getIt);
	} else if (x.attachEvent) {
    	console.log("Not working")
		//x.attachEvent("onclick", myFunction);
	}*/
	document.getElementById("start").oninput = function() {myFunction()};
	
	function myFunction() {
		 setTimeout(function(){
				var x = document.getElementById("start").value;
		//document.getElementById("demo").innerHTML = "You wrote: " + x;
		if(x[0] == 'R') {
			//console.log("It's a repair!")
			y = x.substring(1,  x.length);
			console.log("Y = " + y)
			$scope.LoadRepairInfo(y, cart);
		} else {
			console.log("TESTING " + cart + " and " + start.value)
			$scope.LoadOrderInfo(x, cart);
		}
			}, 500); 
	};
	
	$scope.LoadRepairInfo = function (barcode, cart) {
		myblockui();
		console.log("bar cart id " + barcode + " " + cart + " " + start.value)//
        var api_url = window.cfg.apiUrl + "alclair_manufacturing/load_repair_info.php?barcode=" + barcode + "&cart=" + cart + "&repair_id=" + barcode;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
             //console.log(result);
             console.log("message " + result.message);
             if(result.message == "Something is incomplete") {
	             toastr.error("Cannot complete action.  Please check that everything is OK.");
	             $.unblockUI();
             }
             else {            
             	if (result.code == "success") {
                 	$.unblockUI();
				 	//alert(result.data.id);
				 	//if (result.data.id !=undefined)
				 	//{
                    	 //$scope.qrcode.id = result.data.id;
                    	 //console.log("Data is " + JSON.stringify(result.data))
                    	 //console.log("Order ID is " + (result.data[0].order_id))
                    	 //console.log("Test is " + (result.test))
                    	 $scope.qrcode.order_id = "R" + result.data[0].id;
                    	 $scope.qrcode.designed_for = result.data[0].customer_name;
                    	 $scope.qrcode.type = "Repair";
                    	 $scope.days = result.days;
                 	//}
				 	//else
				 	//{
                 	//}
             	}
			 	else {
                	 $.unblockUI();
                	 console.log("HERE")
					 toastr.error(result.message == undefined ? result.data : result.message);
             	}
             } // END ELSE STATEMENT
         	}).error(function (data) {
           	 toastr.error("Loading info error.");
           	 $.unblockUI();
       	});         
	}
            
    $scope.Accept = function (step) {
	    //console.log("dsafasdfasd" + $scope.qrcode.barcode)
        if (!$scope.qrcode.barcode) {
	         toastr.error("Enter in a barcode.");
			 return;
        }
        
        if (step == 'start_cart') {
	        var x = document.getElementById("start").value;
	        if(x[0] == 'R') {
				toastr.error("Repair orders do not go through this cart!")
				return;
			}
	    	var api_url = window.cfg.apiUrl + 'alclair_manufacturing/start_cart_v2.php';   
        } 
        else if (step == 'repair_cart') {
	        var x = document.getElementById("start").value;
			if(x[0] != 'R') {
				toastr.error("Manufacturing orders do not go through this cart!")
				return;
			}
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/repair_cart.php';
	    } 
	    else if (step == 'diagnosing') {
		    var x = document.getElementById("start").value;
			if(x[0] != 'R') {
				toastr.error("Manufacturing orders do not go through this cart!")
				return;
			}
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/diagnosing.php';
        } 
        else if (step == 'repair_reshell') {
	        var x = document.getElementById("start").value;
			if(x[0] != 'R') {
				toastr.error("Manufacturing orders do not go through this cart!")
				return;
			}
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/repair_reshell.php';
        }
        else if (step == 'digital_impression_detailing') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/digital_impression_detailing.php';
        }
        else if (step == 'shell_pouring') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/shell_pouring.php';
        }
        else if (step == 'shell_detailing') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/shell_detailing.php';
        }
        else if (step == 'driver_purgatory') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/driver_purgatory.php';
        }
		else if (step == 'hearing_protection') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/hearing_protection.php';
        }
        else if (step == 'exp_assembly') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/exp_assembly.php';
        }

        else if (step == 'casing') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/casing.php';
        }
        else if (step == 'finishing') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/finishing.php';
        }
        else if (step == 'quality_control') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/quality_control.php';
        }
        else if (step == 'electronics_qc') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/electronics_qc.php';
        }
        else if (step == 'artwork') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/artwork.php';
        }
        else if (step == 'ready_to_ship') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/ready_to_ship.php';
        }
        else if (step == 'active_repair') {
	        var x = document.getElementById("start").value;
			if(x[0] != 'R') {
				toastr.error("Manufacturing orders do not go through this cart!")
				return;
			}
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/active_repair.php';
        }
        else if (step == 'holding_for_payment') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/holding_for_payment.php';
        }
        else if (step == 'group_order_holding') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/group_order_holding.php';
        }
        else if (step == 'pre_group_order_holding') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/pre_group_order_holding.php';
        }
        else if (step == 'holding') {
	        //var x = document.getElementById("start").value;
			//if(x[0] != 'R') {
			//	toastr.error("Manufacturing orders do not go through this cart!")
			//	return;
			//}
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/holding.php';
        }
        else if (step == 'done') {
	        var api_url = window.cfg.apiUrl + 'alclair_manufacturing/done.php';
        }
        
        //var api_url = window.cfg.apiUrl + 'alclair_manufacturing/start_cart.php';
		console.log(api_url+"?"+$scope.qrcode);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.qrcode),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             //console.log(result);
             console.log("message " + result.message);
             console.log("Important message is " + result.testing)
             if(result.message == "Something is incomplete") {
	             toastr.error("Cannot complete action.  Please check that everything is OK.");
	             $.unblockUI();
             }
             else {            
             	if (result.code == "success") {
                 	$.unblockUI();
				 	//alert(result.data.id);
				 	 toastr.success("Order has been updated!")
				 	 // IF EMAIL EXISTS UPDATE ACTIVE CAMPAIGN
				 	 if($scope.qrcode.email) {
					 	 console.log("Email Exists " +$scope.qrcode.email)
					 } else {
						 console.log("Email Does Not Exist " +$scope.qrcode.email)
					 }
				 	 //return;
				 	 if($scope.qrcode.email) {
					 	 setTimeout(function(){
						 	 var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
						 	 //return;
						 	 Email = $scope.qrcode.email;
						 	 Current_status = step;//'RIGHT';
						 	 Estimated_ship_date = moment($scope.qrcode.estimated_ship_date).format("MM/DD/YYYY");
							 //json_text= '{ "contact": { "email": '+'"'+$scope.qrcode.email+'"'+', "fieldValues":[{"field": 49, "value": '+step+'}, {"field": 50, "value": "Estimated Ship Date"}] }}';
							 //json_text= '{ "contact": { "email": "galenwallaceclarkmusic@gmail.com", "fieldValues":[{"field": 49, "value": "RIGHT HERE"}, {"field": 50, "value": "Estimated Ship Date"}] }}';
							 json_text= '{ "contact": { "email": "' +Email+'", "fieldValues":[{"field": 49, "value": "'+Current_status+'"}, {"field": 50, "value": "'+Estimated_ship_date+'"}] }}';
							 $http({
							 	method: 'POST',
							 	// January 9th, 2023 - A PROXY SERVER WAS REQUIRED
							 	// Cross-Origin Resource Sharing (CORS) WAS AN ISSUE
							 	//https://corsproxy.io/ WAS USED
								url: 'https://corsproxy.io/?https://alclair.api-us1.com/api/3/contact/sync',							 	data: json_text,
							 	headers: {
								 	'Content-Type': 'application/json',					 	
								 	'Api-Token': key_is,
								 	//'Origin':'https://alclair.api-us1.com/api/3/',
								 	'Origin': 'https://otis.alclr.co:8080',
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
						 	 
						 	 setTimeout(function(){ 
							 	 location.reload();				 	
							 }, 500);    
						}, 500);   
						 console.log("Email exists " + $scope.qrcode.email)	 
					 } else {
						 	// EMAIL DOES NOT EXIST AND RELOAD PAGE ONLY
						 	setTimeout(function(){ 
						 		location.reload();				 	
						 	}, 500);   
						 	console.log("Email does not exist " + $scope.qrcode.email)	  
					 }

				 	  
				 	if (result.data.id !=undefined)
				 	{
                    	 $scope.qrcode.id = result.data.id;
                 	}
				 	else
				 	{
                     
                 	}
				 	//redirect
             	}
			 	else {
                	 $.unblockUI();
                	 console.log("In Here")
					 toastr.error(result.message == undefined ? result.data : result.message);
             	}
             } // END ELSE STATEMENT
         }).error(function (data) {
           	 toastr.error("Barcode error.");
       	});
    };
    
    $scope.LoadOrderInfo = function (barcode, cart) {
	    //console.log("dsafasdfasd" + $scope.qrcode.barcode)
        
        myblockui();
        var api_url = window.cfg.apiUrl + "alclair_manufacturing/load_order_info.php?barcode=" + barcode + "&cart=" + cart + "&order_id=" + start.value;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
             //console.log(result);
             console.log("message " + result.message);
             if(result.message == "Something is incomplete") {
	             toastr.error("Cannot complete action.  Please check that everything is OK.");
	             $.unblockUI();
             }
             else {            
             	if (result.code == "success") {
                 	$.unblockUI();
				 	//alert(result.data.id);
				 	//if (result.data.id !=undefined)
				 	//{
                    	 //$scope.qrcode.id = result.data.id;
                    	 //console.log("Data is " + JSON.stringify(result.data))
                    	 //console.log("Order ID is " + (result.data[0].order_id))
                    	 //console.log("Test is " + (result.test))
                    	 $scope.qrcode.order_id = result.data[0].order_id;
                    	 $scope.qrcode.designed_for = result.data[0].designed_for;
                    	 $scope.qrcode.email = result.data[0].email;
                    	 $scope.qrcode.estimated_ship_date = result.data[0].estimated_ship_date;
                    	 $scope.qrcode.type = "Manufacturing";
                    	 $scope.days = result.days;
                    	 console.log("TEST IS  " + result.test)
                 	//}
				 	//else
				 	//{
                 	//}
             	}
			 	else {
                	 $.unblockUI();
					 toastr.error(result.message == undefined ? result.data : result.message);
             	}
             } // END ELSE STATEMENT
         	}).error(function (data) {
           	 toastr.error("Loading info error.");
           	 $.unblockUI();
       	});         
    };
    $scope.LoadData = function () {
	   var path = window.location.pathname;
	   var page = path.split("/").pop();
	   console.log( "Name of the page is " + page);
	   
	   if(page == "start_cart") {
		   $scope.order_status_id = 1;
	   } else if (page == "impression_detailing") {
		   $scope.order_status_id = 2;
		} else if (page == "digital_impression_detailing") {
		   $scope.order_status_id = 15;
	   } else if (page == "shell_pouring") {
		   $scope.order_status_id = 3;
	   } else if (page == "shell_detailing") {
		   $scope.order_status_id = 4;
	   } else if (page == "driver_purgatory") {
		   $scope.order_status_id = 17;
	   } else if (page == "hearing_protection") {
		   $scope.order_status_id = 18;
	   } else if (page == "exp_assembly") {
		   $scope.order_status_id = 19;
	   } else if (page == "casing") {
		   $scope.order_status_id = 5;
	   } else if (page == "finishing") {
		   $scope.order_status_id = 6;
	   } else if (page == "quality_control") {
		   $scope.order_status_id = 7;
	   } else if (page == "electronics_qc") {
		   $scope.order_status_id = 8;
	   } else if (page == "artwork") {
		   $scope.order_status_id = 9;
	   } else if (page == "ready_to_ship") {
		   $scope.order_status_id = 10;
	   } else if (page == "group_order_holding") {
		   $scope.order_status_id = 11;
		} else if (page == "pre_group_order_holding") {
		   $scope.order_status_id = 16;
		} else if (page == "holding") {
		   $scope.order_status_id = 13;
	   } else if (page == "holding_for_payment") {
		   $scope.order_status_id = 14;
	   } else if (page == "order_received") {
		   $scope.order_status_id = 99;
	   }
	   
	   if(page == "repair_cart") {
		   $scope.repair_status_id = 1;
	   } else if (page == "diagnosing") {
		   $scope.repair_status_id = 2;
	   } else if (page == "repair_reshell") {
		   $scope.repair_status_id = 3;
		} else if (page == "digital_impression_detailing") {
		   $scope.repair_status_id = 17;
	   } else if (page == "shell_pouring") {
		   $scope.repair_status_id = 4;
	   } else if (page == "shell_detailing") {
		   $scope.repair_status_id = 5;
	   } else if (page == "casing") {
		   $scope.repair_status_id = 6;
	   } else if (page == "finishing") {
		   $scope.repair_status_id = 7;
	   } else if (page == "quality_control") {
		   $scope.repair_status_id = 8;
	   } else if (page == "electronics_qc") {
		   $scope.repair_status_id = 9;
	   } else if (page == "artwork") {
		   $scope.repair_status_id = 10;
	   } else if (page == "ready_to_ship") {
		   $scope.repair_status_id = 11;
	   } else if (page == "active_repair") {
		   $scope.repair_status_id = 12;
	   } else if (page == "group_order_holding") {
		   $scope.repair_status_id = 13;
		} else if (page == "pre_group_order_holding") {
		   $scope.repair_status_id = 18;
	   } else if (page == "done") {
		   $scope.repair_status_id = 14;
	   } else if (page == "holding") {
		   $scope.repair_status_id = 15;
	   } else if (page == "holding_for_payment") {
		   $scope.repair_status_id = 16;
	   } else if (page == "repair_received") {
		   $scope.repair_status_id = 99;
	   }
	   
	    
        myblockui();
		
		console.log("rush is " + $scope.order_status_id)
        var api_url = window.cfg.apiUrl + "alclair_manufacturing/get_customers_in_cart.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY")+"&PRINTED_OR_NOT=" + $scope.printed_or_not+"&ORDER_STATUS_ID=" + $scope.order_status_id +"&REPAIR_STATUS_ID=" + $scope.repair_status_id + "&RUSH_OR_NOT=" + $scope.rush_or_not;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            //console.log("Testing is " + result.test)
	            //console.log("Test2 is " + JSON.stringify(result.data[0]))
	            
                $scope.OrdersList = result.data;
                $scope.RepairsList = result.data2;
                $scope.RepairsList_Active_HP = result.data3;
                
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
        
           
    $scope.init=function()
    {

		$scope.LoadData();
        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);
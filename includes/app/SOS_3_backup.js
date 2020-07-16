swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {
async function getSOSauthorizationCode() {
    return new Promise(
        (resolve, reject) => {
	        var api_url = window.cfg.apiUrl + "sos/get_sos_code.php";
	$http.get(api_url)
		.success(function (result) {
			sos_code = result.sos_code;
			resolve(sos_code);
			$.unblockUI();
        }).error(function (result) {
			toastr.error("Could not get code");
        });

        }
    );
};
$scope.getItemsFromDB = function() {
	$scope.sos_sku = [];
	$scope.sos_id = [];
	var api_url = window.cfg.apiUrl + "sos/get_items.php";
	$http.get(api_url)
	.success(function (result) {
		//console.log("Length is " + result.data.length + " and ID is " + result.data[44].sos_id)
		for(p=0; p < result.data.length; p++) {
			$scope.sos_sku[p] = result.data[p].sku;
			$scope.sos_id[p] = result.data[p].sos_id;
		}
		$.unblockUI();
   	}).error(function (result) {
		console.log("Order number that could not be found was " + order_number)
    });
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$scope.customer_info = [];
$scope.num_of_orders = [];
async function GrabOrderNumbersWoo_1st_Time() {
	//var api_url = window.cfg.apiUrl + "sos/all_woocommerce_order_numbers.php";
	index = 0;
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
	$http.get(api_url)
		.success(function (result) {
			//num_of_orders = result.num_of_orders;
			$scope.num_of_orders = result.num_of_orders;
			loop_thru = $scope.num_of_orders;
			console.log("Number of orders for November is " + $scope.num_of_orders)
			for(p=0; p < loop_thru; p++) {
				//var customer_info = '{"Name": ' + '"' + result.customer_names[p] + '"' + ', "Email": ' + '"' +  result.emails[p] + '"' + '}';
				//console.log("P is " + p + " and " + customer_info)
				$scope.customer_info[index] = '{"Name": ' + '"' + result.customer_names[p] + '"' + ', "Email": ' + '"' +  result.emails[p] + '"' + '}';
				console.log("P is " + p + " and " + $scope.customer_info[index])
				//$scope.CreateCustomersinSOS2($scope.customer_info[index]);
				index = index + 1;
				//return;
				//$scope.CreateCustomersinSOS(result.order_numbers, result.customer_names, result.emails);
			}
			(async () => {
				//await CreateCustomersInSOS4();
				loop_thru = $scope.customer_info.length;
				for(let i = 0; i < loop_thru; i++){
					await STEP3($scope.customer_info[i], 1000*(i+1));
				}
			})();	
			$.unblockUI();
        }).error(function (result) {
			toastr.error("Did not grab all orders.");
       	});
       	return;
}

function STEP3(customer_info, delay) { 
//async function STEP3(customer_info) {
	setTimeout(function(){
	console.log("Customer info is " + customer_info)
	$http({
	  method: 'POST',
	  url: 'https://api.sosinventory.com/api/v2/customer/',
	  data: customer_info, 
	  headers: {
		  'Content-Type': 'application/x-www-form-urlencoded',
		  'Host': 'api.sosinventory.com',
		  'Authorization': 'Bearer ' + sos_code
		}
	}).then(function successCallback(response) {
		console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
		json = response.data.data;
		return(json.id)
	  }, function errorCallback(response) {
		  console.log("Failed to create customer " + JSON.stringify(response))
	  });	
	  }, delay)
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
async function STEP1() {
	//let result = await STEP2();
	(async () => {
		let result = await STEP2();
	})();	
    //console.log(result);
}

async function STEP2() {
	setTimeout(function(){
		let result;
		loop_thru = $scope.customer_info.length;
		for(let i = 0; i < loop_thru; i++){
			setTimeout(function(){
			(async () => {
				let result = await STEP3($scope.customer_info[i]);
			})();
			}, 0)
			//result = await STEP3($scope.customer_info[i])
		}
		return;
		/*
		$http({
		  method: 'POST',
		  url: 'https://api.sosinventory.com/api/v2/customer/',
		  data: customer_info, 
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
			json = response.data.data;
			return(json.id)
		  }, function errorCallback(response) {
			  console.log("Failed to create customer " + JSON.stringify(response))
		  });	
		  */
	}, 0)
}



async function CreateCustomersInSOS4(customer_info) {
	setTimeout(function(){
		console.log("SOS4 The length of customer info is " + $scope.num_of_orders)
		index = 0;
		loop_thru = $scope.num_of_orders;
		let result;
		for(let p=0; p < loop_thru; p++) {
			console.log("Inside SOS4 customer info is " + $scope.customer_info[p])
			//(async () => {
				//result = await CreateCustomersInSOS5($scope.customer_info[p]);
			$scope.CreateCustomersinSOS2($scope.customer_info[p]);
			//})();	
			//$scope.CreateCustomersinSOS2($scope.customer_info[p]);
			//index = index+1;
		}
		return;
	}, 500)
}

	
$scope.CreateCustomersinSOS2 = function (customer_info) { 
	console.log("Customer info is " + customer_info)
	$http({
	  method: 'POST',
	  url: 'https://api.sosinventory.com/api/v2/customer/',
	  data: customer_info, 
	  headers: {
		  'Content-Type': 'application/x-www-form-urlencoded',
		  'Host': 'api.sosinventory.com',
		  'Authorization': 'Bearer ' + sos_code
		}
	}).then(function successCallback(response) {
		console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
		json = response.data.data;
		return(json.id)
	  }, function errorCallback(response) {
		  console.log("Failed to create customer " + JSON.stringify(response))
	  });	
}

async function CreateCustomersInSOS3() {
	setTimeout(function(){
	console.log("The length of customer info is " + $scope.customer_info.length)
	loop_thru = $scope.customer_info.length;
	for(p=0; p < loop_thru; p++) {
		console.log("Customer INFO is " + $scope.customer_info[p])
		$http({
			method: 'POST',
			url: 'https://api.sosinventory.com/api/v2/customer/',
			data: $scope.customer_info[p], 
			headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Host': 'api.sosinventory.com',
			'Authorization': 'Bearer ' + sos_code
		}
		}).then(function successCallback(response) {
			console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
			json = response.data.data;
			return(json.id)
	  	}, function errorCallback(response) {
		  console.log("Failed to create customer")
	  	});	
	}
	}, 15000)
}



$scope.CreateCustomersinSOS = function (order_numbers, customer_names, emails) { //customer_info
	loop_thru = order_numbers.length;
	for(p=0; p < loop_thru; p++) {
		var customer_info = '{"Name": ' + '"' + customer_names[p] + '"' + ', "Email": ' + '"' +  emails[p] + '"' + '}';
		//var customer_info = '{"Name": ' + '"' + customer_names[p] + '"' + ', "Email": ' + '"' +  "stop@gmail.com" + '"' + '}';
		console.log("Customer info is " + customer_info)
		$http({
		  method: 'POST',
		  url: 'https://api.sosinventory.com/api/v2/customer/',
		  data: customer_info, 
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
			json = response.data.data;
			return(json.id)
		  }, function errorCallback(response) {
			  console.log("Failed to create customer")
		  });	
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ALL CUSTOMER EMAILS INSIDE SOS - STEP 4 /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$scope.customer_email = [];
$scope.customer_id = [];
async function GrabCustomersSOS_3() {
	index = 0;
	var num_customers = 120;
	var start_at = 90;
	var customer_id = new Array();
	var customer_name = new Array();
	var customer_email = new Array();
	try {
		for(i=start_at; i < num_customers; i++) {
			await GrabCustomersSOS_4(i).then(function() { // REMOVED json FROM HERE
				//console.log("I is " + i )
				var name = json.name;
				var n = name.search("deleted");
				if(json == "empty") {
					//console.log("JSON equals empty and I is " + i)
					//i = num_customers;
				} else {
					//console.log("Customer in Step 1 is " + json.name + " and email is " + json.email + " and customer ID is " + json.id);
					if(n > 0) { // THE WORD DELETED EXISTS IN THE NAME
						//console.log("The name has been deleted " + json.name + " " + i)	
					} else {
						customer_id[index] = json.id; //i;
						customer_name[index] = json.name;
						customer_email[index] = json.email;
						$scope.customer_email[index] = json.email;
						$scope.customer_id[index] = json.id;  //i;
						index = index + 1;
						console.log("Array name " + json.name + " " + i)	
					}
					//console.log("JSON IS NOT EMPTY and I is " + i + " and archived is " + json.archived + " Name is " + json.name)
				}
			})
		} // CLOSE FOR LOOP
		for(j=0; j < $scope.customer_email.length; j++) {
			//console.log("Name is " + customer_name[j] + " and email is " + customer_email[j] + " and ID is " + customer_id[j])	
			//console.log("Email is " + $scope.customer_email[j] + " and ID is " + $scope.customer_id[j])	
		}	
	} catch (error) {
       //console.log("Broken here & number of customers is now " + num_customers + " and I is " + i);
    }
}   

// 2nd promise
async function GrabCustomersSOS_4(i) {
    return new Promise(
        (resolve, reject) => {
	        $http({
			  method: 'GET',
			  url: 'https://api.sosinventory.com/api/v2/customer/'+i, 
			  headers: {
				  'Content-Type': 'application/x-www-form-urlencoded',
				  'Host': 'api.sosinventory.com',
				  'Authorization': 'Bearer ' + sos_code
				}
			}).then(function successCallback(response) {
				json = response.data.data;
				setTimeout(function(){
					if(json) {
						//console.log("Customer in Step 2 is " + json.name + " and I is " + i);
						resolve(json);
					} else {
						console.log("READY & ENDED")
						resolve("empty");
						//return;
					}
				}, 1000)
			  }, function errorCallback(response) {
				  console.log("Fail")
			  });
            //resolve(json);
        }
    );
};

////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ALL CUSTOMER EMAILS INSIDE SOS ////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

async function GrabOrderNumbersWoo_2nd_Time() {
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
	$http.get(api_url)
		.success(function (result) {
			loop_thru = result.order_numbers.length;
			//try {
				for(i=0; i < loop_thru; i++) {
					//console.log("Number of orders is " + result.order_numbers.length)
					$scope.BuildSalesOrder(result.order_numbers[i]);
					//await BuildSalesOrder(result.order_numbers[i]).then(function() { 
						//console.log("The SO is " + SALES_ORDER + " and I is " + i)
						//$scopeCreateSalesOrder(SALES_ORDER)
					//})
				}
			//} catch (error) {
				//console.log(error.message);
				//console.log("Failed in Grab Order Numbers from Woo - 2nd Time");
    			//}
			$.unblockUI();
        	}).error(function (result) {
			toastr.error("Did not grab all orders.");
        	});
}
$scope.BuildSalesOrder = function (order_number) {
	var api_url = window.cfg.apiUrl + "sos/get_woo_order_processing_button.php?order_number="+ order_number;
		$http.get(api_url)
		.success(function (result) {
			console.log("TEST IS " + result.test)
			num_of_skus = result.SKUs.length;
			email = result.email;
			//CUSTOMER_ID  = 1;
			for(s=0; s < $scope.customer_email.length; s++) {
				if(email == $scope.customer_email[s]) {
					CUSTOMER_ID = $scope.customer_id[s]
				}
			}
			ORDER_DATE = result.order_date;
			LINES = "'lines':[";
setTimeout(function(){
			for(p=0; p < num_of_skus; p++) {
				lineNumber = p+1;
				ITEM_INDEX = $scope.sos_sku.indexOf(result.SKUs[p]);
				ITEM_ID =$scope.sos_id[ITEM_INDEX];
				QUANTITY = 1;
				//if(p == 0) {
				DISCOUNT = result.DISCOUNT;
				SHIPPING_AMOUNT = result.SHIPPING_AMOUNT;
				TAXES = result.TAXES;
				if(result.Is_Earphone[p] == "YES") {
					UNITPRICE = result.SUBTOTALs[p];
				} else { 
					UNITPRICE = result.SUBTOTALs[p];
				}
				lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'class':{'id': 1, 'name': 'Alclair'}, 'quantity':" + 1 + ", 'unitprice':" + UNITPRICE + ", 'amount':" + UNITPRICE + "},";
				//lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'unitprice':" + UNITPRICE + "},";
				LINES = LINES + lineNumber_line;
			}

			LINES = LINES + "]";
			ORDER_NUMBER = result.order_number;
			SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + "'discountAmount': " + DISCOUNT + ","  +"'taxAmount':" + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT +", " + LINES + "}";  
			
				$scope.CreateSalesOrder(SALES_ORDER)
}, 2000)	
			$.unblockUI();
    		}).error(function (result) {
				console.log("Order number that could not be found was " + ORDER_NUMBER)
    		});
}
$scope.CreateSalesOrder = function (add_order) {
	console.log("The order to add is " + add_order)
	$http({
	  method: 'POST',
	  url: 'https://api.sosinventory.com/api/v2/salesorder/',
	  data: add_order,
	  headers: {
		  'Content-Type': 'application/x-www-form-urlencoded',
		  'Host': 'api.sosinventory.com',
		  'Authorization': 'Bearer ' + sos_code
		}
	}).then(function successCallback(response) {
		console.log("Sales Order Created " + JSON.stringify(response.data.data))
		json = response.data.data;
		console.log("JSON is " + json.customer)
	 }, function errorCallback(response) {
		  console.log("Failed to create Sales Order")
	 });
} 	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$scope.CreateCustomer2 = function (customer_info) { //customer_info
    console.log("In Post ")
	//customer_name = "Tyler Folsom";
	//email = "tyler.folsom@gmail.com";
	//var customer_info = '{"Name": ' + '"' + customer_name + '"' + ', "Email": ' + '"' +  email + '"' + '}';
	$http({
	  method: 'POST',
	  url: 'https://api.sosinventory.com/api/v2/customer/',
	  data: customer_info, //{'Name': 'Test Name 1'},
	  headers: {
		  'Content-Type': 'application/x-www-form-urlencoded',
		  'Host': 'api.sosinventory.com',
		  'Authorization': 'Bearer ' + sos_code
		}
	}).then(function successCallback(response) {
		console.log("Success 1 " + JSON.stringify(response.data.data))
		json = response.data.data;
		//console.log("JSON is " + json.name + " and ID is " + json.id)
		return(json.id)
	  }, function errorCallback(response) {
		  console.log("Failed to create customer")
	  });	
} 

$scope.CreateCustomer = function (customer_info) { //customer_info
	customer_name = "Tyler Folsom4";
	email = "tyler.folsom4@gmail.com";
	var customer_info = '{"Name": ' + '"' + customer_name + '"' + ', "Email": ' + '"' +  email + '"' + '}';
	$scope.CreateCustomer2(customer_info);
	console.log("The returned ID is " + returned_id)
}


$scope.Process2 = function() {
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
		$http.get(api_url).success(function (result2) {
			num_of_orders = result2.num_of_orders;
			for(k=0; k < num_of_orders; k++) {
				the_order_number = result2.order_numbers[k];
				the_customer_name = result2.customer_names[k];
				the_email = result2.emails[k];
				var customer_info = '{"Name": ' + '"' + the_customer_name + '"' + ', "Email": ' + '"' +  the_email + '"' + '}';
				console.log("# " + k + " is " + the_order_number + " and Customer is " + the_customer_name + " and emails " + the_email)
				
				

				$http({
				  method: 'POST',
				  url: 'https://api.sosinventory.com/api/v2/customer/',
				  data: customer_info, 
				  headers: {
					  'Content-Type': 'application/x-www-form-urlencoded',
					  'Host': 'api.sosinventory.com',
					  'Authorization': 'Bearer ' + sos_code
					}
				}).then(function successCallback(response) {
					
					json = response.data.data;
					CUSTOMER_ID = json.id;
					console.log("Customer's ID is " + CUSTOMER_ID + " and the order number is " + the_order_number)
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
					//var api_url = window.cfg.apiUrl + "sos/get_orders_before_july_1.php";
					var api_url = window.cfg.apiUrl + "sos/get_woo_order_processing_button.php?order_number="+ the_order_number;
						$http.get(api_url)
						.success(function (result) {
							console.log("TEST IS " + result.test)
							num_of_skus = result.SKUs.length;
							//CUSTOMER_ID  = 1;
							ORDER_DATE = result.order_date;
							LINES = "'lines':[";
	
							for(p=0; p < num_of_skus; p++) {
								lineNumber = p+1;
								ITEM_INDEX = $scope.sos_sku.indexOf(result.SKUs[p]);
								ITEM_ID =$scope.sos_id[ITEM_INDEX];
								QUANTITY = 1;
								//if(p == 0) {
								DISCOUNT = result.DISCOUNT;
								SHIPPING_AMOUNT = result.SHIPPING_AMOUNT;
								TAXES = result.TAXES;
								if(result.Is_Earphone[p] == "YES") {
									UNITPRICE = result.SUBTOTALs[p];
								} else { 
									UNITPRICE = result.SUBTOTALs[p];
								}
								lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'class':{'id': 1, 'name': 'Alclair'}, 'quantity':" + 1 + ", 'unitprice':" + UNITPRICE + ", 'amount':" + UNITPRICE + "},";
								//lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'unitprice':" + UNITPRICE + "},";
								LINES = LINES + lineNumber_line;
							}
							LINES = LINES + "]";
							ORDER_NUMBER = result.order_number;
							SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + "'discountAmount': " + DISCOUNT + ","  +"'taxAmount':" + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT +", " + LINES + "}";  
							$scope.CreateSalesOrder(SALES_ORDER)
							
							$.unblockUI();
				    		}).error(function (result) {
								console.log("Order number that could not be found was " + ORDER_NUMBER)
				    		});
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////					
				  }, function errorCallback(response) {
					  console.log("Failed to create customer")
				  });	
			
			}

		}).error(function (result) {
			console.log("Order number that could not be found was " + order_number)
    	});
}

the_order_number = [];
$scope.the_customer_name = [];
$scope.the_email = [];
CUSTOMER_ID = [];
num_of_orders = [];

$scope.Process3 = function() {
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
		$http.get(api_url).success(function (result2) {
			num_of_orders = result2.num_of_orders;
			for(k=0; k < num_of_orders; k++) {
				the_order_number[k] = result2.order_numbers[k];
				$scope.the_customer_name[k] = result2.customer_names[k];
				$scope.the_email[k] = result2.emails[k];
				var customer_info = '{"Name": ' + '"' + $scope.the_customer_name[k] + '"' + ', "Email": ' + '"' +  $scope.the_email[k] + '"' + '}';
				//console.log("# " + k + " is " + $scope.the_order_number[k] + " and Customer is " + $scope.the_customer_name[k] + " and emails " + $scope.the_email[k])
				$http({
				  method: 'POST',
				  url: 'https://api.sosinventory.com/api/v2/customer/',
				  data: customer_info, 
				  headers: {
					  'Content-Type': 'application/x-www-form-urlencoded',
					  'Host': 'api.sosinventory.com',
					  'Authorization': 'Bearer ' + sos_code
					}
				}).then(function successCallback(response) {
					
					json = response.data.data;
					CUSTOMER_ID[k] = json.id;
					console.log("Customer's ID is " + CUSTOMER_ID[k] + " and the order number is " + the_order_number[k])
				
				  }, function errorCallback(response) {
					  console.log("Failed to create customer")
				  });	
			
			}
			
		for(k=0; k < num_of_orders; k++) {
	    	console.log("# " + k + " is " + the_order_number[k] + " and Customer is " + $scope.the_customer_name[k] + " and emails " + $scope.the_email[k] + "Customer " + CUSTOMER_ID[k])
	    }
		}).error(function (result) {
			console.log("Order number that could not be found was " + result2.order_numbers[k])
    	});
    	
    	
}

$scope.getItemsFromDB();
// async await it here too
(async () => {
	await getSOSauthorizationCode();
	
	// GRAB ORDERS TO PROCESS
	
	
	//console.log("Grabbing SOS Items")
	//await GrabItemsSOS_1();
	//console.log("Grabbing SOS Customers - 1st Time")
    //await GrabCustomersSOS_3();
    //console.log("Grabbing WooCommerce order numbers - 1st Time")
	await GrabOrderNumbersWoo_1st_Time();
	//await CreateCustomersInSOS4();
	//await CreateCustomersInSOS3();
	//console.log("Grabbing SOS Customers - 2nd Time")
	//await GrabCustomersSOS_3();
	//console.log("Grabbing WooCommerce order numbers - 2nd Time")
	//await GrabOrderNumbersWoo_2nd_Time();
	//console.log("DONE")
})();	



////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////// GRABS ALL ITEMS INSIDE SOS ////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$scope.init();
}]);
	
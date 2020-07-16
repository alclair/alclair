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
	//await GrabOrderNumbersWoo_1st_Time();
	//console.log("Grabbing SOS Customers - 2nd Time")
    //await GrabCustomersSOS_3();
	//console.log("Grabbing WooCommerce order numbers - 2nd Time")
    //await GrabOrderNumbersWoo_2nd_Time();
})();	



////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////// GRABS ALL ITEMS INSIDE SOS ////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$scope.init();
}]);
	
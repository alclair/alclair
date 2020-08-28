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
			console.log("Number of orders is " + $scope.num_of_orders)
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
					await STEP3($scope.customer_info[i], 2000*(i+1), loop_thru, i);
					/*
					if(i == loop_thru-1){
						setTimeout(function(){
							(async () => {
								await GrabCustomersSOS_3();
							})();	
						}, 1000 * 12)
					}
					*/
				}
				//await GrabCustomersSOS_3();
			})();	
			$.unblockUI();
        }).error(function (result) {
			toastr.error("Did not grab all orders.");
       	});
       	return;
}

function STEP3(customer_info, delay, loop_thru, i) { 
//async function STEP3(customer_info) {
	var move_on = "NO";
	setTimeout(function(){
		console.log("Customer info is " + customer_info + "  I is " + i  + " and loop is " + loop_thru)
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
			//console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
			console.log("CUSTOMER CREATED " + JSON.stringify(response.data.data))
			var move_on = "YES";
			json = response.data.data;
			if(i == loop_thru-1 && move_on == "YES") {
				setTimeout(function(){
					(async () => {
						await GrabCustomersSOS_3();
					})();	
				}, 2000)
			} else {
				//resolve;
			}
		  }, function errorCallback(response) {
		  		console.log("Failed to create customer " + JSON.stringify(response))
		  		//console.log("Loop is " + loop_thru + " and i is " + i)
		  		if(i == loop_thru-1) {
			  		var move_on = "YES";
			  		if(move_on = "YES") {
						setTimeout(function(){
							(async () => {
								await GrabCustomersSOS_3();
							})();	
						}, 2000)
					}
				}
		  });	
	 }, delay)
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ALL CUSTOMER EMAILS INSIDE SOS - STEP 4 /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$scope.customer_email = [];
$scope.customer_id = [];
$scope.customer_name = [];
$scope.index = 0;
$scope.end = "NO";
function GrabCustomersSOS_3() {
//$scope.GrabCustomersSOS_3 = function() {
	console.log("HERE IN GRAB CUSTOMERS")
	index = 0;
	var start_at = 880; 
	var num_customers = 960; 
	var customer_id = new Array();
	var customer_name = new Array();
	var customer_email = new Array();
	try {
		for(i=start_at; i < num_customers; i++) {
			//console.log("I FIRST is " + i  )
			(async () => {
				await GrabCustomersSOS_4(i, 1000*(i- (start_at-1) ), num_customers).then(function() { // REMOVED json FROM HERE
				})
			})();
		} // CLOSE FOR LOOP
	} catch (error) {
       //console.log("Broken here & number of customers is now " + num_customers + " and I is " + i);
    }
}   

$scope.move_onto_next = "YES";
async function GrabCustomersSOS_4(i, delay, num_customers) {
	console.log("IN GRAB CUSTOMERS SOS 4")
	
    setTimeout(function(){
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
			if(json == "empty") {
				console.log("JSON is empty & i is " + i)
			} else {
				if(response.data.status == "ok") { // RETURNS A CUSTOMER
					var name = json.name;
					var n = name.search("deleted");
					if(n == -1) { // THE WORD DELETED DOES NOT EXIST IN THE NAME
						console.log("MADE IT HERE")
						if(typeof json.name != "undefined") {
							$scope.customer_email[$scope.index] = json.email;
							$scope.customer_id[$scope.index] = json.id;  //i;
							$scope.customer_name[$scope.index] = name;  //i;
							$scope.index = $scope.index + 1;
							console.log("Array name " + json.name + " " + json.id + " email is " + json.email)	
						} else {
							console.log("COULD NOT GRAB NAME " + JSON.stringify(response.data.data))
						}
						if($scope.move_onto_next == "YES" && i == num_customers-1) {
							$scope.move_onto_next = "NO";
							(async () => {
								await GrabOrderNumbersWoo_2nd_Time().then(function() { })
							})();
						}
					} else {
						console.log("The name has been deleted " + json.name + " " + json.id)	
						if($scope.move_onto_next == "YES" && i == num_customers-1) {
							$scope.move_onto_next = "NO";
							(async () => {
								await GrabOrderNumbersWoo_2nd_Time().then(function() { })
							})();
						}
					}
				} else {
					if($scope.move_onto_next == "YES" && i == num_customers-1) { // ONLY NEED TO CALL THE FUNCTION ONE TIME
						//console.log("IN ELSE STATEMENT & i is " + i + " & ncust is " + num_customers)
					//if(i == num_customers-1) { // ONLY NEED TO CALL THE FUNCTION ONE TIME	
						$scope.move_onto_next = "NO";
						(async () => {
							await GrabOrderNumbersWoo_2nd_Time().then(function() { 
							})
						})();
					} else {
						console.log("NOT READY TO GRAB ORDERS 2ND TIME ")
					}
				}
			}
		}, function errorCallback(response) {
			console.log("Fail and I is " + i)
		});
    }, delay+1000)
};

////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ALL CUSTOMER EMAILS INSIDE SOS ////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

async function GrabOrderNumbersWoo_2nd_Time() {
	console.log("IN GRAB ORDERS 2ND TIME")
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
	$http.get(api_url)
		.success(function (result) {
			loop_thru = result.order_numbers.length;
			for(i=0; i < loop_thru; i++) {
				$scope.BuildSalesOrder(result.order_numbers[i]);
			}
			$.unblockUI();
        }).error(function (result) {
			toastr.error("Did not grab all orders.");
        });
}
$scope.BuildSalesOrder = function (order_number) {
	var api_url = window.cfg.apiUrl + "sos/get_woo_order_processing_button.php?order_number="+ order_number;
		$http.get(api_url)
		.success(function (result) {
			//console.log("TEST IS " + result.test)
			num_of_skus = result.SKUs.length;
			email = result.email;
			CUSTOMER_ID  = 0;
			//console.log("Customer email length is " + $scope.customer_email.length )
			for(s=0; s < $scope.customer_email.length; s++) {
				//console.log("Email is " + email)
				if(email == $scope.customer_email[s]) {
					CUSTOMER_ID = $scope.customer_id[s]
					CUSTOMER_NAME = $scope.customer_name[s];
					CUSTOMER_EMAIL = $scope.customer_email[s];
					break;
				} else if(s == $scope.customer_email.length-1) {
					console.log(email + " EMAIL WAS NOT FOUND INSIDE FOR LOOP")
				}
			}
			ORDER_DATE = result.order_date;
			LINES = "'lines':[";
			console.log("C ID is " + CUSTOMER_ID + " and C name is " + CUSTOMER_NAME + " and C email is " + CUSTOMER_EMAIL)
			setTimeout(function(){
				console.log("Number of SKUs is " + num_of_skus + " and email is " + email)
				for(p=0; p < num_of_skus; p++) {
					lineNumber = p+1;
					ITEM_INDEX = $scope.sos_sku.indexOf(result.SKUs[p]);
					ITEM_ID =$scope.sos_id[ITEM_INDEX];
					console.log("Line 271 ITEM_ID is " + ITEM_ID + " and ITEM INDEX is " + ITEM_INDEX + " and name is " + result.customer_name)
					QUANTITY = 1;
					//if(p == 0) {
					DISCOUNT = result.DISCOUNT;
					SHIPPING_AMOUNT = result.SHIPPING_AMOUNT;
					TAXES = result.TAXES;
					ORDERSTAGE = result.orderStage;
					EMAIL = result.email;
					CUSTOMER = result.customer_name;
					console.log("THE ORDER STAGE IS " + ORDERSTAGE)
					if(result.Is_Earphone[p] == "YES") {
						UNITPRICE = result.SUBTOTALs[p];
						//console.log("A" + UNITPRICE)
					} else { 
						if (typeof result.SUBTOTALs[p] === 'string' || result.SUBTOTALs[p] instanceof String) {
							var str = result.SUBTOTALs[p];
							//var q = str.search("#36;");
							var q = str.search(";");
							//console.log("Q is " + q)
							var res = str.slice(q+1, 	result.SUBTOTALs[p].length);
							//console.log("RES is " + res + " IS A STRING")
							UNITPRICE =  res;
						} else {
							UNITPRICE = result.SUBTOTALs[p];
							//console.log(" NOT A STRING")
						}
					
					}
					lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'class':{'id': 1, 'name': 'Alclair'}, 'quantity':" + 1 + ", 'unitprice':" + UNITPRICE + ", 'amount':" + UNITPRICE + "},";
					LINES = LINES + lineNumber_line;
				}
	
				LINES = LINES + "]";
				ORDER_NUMBER = result.order_number;
				
				SALES_ORDER ="{'Number': " + ORDER_NUMBER + ", 'date': " + "'" + ORDER_DATE + "'" + ", 'customer': {'id': " + CUSTOMER_ID + " }, " + " 'shipping': {'company': " + null + ", 'contact': '" + CUSTOMER_NAME + "', 'phone': " + null + ", 'email': '" + CUSTOMER_EMAIL + "', 'addressName': " + null + ", 'addressType': " + null + ", 'address': { 'line1': " + null + ", 'line2': " + null + ", 'line3': " + null + ", 'line4': " + null + ", 'line5': " + null + ", 'city': " + null + ", 'stateProvince': " + null + ", 'postalCode': " +  null + ", 'country': " +  null + "}}, " + "'orderStage': {'id': " + ORDERSTAGE + " }," + "'discountAmount': " + DISCOUNT + ", " + "'taxAmount': " + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT + ", " + LINES + " }";
				
				//SALES_ORDER ="{'Number': " + ORDER_NUMBER + ", 'date': " + "'" + ORDER_DATE + "'" + ", 'customer': {'id': " + CUSTOMER_ID + " }, " + " 'shipping': {'company': " + null + ", 'contact': '" + CUSTOMER_NAME + "', 'phone': " + null + ", 'email': '" + CUSTOMER_EMAIL + ", 'address': { 'line1': " + null + "}}, " + "'orderStage': {'id': " + ORDERSTAGE + " }," + "'discountAmount': " + DISCOUNT + ", " + "'taxAmount': " + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT + ", " + LINES + " }";
				



				$scope.CreateSalesOrder(SALES_ORDER,  1000*(i+1))
			}, 500)	
			$.unblockUI();
    		}).error(function (result) {
				console.log("Order number that could not be found was " + ORDER_NUMBER)
    		});
}
$scope.CreateSalesOrder = function (add_order, delay) {
	setTimeout(function(){
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
			 console.log("Failed to create Sales Order " + JSON.stringify(response))
		 });
	}, delay)
} 	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
	
	//await CreateCustomersInSOS4();
	//await CreateCustomersInSOS3();
	//console.log("Grabbing SOS Customers - 2nd Time")
	
	await GrabOrderNumbersWoo_1st_Time(); // STARTS HERE
	//await GrabCustomersSOS_3(); 
	//console.log("Grabbing WooCommerce order numbers - 2nd Time")
	//await GrabOrderNumbersWoo_2nd_Time();
	//console.log("DONE")
})();	





	//$scope.init();
}]);
	
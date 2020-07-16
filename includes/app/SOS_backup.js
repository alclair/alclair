swdApp.controller('Orders', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {

$scope.user = $.param({
      Name: 'Test Name 97',
      Email: 'test@gmail.com'
});

$scope.testing = {
  "Name"  :  "Test Name 22"
}

var obj = '{'
       +'Name : Test Name 100'
       +'}';

var user2 = '{ "Customer" : [' +
'{ "Name":"John" }' +' ]}';

var user3 = '{[' +
'{ "Name":"John" }' +' ]}';

var sos_code = 'we will get there';

    $scope.Get = function () {
        console.log("In Get " )
		$http({
		  method: 'GET',
		  url: 'https://api.sosinventory.com/api/v2/customer/191', 
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("Success 1 " + JSON.stringify(response.data))
			json = response.data.data;
			console.log("The status is " + response.data.status)
			//json.name = 'Test Name 94';
			console.log("JSON is " + json.number)
		    // this callback will be called asynchronously
		    // when the response is available
		  }, function errorCallback(response) {
			  console.log("Fail")
		    // called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	}    
	
	$scope.Post = function () {
        console.log("In Post ")
		$http({
		  method: 'POST',
		  url: 'https://api.sosinventory.com/api/v2/customer/',
		  data: {'Name': 'Test Name 1'},
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("Success 1 " + JSON.stringify(response.data.data))
			json = response.data.data;
			console.log("JSON is " + json.name)
		  }, function errorCallback(response) {
			  console.log("Fail")
		  });
	} 
	
	$scope.Update = function () {
        console.log("In Get ")
		$http({
		  method: 'GET',
		  url: 'https://api.sosinventory.com/api/v2/customer/3', 
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("Success 1 " + JSON.stringify(response.data.data))
			json = response.data.data;
			json.name = 'Test Name 94';
			console.log("JSON is " + json.name)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$http({
		  method: 'PUT',
		  url: 'https://api.sosinventory.com/api/v2/customer/3',
		  data: json,
		  //dataType: 'json',
		  headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			  'Host': 'api.sosinventory.com',
			  'Authorization': 'Bearer ' + sos_code
			}
		}).then(function successCallback(response) {
			console.log("Success 1 " + JSON.stringify(response.data.data))
			json = response.data.data;
			console.log("JSON is " + json.name)
		  }, function errorCallback(response) {
			  console.log("Fail")
			  console.log(JSON.stringify($scope.testing))
		  });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    // this callback will be called asynchronously
		    // when the response is available
		  }, function errorCallback(response) {
			  console.log("Fail")
		    // called asynchronously if an error occurs
		    // or server returns response with an error status.
		  });
	}  
	
	
	$scope.SalesOrder2 = function () {
		on = 8891;
		//testing = "{'Number' : '8888', 'date':'2020-06-08T23:18:00', 'customer':{'id':5,}, 'lines': [{'lineNumber':1, 'item': {'id':53}} ]}";
		testing = "{'Number' : "+ on +", 'date':'2020-06-08T23:18:00', 'customer':{'id':5,}, 'lines': [{'lineNumber':1, 'item': {'id':53}} ]}";
testing2 = "{'Number' : 9119, 'date':2019-12-01T03:46:46+00:00, 'customer':{'id':10,}, 'lines': [{'lineNumber':1, 'item': {'id':126}} ]}";
		//console.log("Part 1 Item 5 is " + item_sku[4])
	} 
	
  
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ONE ORDER NUMBER AT A TIME - STEP 3  /////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$scope.Simulate = function () {
		//console.log("Length of emails is " + $scope.customer_id.length)
		var api_url = window.cfg.apiUrl + "sos/all_woocommerce_order_numbers.php";
		$http.get(api_url)
			.success(function (result) {
				//for(i=0; i < result.num_orders; i++) {
					//console.log("Order number is " + result.order_numbers[i] + " and email is " + result.emails[i] + " and I is " + i)
				console.log("Number of orders is " + result.order_numbers.length)
				$scope.Simulate2(result.order_numbers);
				//}
				$.unblockUI();
            	}).error(function (result) {
				toastr.error("Did not grab all orders.");
            	});
	};

	$scope.Simulate2 = function (order_number) {
		loop_thru = 10; //order_number.length;
		for(p=0; p < loop_thru; p++) {
			var api_url = window.cfg.apiUrl + "sos/get_single_woocommerce_order.php?order_number="+ order_number[p];
			$http.get(api_url)
			.success(function (result) {
				//console.log("Customer email is " + result.email)
				SKUs = result.SKUs.length;
				// CUSTOMER ALREADY EXISTS IN SOS  - FIND ID OF CUSTOMER IN SOS 
				THE_INDEX_EMAIL = $scope.customer_email.indexOf(result.email);
				THE_ID_CUSTOMER  = $scope.customer_id[THE_INDEX_CUSTOMER];
				ORDER_DATE = result.order_date;
					
				JTEM_INDEX = $scope.item_sku.indexOf(result.SKUs[0]);
				ITEM_ID = $scope.item_id[ITEM_INDEX];
//console.log("Customer exists and is " + $scope.customer_email[THE_INDEX_CUSTOMER] + " and ID is " + $scope.customer_id[THE_INDEX_CUSTOMER] + " and # of Items is " + SKUs)
					
				order_number = 8989;
//sales_order = "{'Number' : " + order_number[p] + ", 'date' : " + order_date + ", 'customer' : {'id': " + THE_ID_CUSTOMER + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + THE_ID_ITEM + "}} ]}";  
				SALES_ORDER = "{'Number' : " + order_number + ", 'date' : " + ORDER_DATE + ", 'customer' : {'id': " + THE_ID_CUSTOMER + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + ITEM_ID + "}} ]}";  
				$scope.SalesOrder(SALES_ORDER)
				$.unblockUI();
            	}).error(function (result) {
				toastr.error("Did not grab a single order.");
				console.log("Order number that could not be found was " + order_number[p])
				//location.reload();	
            	});			
		}
	};
////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////    GRABS ONE ORDER NUMBER AT A TIME - STEP 3      ////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// GRABS ALL ORDER NUMBERS & CREATES CUSTOMER IN SOS  IF NEEDED   ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
async function GrabOrderNumbersWoo_1st_Time() {
	var api_url = window.cfg.apiUrl + "sos/all_woocommerce_order_numbers.php";
	$http.get(api_url)
		.success(function (result) {
			//for(i=0; i < result.num_orders; i++) {
				//console.log("Order number is " + result.order_numbers[i] + " and email is " + result.emails[i] + " and I is " + i)
			//console.log("Number of orders is " + result.order_numbers.length)
			$scope.DecideIfCustomerGetsCreated(result.order_numbers);
			//await GrabOrderNumbersWoo_2(result.order_numbers).then(function() { 
			//})
			$.unblockUI();
        	}).error(function (result) {
			toastr.error("Did not grab all orders.");
        	});
}
$scope.DecideIfCustomerGetsCreated = function (order_number) {
		loop_thru = 12; //order_number.length; //1;
		for(p=0; p < loop_thru; p++) {
			var api_url = window.cfg.apiUrl + "sos/get_single_woocommerce_order.php?order_number="+ order_number[p];
			$http.get(api_url)
			.success(function (result) {

				SKUs = result.SKUs.length;
				if($scope.customer_email.indexOf(result.email) >= 0) {
					console.log("NOT ADDING CUSTOMER")
					// CUSTOMER ALREADY EXISTS IN SOS  - FIND ID OF CUSTOMER IN SOS 
				} else { // CUSTOMER DOES NOT EXIST IN SOS
					console.log("ADDING CUSTOMER " + result.customer_name + " email is " + result.email)
					var customer_info = '{"Name": ' + '"' + result.customer_name + '"' + ', "Email": ' + '"' +  result.email + '"' + '}';
					$scope.CreateCustomer(customer_info);
					//console.log("Customer does not exist and # of SKUs is " + SKUs)
					return;
				}
				$.unblockUI();
            	}).error(function (result) {
				//toastr.error("Did not grab a single order.");
				console.log("Order number that could not be found was " + order_number[p])
				//location.reload();	
            	});			
		}
	};	
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
	$scope.CreateCustomer2(customer_info).success(function (result) {
		console.log("The returned ID is now " + result)
	}).error(function (result) {
		console.log("DID NOT WORK")
    });	
	console.log("The returned ID is " + returned_id)
}

////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////// GRABS ALL ORDER NUMBERS & BUILD SALES ORDERS  ///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
async function GrabOrderNumbersWoo_2nd_Time() {
	var api_url = window.cfg.apiUrl + "sos/all_woocommerce_order_numbers.php";
	$http.get(api_url)
		.success(function (result) {
			loop_thru = 12; //order_number.length;
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
	//console.log("Number of orders is " + order_numbers.length)
    //for(p=0; p < order_numbers.length; p++) {
		//console.log("Order number is " + order_number)
		var api_url = window.cfg.apiUrl + "sos/get_single_woocommerce_order.php?order_number="+ order_number;
		$http.get(api_url)
		.success(function (result) {
			//console.log("Test is " + result.SKUs[0] + " and " + $scope.item_sku.length + " and " + $scope.item_id.length)
			//console.log("Test is " + result.SKUs[0] + " and " + $scope.customer_email.length + " and " + $scope.customer_id.length)
			//console.log("Test is " + result.SKUs[0] + " and length is " + result.SKUs.length + " for order # "+ order_number + " and email is " + result.email)
			num_of_skus = result.SKUs.length;
			EMAIL_INDEX = $scope.customer_email.indexOf(result.email);
			CUSTOMER_ID  = $scope.customer_id[EMAIL_INDEX];
			ORDER_DATE = result.order_date;
			//{ "number":"5565", "date":"2020-06-08T23:18:00", "customer":{ "id":5, }, "lines":[{ "lineNumber":1, 	"item":{"id":53}},{ "lineNumber":2, "item":{"id":54}}]},
			LINES = "'lines':[";
			for(p=0; p < num_of_skus; p++) {
				lineNumber = p+1;
				ITEM_INDEX = $scope.item_sku.indexOf(result.SKUs[p]);
				ITEM_ID = $scope.item_id[ITEM_INDEX];
				lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}},";
				LINES = LINES + lineNumber_line;
			}
			LINES = LINES + "]";
			//ITEM_INDEX = $scope.item_sku.indexOf(result.SKUs[0]);
			//ITEM_ID = $scope.item_id[ITEM_INDEX];
			ORDER_NUMBER = order_number; //9121; 
			//SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + ITEM_ID + "}}, ]}";  
			SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + LINES + "}";  
			//console.log("Sales order is " + SALES_ORDER)
			$scope.CreateSalesOrder(SALES_ORDER)
			
			//resolve(SALES_ORDER);
			$.unblockUI();
    		}).error(function (result) {
			//toastr.error("Did not grab a single order.");
			console.log("Order number that could not be found was " + order_number)
			//resolve(order_number);
			//location.reload();	
    		});
    //	}
}

/*
async function BuildSalesOrder(order_numbers) {
    return new Promise(
        (resolve, reject) => {
			var api_url = window.cfg.apiUrl + "sos/get_single_woocommerce_order.php?order_number="+ order_number[p];
			$http.get(api_url)
			.success(function (result) {
				SKUs = result.SKUs.length;
				THE_INDEX_EMAIL = $scope.customer_email.indexOf(result.email);
				THE_ID_CUSTOMER  = $scope.customer_id[THE_INDEX_CUSTOMER];
				ORDER_DATE = result.order_date;
				ORDER_NUMBER = order_number[p];  //9119;
				SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : " + ORDER_DATE + ", 'customer' : {'id': " + THE_ID_CUSTOMER + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + THE_ID_ITEM + "}} ]}";  
				//$scope.CreateSalesOrder(SALES_ORDER)
				resolve(SALES_ORDER);
				$.unblockUI();
        		}).error(function (result) {
				toastr.error("Did not grab a single order.");
				console.log("Order number that could not be found was " + order_number[p])
				resolve("fail");
				//location.reload();	
        		});	
		}
    );
};
*/
$scope.CreateSalesOrder = function (add_order) {
//on = 8891;
//add_order = "{'Number' : 9119, 'date':'2019-12-01T03:46:46+00:00', 'customer':{'id':10,}, 'lines': [{'lineNumber':1, 'item': {'id':126}} ]}";
console.log("The order to add is " + add_order)


//testing = "{'Number' : "+ on +", 'date':'2020-06-08T23:18:00', 'customer':{'id':5,}, 'lines': [{'lineNumber':1, 'item': {'id':53}} ]}";
	$http({
	  method: 'POST',
	  url: 'https://api.sosinventory.com/api/v2/salesorder/',
	  //data: { "number":"5565", "date":"2020-06-08T23:18:00", "customer":{ "id":5, }, "lines":[{ "lineNumber":1, 	"item":{"id":53}},{ "lineNumber":2, "item":{"id":54}}]},
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
////////////////////////////////////////////////////////////////////////////////////////// CLOSE ////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////    GRABS ALL ORDER NUMBERS - STEP 2      ////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////// GRABS ALL CUSTOMER EMAILS INSIDE SOS - STEP 4 /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$scope.customer_email = [];
$scope.customer_id = [];
async function GrabCustomersSOS_3() {
	index = 0;
	var num_customers = 44;
	var customer_id = new Array();
	var customer_name = new Array();
	var customer_email = new Array();
	try {
		for(i=1; i < num_customers; i++) {
			await GrabCustomersSOS_4(i).then(function() { // REMOVED json FROM HERE
				if(json == "empty") {
					//console.log("JSON equals empty!")
					i = num_customers;
				} else {
					//console.log("Customer in Step 1 is " + json.name + " and email is " + json.email + " and customer ID is " + json.id);
					customer_id[index] = json.id; //i;
					customer_name[index] = json.name;
					customer_email[index] = json.email;
					$scope.customer_email[index] = json.email;
					$scope.customer_id[index] = json.id;  //i;
					index = index + 1;
				}
			})
		} // CLOSE FOR LOOP
		for(j=0; j < $scope.customer_email.length; j++) {
			//console.log("Name is " + customer_name[j] + " and email is " + customer_email[j] + " and ID is " + customer_id[j])	
			//console.log("Email is " + $scope.customer_email[j] + " and ID is " + $scope.customer_id[j])	
		}	
	} catch (error) {
       //console.log(error.message);
       console.log("Broken here & number of customers is now " + num_customers);
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
				}, 250)
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////// GRABS ALL ITEMS INSIDE SOS - STEP 3 //////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$scope.item_sku = [];
$scope.item_id = [];
async function GrabItemsSOS_1() {
	index = 0;
	var num_items = 200;
	var item_id = new Array();
	var item_sku = new Array();
	try {
		for(i=1; i < num_items; i++) {
			await GrabItemsSOS_2(i, sos_code).then(function() {
				if(json == "empty") {
					//console.log("Right here if")
					//console.log("JSON equals empty!")
					i = num_items;
				} else {
					//console.log("Right here else")
					//console.log("Item in Step 1 is " + json.name + " and ID is " + i);
					item_id[index] = json.id; //i;
					item_sku[index] = json.sku;
					$scope.item_sku[index] = json.sku;
					$scope.item_id[index] = json.id; //i;
					index = index + 1;
				}
			})
		} // CLOSE FOR LOOP
		for(j=0; j < item_id.length; j++) {
			//console.log("Name is " + customer_name[j] + " and email is " + customer_email[j] + " and ID is " + customer_id[j])	
		}	
	} catch (error) {
       //console.log(error.message);
       console.log("Not Working Getting SKUs");
    }
}   

// 2nd promise
async function GrabItemsSOS_2(i) {
    return new Promise(
        (resolve, reject) => {
	        $http({
			  method: 'GET',
			  url: 'https://api.sosinventory.com/api/v2/item/'+i, 
			  /*headers: {
				  'Content-Type': 'application/x-www-form-urlencoded',
				  'Host': 'api.sosinventory.com',
				  'Authorization': 'Bearer -XOAhCv8bqoAxWMPXwcQUxqrjEBguIi78rgGlsTB_oeu0Ubjt71-HeZrY4yN9PBUVjlDizS5Qe3m8HzXMpbdAnAelpKKYth39myNl0TC1xOeayZ5YKvrFZ-mySn9KeHSw7Nl79BJo6KuLOooHjNJAWN8XTLhLl6Jb-FIUNwQaPlOwBx5AkzLhldFbC04BkfNNlf-3jo08u-K2vyDfUNHHEIY2ROZUBTb9tXxP2pRS5T-59npeolntFCncFLegtmo9z2yIptjS__fa3vKKqbHo1icCRRUMmaCdx46VOVWKRnUsgBi'
				}*/
			headers: {
				  'Content-Type': 'application/x-www-form-urlencoded',
				  'Host': 'api.sosinventory.com',
				  'Authorization': 'Bearer ' + sos_code
				}
			}).then(function successCallback(response) {
				json = response.data.data;
				setTimeout(function(){
					if(json) {
						//console.log("SKU is " + json.sku + " and I is " + i);
						resolve(json);
					} else {
						//console.log("ENDED")
						resolve("empty");
						//return;
					}
				}, 250)
			  }, function errorCallback(response) {
				  console.log("Fail")
			  });
            //resolve(json);
        }
    );
};

$scope.getSOScode = function () {
	var api_url = window.cfg.apiUrl + "sos/get_sos_code.php";
	$http.get(api_url)
		.success(function (result) {
			//console.log("The code is " + result.sos_code)
			//console.log("The date is " + result.date)
			sos_code = result.sos_code;
			$.unblockUI();
        }).error(function (result) {
			toastr.error("Could not get code");
        });
};

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

$scope.Process = function() {
	$scope.order_number_to_get = '10067200';
	var api_url = window.cfg.apiUrl + "sos/get_woo_order_processing_button.php?order_number="+ $scope.order_number_to_get;
		$http.get(api_url)
		.success(function (result) {
			console.log("TEST IS " + result.test)
			for(p=0; p < result.test; p++) {
				console.log("Index is " + p + " and SKU is "+ result.SKUs[p])
			}
			num_of_skus = result.SKUs.length;
			CUSTOMER_ID  = 1;
			ORDER_DATE = result.order_date;
			//SALES_ORDER = { "number":"5565", "date":"2020-06-08T23:18:00", "customer":{ "id":5, }, "lines":[{ "lineNumber":1, 	"item":{"id":53}},{ "lineNumber":2, "item":{"id":54}}]},
			LINES = "'lines':[";
			console.log("Earphone " + num_of_skus)
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
					//UNITPRICE = result.price_original_sku;
					UNITPRICE = result.SUBTOTALs[p];
					//SHIPPING_AMOUNT = result.SHIPPING_AMOUNT;
					//DISCOUNT = result.DISCOUNT;
					console.log("IN THIS IF STATEMENT")
				} else { 
					UNITPRICE = result.SUBTOTALs[p];
				}
				lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'class':{'id': 1, 'name': 'Alclair'}, 'quantity':" + 1 + ", 'unitprice':" + UNITPRICE + ", 'amount':" + UNITPRICE + "},";
				//lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'unitprice':" + UNITPRICE + "},";
				LINES = LINES + lineNumber_line;
			}
			LINES = LINES + "]";
			//ITEM_INDEX = $scope.item_sku.indexOf(result.SKUs[0]);
			//ITEM_ID = $scope.item_id[ITEM_INDEX];
			//ORDER_NUMBER = 1234; //$scope.order_number_to_get; //9121; 
			ORDER_NUMBER = result.order_number;
			//SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + ITEM_ID + "}}, ]}";  
			SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + "'discountAmount': " + DISCOUNT + ","  +"'taxAmount':" + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT +", " + LINES + "}";  
			//SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + LINES + "}";  
			//console.log("Sales order is " + SALES_ORDER)
			$scope.CreateSalesOrder(SALES_ORDER)
			
			//resolve(SALES_ORDER);
			$.unblockUI();
    		}).error(function (result) {
			//toastr.error("Did not grab a single order.");
			console.log("Order number that could not be found was " + order_number)
			//resolve(order_number);
			//location.reload();	
    		});

}

$scope.Process2 = function() {
	var api_url = window.cfg.apiUrl + "sos/get_number_of_woo_orders.php";
		$http.get(api_url).success(function (result) {
			num_of_orders = result.num_of_orders;
			for(p=0; p < num_of_orders; p++) {
				console.log("# " + p + " is " + result.order_numbers[p])
				
				//var api_url = window.cfg.apiUrl + "sos/get_orders_before_july_1.php";
				var api_url = window.cfg.apiUrl + "sos/get_woo_order_processing_button.php?order_number="+ result.order_numbers[p];
					$http.get(api_url)
					.success(function (result) {
						console.log("TEST IS " + result.test)
						for(p=0; p < result.test; p++) {
							//console.log("Index is " + p + " and SKU is "+ result.SKUs[p])
						}
						num_of_skus = result.SKUs.length;
						CUSTOMER_ID  = 1;
						ORDER_DATE = result.order_date;
						//SALES_ORDER = { "number":"5565", "date":"2020-06-08T23:18:00", "customer":{ "id":5, }, "lines":[{ "lineNumber":1, 	"item":{"id":53}},{ "lineNumber":2, "item":{"id":54}}]},
						LINES = "'lines':[";
						console.log("Earphone " + num_of_skus)
						var customer_info = '{"Name": ' + '"' + result.customer_name + '"' + ', "Email": ' + '"' +  result.email + '"' + '}';
						$scope.CreateCustomer(customer_info);

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
								//UNITPRICE = result.price_original_sku;
								UNITPRICE = result.SUBTOTALs[p];
								//SHIPPING_AMOUNT = result.SHIPPING_AMOUNT;
								//DISCOUNT = result.DISCOUNT;
								//console.log("IN THIS IF STATEMENT")
							} else { 
								UNITPRICE = result.SUBTOTALs[p];
							}
							lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'class':{'id': 1, 'name': 'Alclair'}, 'quantity':" + 1 + ", 'unitprice':" + UNITPRICE + ", 'amount':" + UNITPRICE + "},";
							//lineNumber_line = "{'lineNumber':" + lineNumber + ", 'item':{'id':" + ITEM_ID + "}, 'unitprice':" + UNITPRICE + "},";
							LINES = LINES + lineNumber_line;
						}
						LINES = LINES + "]";
						//ITEM_INDEX = $scope.item_sku.indexOf(result.SKUs[0]);
						//ITEM_ID = $scope.item_id[ITEM_INDEX];
						//ORDER_NUMBER = 1234; //$scope.order_number_to_get; //9121; 
						ORDER_NUMBER = result.order_number;
						//SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}, 'lines' : [{'lineNumber' : 1, 'item': {'id':" + ITEM_ID + "}}, ]}";  
						SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + "'discountAmount': " + DISCOUNT + ","  +"'taxAmount':" + TAXES + ", " + "'shippingAmount': " + SHIPPING_AMOUNT +", " + LINES + "}";  
						//SALES_ORDER = "{'Number' : " + ORDER_NUMBER + ", 'date' : '" + ORDER_DATE + "', 'customer' : {'id': " + CUSTOMER_ID + ",}," + LINES + "}";  
						//console.log("Sales order is " + SALES_ORDER)
						$scope.CreateSalesOrder(SALES_ORDER)
						
						//resolve(SALES_ORDER);
						$.unblockUI();
			    		}).error(function (result) {
						//toastr.error("Did not grab a single order.");
						console.log("Order number that could not be found was " + order_number)
						//resolve(order_number);
						//location.reload();	
			    		});
			
			}

		}).error(function (result) {
			console.log("Order number that could not be found was " + order_number)
    	});
}

	
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
$scope.getItemsFromDB();
// async await it here too
(async () => {
	await getSOSauthorizationCode();
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
	
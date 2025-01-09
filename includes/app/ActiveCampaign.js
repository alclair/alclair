swdApp.controller('ActiveCampaign', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {

$scope.init2 = function () {	
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	//practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andy", "lastName": "Swanson"}}';
	//practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andy", "lastName": "Swanson",
	//						"fieldValues":[{"field":"1", "value":"TESTING"}] }}';
	the_value = 49;
	the_value2 = 'This 49';
	// 49 - CURRENT SHOP STATUS & 50 - ESTIMATED SHIP DATE
	//practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andy", "lastName": "Swanson", "fieldValues":[{"field": "1", "value": "#1"}] }}';
	practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andy", "lastName": "Swanson", "fieldValues":[{"field": "49", "value": "#4949"}] }}';
	//practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andy2", "lastName": "Swanson", "fieldValues":[{"field": '+the_value+', "value": "#'+the_value+'"}] }}';
	//practice6 = '{ "contact": { "email": "andy@alclair.com", "fieldValues":[{"field": '+the_value+', "value": "#1"}] }}';

	
	
		console.log("WE ARE HERE !!!!")
		//return;
		
		console.log(JSON.stringify(practice6))
		$http({
			method: 'POST',
			//url: 'https://otis.alclr.co:8443/https://alclair.api-us1.com/api/3/contact/sync',
			//url: 'https://corsproxy.io/?https://alclair.api-us1.com/api/3/contact/sync',	
			//url: 'https://corsproxy.io/?key=feb7e04f&url=https://alclair.api-us1.com/api/3/contact/sync',
			url: 'https://proxy.cors.sh/https://alclair.api-us1.com/api/3/contact/sync',	 
			data: practice6,
			headers: {
				/*
				//'Access-Control-Allow-Origin': 'https://otis.alclr.co/',
				//'Content-Type': 'application/json',
				//'Content-Type': 'application/x-www-form-urlencoded',
				//'Api-Token': key_is,
				 */
				'x-cors-api-key': 'live_3961693df7a5f15e329746337e79b0eea7e3c6d0593a17bf81094674cd73d556',
				//'Access-Control-Allow-Origin': '*',
				//'Content-Type': 'application/json',					 	
				'Api-Token': key_is,
				//'Origin': 'https://otis.alclr.co:8080',
				//'Origin':'https://alclair.api-us1.com/api/3/',
			},
			//body: practice5
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
			//console.log("Fail and I is " + i)
		});
    //}, 1000)
}
	
$scope.init = function () {
//async function STEP3(customer_info) {
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	//setTimeout(function(){
		console.log("WE ARE HERE 2")
		$http({
			method: 'GET',
			// January 9th, 2023 - A PROXY SERVER WAS REQUIRED
			// Cross-Origin Resource Sharing (CORS) WAS AN ISSUE
			//https://corsproxy.io/ WAS USED
			url: 'https://corsproxy.io/?https://alclair.api-us1.com/api/3/contact/sync',			
			headers: {
				 'Access-Control-Allow-Method': 'GET',
				 //'Access-Control-Request-Method': 'GET',
				 'Access-Control-Allow-Headers': 'Content-Type, x-requested-with',
				 //'Access-Control-Request-Headers': 'Content-Type, x-requested-with',
				 //'Access-Control-Allow-Origin': 'https://otis.alclr.co/',
				 'Origin':'https://alclair.api-us1.com/api/3/',
				 //'Origin': 'https://otisdev.alclr.co/',
				 'Api-Token': key_is
			}
						
		}).then(function successCallback(response) {
			console.log("First name 3 is " + JSON.stringify(response.data.contact.firstName))
			console.log("Last name 3 is " + JSON.stringify(response.data.contact.lastName))
			console.log("All is " + JSON.stringify(response.data.contact))
			json = response.data.data;
			if(json == "empty") {
				//console.log("JSON is empty & i is " + i)
			} else {
			
			}
			
		}, function errorCallback(response) {
			console.log("ERROR HERE")
			//console.log("Fail and I is " + i)
		});
    //}, 1000)
}



$scope.init3 = function () {
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	   var api_url = window.cfg.apiUrl + "activecampaign/get_contact.php";
	   $http.get(api_url)
	   		.success(function (result) {
	   			console.log("RESULT IS " + result)
	   			console.log("TEST IS " + result.test)
				$.unblockUI();
        	}).error(function (result) {
				toastr.error("Could not get code");
        	});
}

$scope.init4 = function () {
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	practice6 = '{ "contact": { "email": "andy@alclair.com", "firstName": "Andrew2", "lastName": "Swanson", "Notes": "FAKE NOTES"}}';

		$http({
			method: 'GET',
			url: 'https://otis.alclr.co:8443/https://alclair.api-us1.com/api/3/notes/1',
			headers: {
				 'Access-Control-Allow-Method': 'GET',
				 'Access-Control-Allow-Headers': 'Content-Type, x-requested-with',
				 'Access-Control-Allow-Origin': 'https://otis.alclr.co/',
				 'Api-Token': key_is
			}
			//body: practice5
		}).then(function successCallback(response) {
			console.log("First name is " + JSON.stringify(response.data.note))
			//console.log("Last name is " + JSON.stringify(response.data.contact.lastName))
			json = response.data.data;
			if(json == "empty") {
				//console.log("JSON is empty & i is " + i)
			} else {
			
			}
		}, function errorCallback(response) {
			console.log("ERROR HERE")
			//console.log("Fail and I is " + i)
		});
    //}, 1000)
}

$scope.init5 = function () {
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	practice6 = '{"note": {"note": "NOTE TIME","relid": 2,"reltype": "Subscriber"}}';
		$http({
			method: 'PUT',
			url: 'https://otis.alclr.co:8443/https://alclair.api-us1.com/api/3/notes/1',
			data: practice6,
			headers: {
				 //'Access-Control-Allow-Method': 'GET',
				 //'Access-Control-Allow-Headers': 'Content-Type, x-requested-with',
				 //'Access-Control-Allow-Origin': 'https://otis.alclr.co/',
				 'Api-Token': key_is
			}
		}).then(function successCallback(response) {
			console.log("First name is " + JSON.stringify(response.data.note))
			//console.log("Last name is " + JSON.stringify(response.data.contact.lastName))
			json = response.data.data;
			if(json == "empty") {
				//console.log("JSON is empty & i is " + i)
			} else {
			
			}
		}, function errorCallback(response) {
			console.log("ERROR HERE")
			//console.log("Fail and I is " + i)
		});
    //}, 1000)
}



	//$scope.init();
	$scope.init2();
	//$scope.init3();
	//$scope.init4();
	//$scope.init4();
}]);




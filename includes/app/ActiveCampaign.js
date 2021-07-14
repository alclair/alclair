swdApp.controller('ActiveCampaign', ['$http', '$scope', 'AppDataService', '$upload',  '$cookies', function ($http, $scope, AppDataService, $upload, $cookies) {

$scope.init = function () {
//async function STEP3(customer_info) {
	var key_is = '9b5763099898ad2f12c93dc762b8cb49772101db84b58f0e1e692df228ae15c66c3f5bf0';
	//setTimeout(function(){
		console.log("WE ARE HERE 2")
		$http({
			method: 'GET',
			//url: 'https://alclair.api-us1.com/api/3/contacts/16025', 
			url: 'https://otis.alclr.co:8080/https://alclair.api-us1.com/api/3/contacts/16025',
			headers: {
				 'Access-Control-Allow-Method': 'GET',
				 //'Access-Control-Request-Method': 'GET',
				 'Access-Control-Allow-Headers': 'Content-Type, x-requested-with',
				 //'Access-Control-Request-Headers': 'Content-Type, x-requested-with',
				 'Access-Control-Allow-Origin': 'https://otis.alclr.co/',
				 //'Origin': 'https://otisdev.alclr.co/',
				 'Api-Token': key_is
			}
		}).then(function successCallback(response) {
			console.log("First name is " + JSON.stringify(response.data.contact.firstName))
			console.log("Last name is " + JSON.stringify(response.data.contact.lastName))
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
	$scope.init();
}]);
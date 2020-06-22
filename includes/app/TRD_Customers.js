swdApp.controller('Customers', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {

   $scope.create_new= 0;
   //$scope.customer = [];
   
	$scope.LoadAddress2 = function (id_number) {
        //myblockui();
        $scope.customer = [];
        console.log("Customer ID " + id_number)
        
        var api_url = window.cfg.apiUrl + "trd/get_customers.php?customer_id=" + id_number;
        //alert(api_url);
        $http.get(api_url)
		.success(function (result) {
			$scope.customer = result.data;
			console.log("Test is " + result.test)
			if(result.data) {
				//console.log("RESULT is " + JSON.stringify(result.data[0]))
			} else {
				console.log("INSIDE ELSE");
			}
			if($scope.the_customer == '') {
		       $scope.customer.customer = "";  
		       $scope.customer.contact_name = "";  
		       $scope.customer.address = "";  
		       $scope.customer.email = "";  
		       $scope.customer.phone = "";   
		       $scope.customer.notes = "";  		         
		         
		       	$scope.SearchText = [];    
			}
           // $.unblockUI();
		}).error(function (result) {
			toastr.error("Get load address error.");
		});
    };  

   $scope.Customers = [];
	$scope.getCustomers= function () {
		$scope.Customers = 0;
		var api_url = window.cfg.apiUrl + "trd/search_customers.php";
			$http.get(api_url).success(function (data) {
			console.log("HERE2222")
			$scope.Customers = data.data;
			if($scope.SearchText) {
				console.log("Search Text is " + $scope.SearchText)
				//$scope.LoadAddress2($scope.SearchText);		
			}
		})
	}
     
    
    $scope.DeleteCustomer = function(id_number){
	    console.log("Search is " + id_number)
	  	var api_url = window.cfg.apiUrl + "trd/delete_customer.php?customer_id=" + id_number;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.customer),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
       		})
			.success(function (result) {        
				$.unblockUI();                
				console.log(result.test + " and " + result.code)
				if (result.code == "success") {	
					console.log(result.test)
					toastr.success("Address deleted."); 
					setTimeout(function(){
						location.reload("");	
						//$scope.LoadAddress2($scope.SearchText)
					}, 500); 
          		} else {
		 			//toastr.error(result.message == undefined ? result.data : result.message);
		 		}
			 }).error(	function (data) {
				console.log("Code is " + result.code)
	 			toastr.error("Insert reviwer error.");
		 	});
	  	
    }      
    
    $scope.Customer = function(){	    
	    if($scope.SearchText > 1  ) {
	    	console.log("Search Text is " + $scope.SearchText)
			var new_contact = 0; // NO, NOT A NEW CONTACT
			var customer_id = $scope.SearchText;
		} else {
			var new_contact = 1; // YES, NEW CONTACT
			var customer_id = 0;
		}
	    
	    console.log("New contact is " + $scope.customer.contact_name+ " and ID is " + $scope.customer.customer)
		var api_url = window.cfg.apiUrl + "trd/customers.php?customer_id=" + customer_id + "&new_contact=" + new_contact;
		myblockui();
			$http({
				method: 'POST',
				url: api_url,
				data: $.param($scope.customer),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
       		}).success(function (result) {        
				$.unblockUI();                
				console.log(result.test)
				//return;
				if (result.code == "success") {
					toastr.success("Customer Saved!");
					setTimeout(function(){
						//location.reload("");	
						//$scope.LoadAddress2($scope.SearchText)
					}, 500); 
          		} else {
		 			//toastr.error(result.message == undefined ? result.data : result.message);
		 		}
			 }).error(	function (data) {
				console.log("Code is " + result.code)
	 			toastr.error("Insert reviwer error.");
		 	});
    }
      
        
           
    $scope.init=function()
    {
	    
	    $scope.getCustomers();

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            //$scope.minimum_barrel_warning = data.minimum_barrel_warning;
            //$scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);


	

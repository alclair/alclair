async function Step_1b() {
	console.log("In Step 1")
	try {
		for(i=1; i < 10; i++) {
			console.log("In Step 1 and I is " + i)
			let customer = await Step_2(i);
			console.log("Customer is " + customer.name + " and I is " + i);
		} // CLOSE FOR LOOP
	} 
    catch (error) {
       console.log(error.message);
       console.log("NOT WORKING");
    }
}   

// Promise
//const Step_1 = new Promise(
async function Step_1() {
	return new Promise(
    	(resolve, reject) => {
			for(i=1; i < 10; i++) {
				console.log("In Step 1 and I is " + i)
				let customer = await Step_2(i).then(function() {
				console.log("Customer is " + customer.name + " and I is " + i);
				}
			} // CLOSE FOR LOOP
			resolve();
    	}
	);
}
// 2nd promise
async function Step_2(i) {
	console.log("In Step 2 " + " and I is " + i)
    return new Promise(
        (resolve, reject) => {
	        console.log("IN HERE with " + i)
	        /*$http({
			  method: 'GET',
			  url: 'https://api.sosinventory.com/api/v2/customer/'+i, 
			  headers: {
				  'Content-Type': 'application/x-www-form-urlencoded',
				  'Host': 'api.sosinventory.com',
				  'Authorization': 'Bearer -XOAhCv8bqoAxWMPXwcQUxqrjEBguIi78rgGlsTB_oeu0Ubjt71-HeZrY4yN9PBUVjlDizS5Qe3m8HzXMpbdAnAelpKKYth39myNl0TC1xOeayZ5YKvrFZ-mySn9KeHSw7Nl79BJo6KuLOooHjNJAWN8XTLhLl6Jb-FIUNwQaPlOwBx5AkzLhldFbC04BkfNNlf-3jo08u-K2vyDfUNHHEIY2ROZUBTb9tXxP2pRS5T-59npeolntFCncFLegtmo9z2yIptjS__fa3vKKqbHo1icCRRUMmaCdx46VOVWKRnUsgBi'
				}
			}).then(function successCallback(response) {
				json = response.data.data;
				resolve(json);

			  }, function errorCallback(response) {
				  console.log("Fail")
			  });
			  /*
        }
    );
};

// async await it here too
(async () => {
    await Step_1b();
})();	
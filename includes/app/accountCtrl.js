swdApp.controller('accountCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.vm = {};
    
    console.log("WE ARE HERE")
    $scope.save = function () {
        var api_url = window.cfg.apiUrl + "account/update.php";
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             //alert(result.code);
             if (result.code == "success") {
                 $.unblockUI();
                 toastr.success("Your profile was updated successfully.");
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("error to upload your profile.");

         });
        console.log($scope.vm);
    };
    $scope.LoadData=function()
    {
        var api_url = window.cfg.apiUrl + "account/myprofile.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.vm = data.data;
        });
    }
    $scope.init=function()
    {
        $scope.LoadData();
    }
    $scope.init();
}]);


swdApp.controller('accountLoginCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.greeting = 'Hola!';
    $scope.vm = { username: '', password: '' };
    console.log("JS Line 49" + window.cfg.apiUrl);
    $scope.Login = function () {
        var api_url = window.cfg.apiUrl + 'account/login.php';
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             //alert(result.code);
			 console.log("you are in " + JSON.stringify(result) )
             if (result.code == "success") {
                 $.unblockUI();                 
                 window.location.href = window.cfg.rootUrl + "/home/index";
                 //console.log("ASDFASDF")
                 //window.location.href = window.cfg.rootUrl + "/alclair/qc_list";
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("invalid username or password.");

         });
    }
}]);
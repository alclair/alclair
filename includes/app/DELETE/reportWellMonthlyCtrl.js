swdApp.controller('reportWellMonthlyCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2015", "2016", "2017", "2018"];
    $scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    $scope.year = "2015";
    $scope.month = "10";

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    var labels_disposal = ["CARTWRIGHT", "EAST FORK", "KILLDEER WEST"];
    var labels_water = ["FLOWBACK", "PRODUCTION", "DIRTY WATER"];

    $scope.loadWellMonthlyDisposal = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/well_monthly_disposal.php?year=" + $scope.year + "&month=" + $scope.month;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month) == -1)
                totalmonthday = 30;

            var d1 = [], d2 = [], d3 = [];
            for (var i = 0; i < totalmonthday; i++) {
                d1.push({ y: 0 });
                d2.push({ y: 0 });
                d3.push({ y: 0 });
            }
            var layers = [d1, d2, d3];

            for (var i = 0; i < result.data.length; i++) {
                var created = result.data[i].created[0] == "0" ? parseInt(result.data[i].created[1]) : parseInt(result.data[i].created);
                var disposal = result.data[i].disposal_well_name;
                var total_barrels = result.data[i].total_barrels_delivered;
                created = created - 1;
                
                if (disposal == "CARTWRIGHT") {
                    layers[0][created].y = total_barrels;
                }
                else if (disposal == "EAST FORK") {
                    layers[1][created].y = total_barrels;
                }
                else if (disposal == "KILLDEER WEST") {
                    layers[2][created].y = total_barrels;
                }                
            }
            console.log(layers);

            d3DarwStackGroup(layers, "wellmonthlydisposal", "grouped", "stacked", labels_disposal);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.loadWellMonthlyMonthly = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/well_monthly_monthly.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);            

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;

            var d1 = [], d2 = [], d3 = [];
            for (var i = 0; i < totalmonthday; i++) {
                d1.push({ y: 0 });
                d2.push({ y: 0 });
                d3.push({ y: 0 });
            }
            var layers = [d1, d2, d3];

            for (var i = 0; i < result.data.length; i++) {
                var created = result.data[i].created[0] == "0" ? parseInt(result.data[i].created[1]) : parseInt(result.data[i].created);
                var water_type = result.data[i].water_type;
                var total_barrels = result.data[i].total_barrels_delivered;
                created = created - 1;

                if (water_type == "FLOWBACK") {
                    layers[0][created].y = total_barrels;
                }
                else if (water_type == "PRODUCTION") {
                    layers[1][created].y = total_barrels;
                }
                else if (water_type == "DIRTY WATER") {
                    layers[2][created].y = total_barrels;
                }
            }
            console.log(layers);

            d3DarwStackGroup(layers, "wellmonthlymonthly", "grouped_month", "stacked_month", labels_water);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.loadWellMonthlyDisposal();
    $scope.loadWellMonthlyMonthly();

    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {
    });

    $scope.selectMonth = function () {
        $("#wellmonthlydisposal").html("");
        $scope.loadWellMonthlyDisposal();
        
    };

    $scope.selectMonth_Month = function () {
        $("#wellmonthlymonthly").html("");
        $scope.loadWellMonthlyMonthly();

    };
}]);

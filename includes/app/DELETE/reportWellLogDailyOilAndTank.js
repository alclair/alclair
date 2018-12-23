swdApp.controller('reportWellLogDailyOilAndTank', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2015", "2016", "2017", "2018"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    
    $scope.monthRange = AppDataService.monthRange;

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")
    
    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    $scope.colorRange = ["#36A536", "#FF7F0E", "#00FFFF", "#000000", "#FF0000", "#D676DC", "#5656E6"];
    $scope.labelRange = [];
    var labels = ["Oil Sold", "Oil Tank 1", "Oil Tank 2", "Oil Tank 3", "Gun Barrel", "Skim Tank 1", "Skim Tank 2"];
    for (var i = 0; i < labels.length; i++) {
        $scope.labelRange.push({ text: labels[i], color: $scope.colorRange[i] });
    }

    $scope.loadWellMonthlyPie = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/welllog_daily_oil_and_tank.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);            

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;

            var data = [];
            for (var i = 0; i < labels.length; i++) {
                var linedata = [];
                for (var j = 0; j < totalmonthday; j++) {
                    linedata.push(0);
                }
                data.push(linedata);
            }

            for (var i = 0; i < result.data.length; i++) {
                var item = result.data[i];

                var created = item.created[0] == "0" ? parseInt(item.created[1]) : parseInt(item.created);
                created = created - 1;

                data[0][created] = parseFloat(item.total_oil_sold);
                data[1][created] = parseFloat(item.total_oil_tank1);
                data[2][created] = parseFloat(item.total_oil_tank2);
                data[3][created] = parseFloat(item.total_oil_tank3);
                data[4][created] = parseFloat(item.total_gun);
                data[5][created] = parseFloat(item.total_skim_tank1);
                data[6][created] = parseFloat(item.total_skim_tank2);
            }

            console.log(data);

            d3DarwMultiLineChart(data, "div_welllog_daily_oil_and_tank", $scope.colorRange, labels);

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };    

    $scope.loadWellMonthlyPie();
    
    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {
    });


    $scope.selectMonth = function () {
        $("#div_welllog_daily_oil_and_tank").html("");
        $scope.loadWellMonthlyPie();

    };
}]);

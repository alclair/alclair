swdApp.controller('reportDailyWaterByOilCompany', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2015", "2016", "2017", "2018"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    
    $scope.monthRange = AppDataService.monthRange;

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM");

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    var labels = [];

    $scope.labelRange = [];

    $scope.loadChart = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/daily_water_by_oil_company.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);
            if (result.data.length == 0)
                return;
            $scope.labelRange = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;

            for (var i = 0; i < result.data.length; i++) {
                var name = result.data[i].name;
                if (labels.indexOf(name) == -1) {
                    labels.push(name);
                }
            }


            var layers = [];
            for (var i = 0; i < labels.length; i++) {
                var layer = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer.push({ y: 0 });
                }
                layers.push(layer);
            }

            for (var i = 0; i < result.data.length; i++) {
                var created = result.data[i].created[0] == "0" ? parseInt(result.data[i].created[1]) : parseInt(result.data[i].created);
                var name = result.data[i].name;
                var total_barrels = result.data[i].total_barrels_delivered;
                created = created - 1;

                layers[labels.indexOf(name)][created].y = total_barrels;
            }
            console.log(layers);

            //var color = d3.scale.linear().domain([0, labels.length - 1]).range(["#aad", "#556"]);
            var color = d3.scale.category20();
            for (var i = 0; i < labels.length; i++) {
                $scope.labelRange.push({ text: labels[i], color: color(i) });
            }

            d3DarwStackGroup(layers, "div_daily_water_by_oil_company", "daily_water_by_oil_copany_grouped", "daily_water_by_oil_copany_stacked", labels, { isgroup: false, category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.loadChart();

    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {
    });

    $scope.selectMonth = function () {
        $("#div_daily_water_by_oil_company").html("");
        $scope.loadChart();

    };
}]);

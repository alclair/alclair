swdApp.controller('reportWellMonthlyPieCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2015", "2016", "2017", "2018"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    
    $scope.monthRange = AppDataService.monthRange;

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    $scope.labelRange = [];
    $scope.colorRange = ["#36A536", "#FF7F0E", "#1F77B4"];

    $scope.loadWellMonthlyPie = function (cal_type) {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/well_monthly_pie.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell + "&cal_type=" + cal_type;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);
            $scope.labelRange = [];

            var total = 0;
            var data = [];

            for (var i = 0; i < result.data.length; i++) {
                var dat = result.data[i];
                dat.total_barrels_delivered = parseFloat(dat.total_barrels_delivered);
                if (dat.water_type != null) {                    
                    total = total + dat.total_barrels_delivered;
                }
            }

            for (var i = 0; i < result.data.length; i++) {
                var dat = result.data[i];
                if (dat.water_type != null) {
                    var age = Math.round(dat.total_barrels_delivered / total * 10000) / 100;
                    data.push({ label: dat.water_type, age: age + "%", population: dat.total_barrels_delivered });
                    $scope.labelRange.push({ text: dat.water_type + "(" + dat.total_barrels_delivered + ")", color: $scope.colorRange[data.length - 1] });
                }
            }

            d3DrawPieChart(data, "well_month_report_pie_" + cal_type, $scope.colorRange);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.init = function (cal_type) {
        $scope.loadWellMonthlyPie(cal_type);
    };

    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {
    });

    $scope.selectMonth_Pie = function (cal_type) {
        $("#well_month_report_pie_" + cal_type).html("");
        $scope.loadWellMonthlyPie(cal_type);

    };
}]);

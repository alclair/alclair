swdApp.controller('reportWellMonthlyByDeliveryMethodCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2015", "2016", "2017", "2018"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

	$scope.monthRange = AppDataService.monthRange;

    $scope.typeList = ["Barrels", "Cost"];
    $scope.type = $scope.typeList[0];

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    $scope.labels = [];
    $scope.labelRange = [];

    $scope.loadWellMonthlyMonthly = function () {
        myblockui();

        if ($scope.type == "Barrels")
            var api_url = window.cfg.apiUrl + "reports/well_monthly_delivery_method_barrel.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell;
        else
            var api_url = window.cfg.apiUrl + "reports/well_monthly_delivery_method_cost.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&disposalwell=" + $scope.disposalwell;


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

            var layers = [];
            for (var i = 0; i < $scope.labels.length; i++) {
                var layer = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer.push({ y: 0 });
                }
                layers.push(layer);
            }

            for (var i = 0; i < result.data.length; i++) {
                var created = result.data[i].created[0] == "0" ? parseInt(result.data[i].created[1]) : parseInt(result.data[i].created);
                var delivery_method = result.data[i].delivery_method;
                var total_barrels = result.data[i].total_barrels_delivered;
                created = created - 1;

                layers[$scope.labels.indexOf(delivery_method)][created].y = total_barrels;
            }
            console.log(layers);

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels.length; i++) {
                $scope.labelRange.push({ text: $scope.labels[i], color: color(i) });
            }

            if ($scope.type == "Barrels")
                d3DarwStackGroup(layers, "wellmonthlymonthly_delivery_method", "grouped_month_delivery_method", "stacked_month_delivery_method", $scope.labels, { category20: true });
            else
                d3DarwStackGroup(layers, "wellmonthlymonthly_delivery_method", "grouped_month_delivery_method", "stacked_month_delivery_method", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };


    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {
    });    

    var deliverymethod = AppDataService.DeliveryMethodList;
    for (var i = 0; i < deliverymethod.length; i++) {
        if (deliverymethod[i].value != "0")
            $scope.labels.push(deliverymethod[i].value);
    }
    $scope.loadWellMonthlyMonthly();

    $scope.selectMonth_Month = function () {
        $("#wellmonthlymonthly_delivery_method").html("");
        $scope.loadWellMonthlyMonthly();

    };
}]);

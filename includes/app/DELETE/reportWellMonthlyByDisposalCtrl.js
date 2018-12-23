swdApp.controller('reportWellMonthlyByDisposalCtrl', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017", "2018"];
    $scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    $scope.year = moment().format("YYYY");
    $scope.month =moment().format("MM");
    
    $scope.labels = [];
    $scope.labelRange = [];

    $scope.loadWellMonthlyDisposal = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "reports/well_monthly_disposal.php?year=" + $scope.year + "&month=" + $scope.month;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);
            if (result.data.length == 0)
                return;
            $scope.labelRange = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month) == -1)
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
                var disposal = result.data[i].disposal_well_name;
                var total_barrels = result.data[i].total_barrels_delivered;
                created = created - 1;
                                
                layers[$scope.labels.indexOf(disposal)][created].y = total_barrels;
            }
            console.log(layers);

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels.length; i++) {
                $scope.labelRange.push({ text: $scope.labels[i], color: color(i) });
            }

            d3DarwStackGroup(layers, "wellmonthlydisposal", "grouped", "stacked", $scope.labels, { category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };     

    AppDataService.loadDisposalWellList(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels.push(result.data[i].common_name);
        }
        $scope.loadWellMonthlyDisposal();
    }, function () { });

    $scope.selectMonth = function () {
        $("#wellmonthlydisposal").html("");
        $scope.loadWellMonthlyDisposal();
        
    };
}]);

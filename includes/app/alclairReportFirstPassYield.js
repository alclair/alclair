swdApp.controller('reportFirstPassYield', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2017", "2018", "2019"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    
    $scope.monthRange = AppDataService.monthRange;

    $scope.typeList = ["Barrels", "Cost"];
    $scope.type = $scope.typeList[0];

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;
    
    $scope.monitorList = [];
    $scope.IEM = 0;

    $scope.labels = [];
    $scope.labelRange = [];
    
    $scope.labels2 = [];
    $scope.labelRange2 = [];
    
    $scope.labels3 = [];
    $scope.labelRange3 = [];
    
    $scope.labels4 = [];
    $scope.labelRange4 = [];
    
    $scope.makeExcel = function () {

	   myblockui();
      
var api_url = window.cfg.apiUrl + "export/alclair_excel_export_dashboard.php?year=" + $scope.year_month + "&month=" + $scope.month_month;

        $http.get(api_url).success(function (result) {
            $.unblockUI();     
            $scope.data =  result.data;
            console.log("Test 1 is " + result.test1)
            toastr.success("The Excel document was sent to your e-mail.");
            console.log("Data is " + JSON.stringify(result.data));
            console.log("Name is " + JSON.stringify(result.data2));
            console.log("Code is " + JSON.stringify(result.code));
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.loadFirstPassYield = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result) {
		console.log("Testing is " + JSON.stringify(result.data))
            $.unblockUI();
            //console.log(JSON.stringify(result.data));
            //console.log("data length is " + result.data.length)
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
                var pass_or_fail = result.data[i].pass_or_fail;
                //var water_type = result.data[i].water_type;
                var num_status = result.data[i].num_status;
                created = created - 1;
                layers[$scope.labels.indexOf(pass_or_fail)][created].y = num_status;
            }
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels.length; i++) {
                $scope.labelRange.push({ text: $scope.labels[i], color: color(i) });
            }

            //if ($scope.type == "Barrels")
               d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { category20: true });
            //else
                //d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    $scope.loadFirstPassYield_I = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield_initial.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result2) {

            $.unblockUI();
            //console.log(JSON.stringify(result.data));
            //console.log("data length is " + result.data.length)
            if (result2.data.length == 0)
                return;
            $scope.labelRange2 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
            var layers2 = [];
            for (var i = 0; i < $scope.labels2.length; i++) {
                var layer2 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer2.push({ y: 0 });
                }
                layers2.push(layer2);
            }

            for (var i = 0; i < result2.data.length; i++) {
                var created2 = result2.data[i].created[0] == "0" ? parseInt(result2.data[i].created[1]) : parseInt(result2.data[i].created);
                var pass_or_fail2 = result2.data[i].pass_or_fail;
                //var water_type = result.data[i].water_type;
                var num_status2 = result2.data[i].num_status;
                created2 = created2 - 1;
                layers2[$scope.labels2.indexOf(pass_or_fail2)][created2].y = num_status2;
            }
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels2.length; i++) {
                $scope.labelRange2.push({ text: $scope.labels2[i], color: color(i) });
            }

            //if ($scope.type == "Barrels")
                d3DarwStackGroup(layers2, "firstpassyield_initial", "grouped_month_initial", "stacked_month_initial", $scope.labels2, { category20: true });
            //else
                //d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.loadFirstPassYield_initial_PassFail = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield_initial_PassFail.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result3) {
			
            $.unblockUI();
            //console.log(JSON.stringify(result.data));
            //console.log("data length is " + result.data.length)
            if (result3.data.length == 0)
                return;
            $scope.labelRange3 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
            var layers3 = [];
            for (var i = 0; i < $scope.labels3.length; i++) {
                var layer3 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer3.push({ y: 0 });
                }
                layers3.push(layer3);
            }

            for (var i = 0; i < result3.data.length; i++) {
                var created3 = result3.data[i].created[0] == "0" ? parseInt(result3.data[i].created[1]) : parseInt(result3.data[i].created);
                var pass_or_fail3 = result3.data[i].initial_pass_or_fail;
                var num_status3 = result3.data[i].num_status;
                created3 = created3 - 1;
                layers3[$scope.labels3.indexOf(pass_or_fail3)][created3].y = num_status3;
            }
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels3.length; i++) {
                $scope.labelRange3.push({ text: $scope.labels3[i], color: color(i) });
            }

            //if ($scope.type == "Barrels")
                d3DarwStackGroup(layers3, "firstpassyield_initial_PassFail", "grouped_month_initial_PassFail", "stacked_month_initial_PassFail", $scope.labels3, { category20: true });
            //else
                //d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
    $scope.loadFirstPassYield_Failure = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield_failure.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result4) {
			
			//console.log("FACEPLATE is " + JSON.stringify(result3.test1));
			//console.log("SOUND is " + result3.test2)
			//console.log("SHELLS is " + result3.test3)
			//console.log("JACKS is " + result3.test4)
			//console.log("PORTS is " + result3.test5)
			//console.log("ARTWORK is " + result3.test6)
            $.unblockUI();
            //console.log(JSON.stringify(result.data));
            //console.log("data length is " + result.data.length)
            if (result4.data.length == 0)
                return;
            $scope.labelRange4 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
            var layers4 = [];
            for (var i = 0; i < $scope.labels4.length; i++) {
                var layer4 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer4.push({ y: 0 });
                }
                layers4.push(layer4);
            }
			
			console.log("Testing is " + JSON.stringify(result4.data))
            for (var i = 0; i < result4.data.length; i++) {
                var created4 = result4.data[i].created[0] == "0" ? parseInt(result4.data[i].created[1]) : parseInt(result4.data[i].created);
                var pass_or_fail4 = result4.data[i].pass_or_fail;
                //var water_type = result.data[i].water_type;
                var num_status4 = result4.data[i].num_status;
                created4 = created4 - 1;
                layers4[$scope.labels4.indexOf(pass_or_fail4)][created4].y = num_status4;
            }
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels4.length; i++) {
                $scope.labelRange4.push({ text: $scope.labels4[i], color: color(i) });
            }

            //if ($scope.type == "Barrels")
                d3DarwStackGroup(layers4, "firstpassyield_failure", "grouped_month_failure", "stacked_month_failure", $scope.labels4, { category20: true });
            //else
                //d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    


    
    AppDataService.loadMonitorList(null, null, function (result) {
    	$scope.monitorList = result.data;
    	$scope.monitorList.unshift({ id: 0, name: "All" });
    }, function (result) { 
    });

    AppDataService.loadStatusTypeList(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels.push(result.data[i].type);
            $scope.labels3.push(result.data[i].type);
        }
	}, function () { });
	
    AppDataService.loadStatusTypeList_initial(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels2.push(result.data[i].type);
        }    
    }, function () { });
    AppDataService.loadStatusTypeList_failure(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels4.push(result.data[i].type);
            console.log("labels are " + $scope.labels4)
        }    
    }, function () { });
    
	$scope.loadFirstPassYield();
	$scope.loadFirstPassYield_initial_PassFail();
	$scope.loadFirstPassYield_I();	
	$scope.loadFirstPassYield_Failure();	

    $scope.selectMonth_Month = function () {
        $("#firstpassyield").html("");
        $scope.loadFirstPassYield();
        $("#firstpassyield_initial_PassFail").html("");
        $scope.loadFirstPassYield_initial_PassFail();
        $("#firstpassyield_initial").html("");
        $scope.loadFirstPassYield_I();
         $("#firstpassyield_failure").html("");
        $scope.loadFirstPassYield_Failure();
    };
    
}]);

swdApp.controller('reportFirstPassYield_initial', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2017"];
    //$scope.monthRange = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
    
    $scope.monthRange = AppDataService.monthRange;

    $scope.typeList = ["Barrels", "Cost"];
    $scope.type = $scope.typeList[0];

    $scope.year_month = moment().format("YYYY");
    $scope.month_month = moment().format("MM")

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;
    
    $scope.monitorList = [];
    $scope.IEM = 0;

    $scope.labels = [];
    $scope.labelRange = [];


    $scope.loadFirstPassYield = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield_initial.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result) {

            $.unblockUI();
            //console.log(JSON.stringify(result.data));
            //console.log("data length is " + result.data.length)
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
                var pass_or_fail = result.data[i].pass_or_fail;
                //var water_type = result.data[i].water_type;
                var num_status = result.data[i].num_status;
                created = created - 1;
                layers[$scope.labels.indexOf(pass_or_fail)][created].y = num_status;
            }
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels.length; i++) {
                $scope.labelRange.push({ text: $scope.labels[i], color: color(i) });
            }

            //if ($scope.type == "Barrels")
                d3DarwStackGroup(layers, "firstpassyield_initial", "grouped_month_initial", "stacked_month_initial", $scope.labels, { category20: true });
            //else
                //d3DarwStackGroup(layers, "firstpassyield", "grouped_month", "stacked_month", $scope.labels, { prefix: '$', category20: true });
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
            
    AppDataService.loadMonitorList(null, null, function (result) {
    	$scope.monitorList = result.data;
    	$scope.monitorList.unshift({ id: 0, name: "All" });
    }, function (result) { 
    });

    AppDataService.loadStatusTypeList(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels.push(result.data[i].type);
        }  
    }, function () { });
    
	$scope.loadFirstPassYield();
    $scope.selectMonth_Month = function () {
        $("#firstpassyield_initial").html("");
        $scope.loadFirstPassYield();

    };
}]);


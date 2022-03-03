swdApp.controller('reportFirstPassYield', ['$http', '$scope', 'AppDataService', function ($http, $scope, AppDataService) {
    $scope.yearRange = ["2018", "2019", "2020", "2021", "2022"];
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
    
    $scope.labels5 = [];
    $scope.labelRange5 = [];
    
    $scope.labels6 = [];
    $scope.labelRange6 = [];

	$scope.labels7 = [];
	$scope.labelRange7 = [];

    
    $scope.makeExcel = function () {

	   myblockui();
	   var api_url = window.cfg.apiUrl + "export/alclair_excel_export_dashboard.php?year=" + $scope.year_month + "&month=" + $scope.month_month;
        $http.get(api_url).success(function (result) {
            $.unblockUI();     
            $scope.data =  result.data;
            //console.log("Test 1 is " + result.test1)
            toastr.success("The Excel document was sent to your e-mail.");
            //console.log("Data is " + JSON.stringify(result.data));
            //console.log("Name is " + JSON.stringify(result.data2));
            //console.log("Code is " + JSON.stringify(result.code));
        }).error(function (result) {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };

    $scope.loadFirstPassYield = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairFirstPassYield.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result) {
		//console.log("Testing is " + JSON.stringify(result.data))
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
			
		   //console.log("Testing is " + JSON.stringify(result4.data))
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
    
    $scope.loadImpressionsReceived = function () {
        myblockui();
		
		var api_url = window.cfg.apiUrl + "reports/alclairImpressionsReceived.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result5) {
			$scope.num_impressions = result5.num_impressions;
			$scope.num_shipped = result5.num_shipped;
            $.unblockUI();
            //console.log(JSON.stringify(result5.data));
            //console.log("data length is " + result.data.length)
            if (result5.data.length == 0)
                return;
            $scope.labelRange5 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
			 //$scope.labels5 = "Count"; 
            var layers5 = [];
            for (var i = 0; i < $scope.labels5.length; i++) {
                var layer5 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer5.push({ y: 0 });
                }
                layers5.push(layer5);
            }

			/*
		     //console.log("Testing is " + JSON.stringify(result5.data))
            for (var i = 0; i < result5.data.length; i++) {
                //var created5 = result5.data[i].created[0] == "0" ? parseInt(result5.data[i].created[1]) : parseInt(result4.data[i].created);
                var the_day5 = result5.data[i].the_day[0] == "0" ? parseInt(result5.data[i].the_day[1]) : parseInt(result5.data[i].the_day);
                //var pass_or_fail5 = " # of Impressions";
                var pass_or_fail5 = result5.data[i].type;
                var num_days5 = result5.data[i].num_in_day;
                
                //created5 = created5 - 1;
                the_day5 = the_day5 - 1;
                layers5[$scope.labels5.indexOf(pass_or_fail5)][the_day5].y = num_days5;
            }*/
            
            for (var i = 0; i < result5.data.length; i++) {
                var created5 = result5.data[i].the_day[0] == "0" ? parseInt(result5.data[i].the_day[1]) : parseInt(result5.data[i].the_day);
                var pass_or_fail5 = result5.data[i].type;
                //var water_type = result.data[i].water_type;
                var num_status5 = result5.data[i].num_in_day;
                created5 = created5 - 1;
                //console.log("I is " + i)
                //console.log("C is " + created5 + " PF is " + pass_or_fail5 + " Num is " + num_status5)
                layers5[$scope.labels5.indexOf(pass_or_fail5)][created5].y = num_status5;
            }

            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels5.length; i++) {
                $scope.labelRange5.push({ text: $scope.labels5[i], color: color(i) });
            }

                d3DarwStackGroup(layers5, "impressions_received_date", "grouped_impression_date", "stacked_impression_date", $scope.labels5, { category20: true });

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
$scope.loadRepairsReceived = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairRepairsReceived.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result6) {
	        $scope.num_repairs2 = result6.num_repairs;
			$scope.num_shipped2 = result6.num_shipped;
			//console.log("TEST RETURNS " + result6.test)
	        //return;

            $.unblockUI();
            //console.log(JSON.stringify(result5.data));
            //console.log("data length is " + result.data.length)
            if (result6.data.length == 0)
                return;
            $scope.labelRange6 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
            var layers6 = [];
            for (var i = 0; i < $scope.labels6.length; i++) {
                var layer6 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer6.push({ y: 0 });
                }
                layers6.push(layer6);
            }
			
			for (var i = 0; i < result6.data.length; i++) {
                var created6 = result6.data[i].the_day[0] == "0" ? parseInt(result6.data[i].the_day[1]) : parseInt(result6.data[i].the_day);
                var pass_or_fail6 = result6.data[i].type;
                //var water_type = result.data[i].water_type;
                var num_status6 = result6.data[i].num_in_day;
                created6 = created6 - 1;
                //console.log("I is " + i)
                //console.log("C is " + created6 + " PF is " + pass_or_fail6 + " Num is " + num_status6)
                layers6[$scope.labels6.indexOf(pass_or_fail6)][created6].y = num_status6;
            }

			/*
		     //console.log("Testing is " + JSON.stringify(result5.data))
            for (var i = 0; i < result6.data.length; i++) {
                //var created5 = result5.data[i].created[0] == "0" ? parseInt(result5.data[i].created[1]) : parseInt(result4.data[i].created);
                var the_day6 = result6.data[i].the_day[0] == "0" ? parseInt(result6.data[i].the_day[1]) : parseInt(result6.data[i].the_day);
                var pass_or_fail6 = " # of Repairs";
                var num_days6 = result6.data[i].num_days;
                
                //created5 = created5 - 1;
                the_day6 = the_day6 - 1;
                layers6[$scope.labels6.indexOf(pass_or_fail6)][the_day6].y = num_days6;
            }*/
            //console.log("Layers is " + JSON.stringify(layers));

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels6.length; i++) {
                $scope.labelRange6.push({ text: $scope.labels6[i], color: color(i) });
            }

                d3DarwStackGroup(layers6, "repairs_received_date", "grouped_repairs_date", "stacked_repairs_date", $scope.labels6, { category20: true });

        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    
   $scope.loadRepairsAndFitNumbers = function () {
        myblockui();

        var api_url = window.cfg.apiUrl + "reports/alclairRepairsandFitIssues.php?year=" + $scope.year_month + "&month=" + $scope.month_month + "&IEM=" + $scope.IEM;

        $http.get(api_url).success(function (result7) {
			$scope.num_repairs = result7.num_repairs;
			$scope.num_fit_issues = result7.num_fit_issues;
            $.unblockUI();

            if (result7.data.length == 0)
                return;
            $scope.labelRange7 = [];

            var monthday = ["01", "03", "05", "07", "08", "10", "12"];
            var totalmonthday = 31;
            if (monthday.indexOf($scope.month_month) == -1)
                totalmonthday = 30;
          
            var layers7 = [];
            for (var i = 0; i < $scope.labels7.length; i++) {
                var layer7 = [];
                for (var j = 0; j < totalmonthday; j++) {
                    layer7.push({ y: 0 });
                }
                layers7.push(layer7);
            }
			
			for (var i = 0; i < result7.data.length; i++) {
                var created7 = result7.data[i].the_day[0] == "0" ? parseInt(result7.data[i].the_day[1]) : parseInt(result7.data[i].the_day);
                var pass_or_fail7 = result7.data[i].type;

                var num_status7 = result7.data[i].num_in_day;
                created7 = created7 - 1;
                //console.log(num_status7)
                //console.log(pass_or_fail7)
                console.log($scope.labels7.indexOf('# of Repairs Received'))
                layers7[$scope.labels7.indexOf(pass_or_fail7)][created7].y = num_status7;
            }

            var color = d3.scale.category20();
            for (var i = 0; i < $scope.labels7.length; i++) {
                $scope.labelRange7.push({ text: $scope.labels7[i], color: color(i) });
            }

                d3DarwStackGroup(layers7, "repairs_and_fit_numbers", "grouped_repairs_date2", "stacked_repairs_date2", $scope.labels7, { category20: true });

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
        }    
    }, function () { });
	AppDataService.loadStatusTypeList_orders(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels5.push(result.data[i].type);
        }    
    }, function () { });

	AppDataService.loadStatusTypeList_repairs(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels6.push(result.data[i].type);
            //console.log("labels SIX are " + $scope.labels6)
        }    
    }, function () { });
    
	AppDataService.loadStatusTypeList_repairs_and_fit_issues(null, null, function (result) {
	//AppDataService.loadStatusTypeList_repairs(null, null, function (result) {
        for (var i = 0; i < result.data.length; i++) {
            $scope.labels7.push(result.data[i].type);
            console.log("Labels7 is " + result.data[i].type)
        }    
    }, function () { });

	
	//$scope.labels6.push(" # of Repairs", "");
	//$scope.labels6.push("");
    
	$scope.loadFirstPassYield();
	$scope.loadFirstPassYield_initial_PassFail();
	$scope.loadFirstPassYield_I();	
	$scope.loadFirstPassYield_Failure();	
	$scope.loadImpressionsReceived();
	$scope.loadRepairsReceived();
	$scope.loadRepairsAndFitNumbers();


    $scope.selectMonth_Month = function () {
        $("#firstpassyield").html("");
        $scope.loadFirstPassYield();
        $("#firstpassyield_initial_PassFail").html("");
        $scope.loadFirstPassYield_initial_PassFail();
        $("#firstpassyield_initial").html("");
        $scope.loadFirstPassYield_I();
        $("#firstpassyield_failure").html("");
        $scope.loadFirstPassYield_Failure();
        $("#impressions_received_date").html("");
        $scope.loadImpressionsReceived();
        $("#repairs_received_date").html("");
        $scope.loadRepairsReceived();
        $("#repairs_and_fit_numbers").html("");
        $scope.loadRepairsAndFitNumbers();

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


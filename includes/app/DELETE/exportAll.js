swdApp.controller('exportAll', ['$http', '$scope', 'AppDataService',  function ($http, $scope, AppDataService) {    
    $scope.calc = {
        landfill_disposal_cost: "38.00",
        swd_disposal: "0.38",
        oil_price: "34",
        landlord_cost: "0.40",
        interphase_cost: "2.00",
    };

    $scope.disposalWellList = [];
    //$scope.waterTypeList = [];  
    $scope.truckingCompanyList = [];    
    $scope.source_well_id = 0;

	//    disposal_well_id: "",


    //$scope.waterTypeList = [];  
    $scope.truckingCompanyList = [];
    $scope.trucking_company_id = 0;
    AppDataService.loadTruckingCompanyList(null, null, function (result) {
        $scope.truckingCompanyList = result.data;
        $scope.truckingCompanyList.unshift({ id: 0, name: "All" });
    }, function (result) { }); 
    
    $scope.disposalWellList = [];
    $scope.disposal_well_id = 0;
    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
    }, function (result) {});


    $scope.startdate = new Date();
    $scope.enddate = new Date();

    $scope.opened_startdate = false;
    $scope.opened_enddate = false;

    $scope.openStartDate = function ($event) {
        $scope.opened_startdate = true;
    };

    $scope.openEndDate = function ($event) {
        $scope.opened_enddate = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['yyyy-MM-dd'];
    $scope.format = $scope.formats[0];

    $scope.export = function (type, Customer, commodity) {
	    console.log(Customer);
		console.log(commodity);
	    console.log($scope.calc.landfill_disposal_cost);
        var startdate = moment($scope.startdate).format("YYYY-MM-DD");
        var enddate = moment($scope.enddate).format("YYYY-MM-DD");

        myblockui();
        var api_url = "";
        if (type == "welllog")
            api_url = window.cfg.apiUrl + "export/exportWellLog.php?startdate=" + startdate + "&enddate=" + enddate;
        else if (type == "ticket")

        	if(Customer == "trd") // || Customer == "dev") 
	        	api_url = window.cfg.apiUrl + "export/exportTicket_TRD.php?startdate=" + startdate + "&enddate=" + enddate + "&landfill=" + $scope.calc.landfill_disposal_cost + "&SWDdisposal=" + $scope.calc.swd_disposal + "&OilPrice=" + $scope.calc.oil_price + "&LandlordCost=" + $scope.calc.landlord_cost + "&InterphaseCost=" + $scope.calc.interphase_cost;
	        else if(Customer == "wwl") // || Customer == "dev") 
	        	api_url = window.cfg.apiUrl + "export/exportTicket_WWL.php?startdate=" + startdate + "&enddate=" + enddate + "&landfill=" + $scope.calc.landfill_disposal_cost + "&SWDdisposal=" + $scope.calc.swd_disposal + "&OilPrice=" + $scope.calc.oil_price + "&LandlordCost=" + $scope.calc.landlord_cost + "&InterphaseCost=" + $scope.calc.interphase_cost;
	        	//console.log(result.data);
	        	//console.log(api_url); }
            else 
            	api_url = window.cfg.apiUrl + "export/exportTicket.php?startdate=" + startdate + "&enddate=" + enddate + "&trucking_company_id=" + $scope.trucking_company_id + "&source_well_id=" + $scope.source_well_id + "&disposal_well_id=" + $scope.disposal_well_id; 

        else if (type == "dailylogs")
		{
            api_url = window.cfg.apiUrl + "export/exportDailyLogs.php?startdate=" + startdate + "&enddate=" + enddate;
			console.log(api_url);
        }
		else if (type == "truckingcompany")
            api_url = window.cfg.apiUrl + "export/exportTruckingCompany.php?startdate=" + startdate + "&enddate=" + enddate;
        else if (type == "oilcompany")
            api_url = window.cfg.apiUrl + "export/exportOilCompany.php?startdate=" + startdate + "&enddate=" + enddate;
        else if (type == "outgoing_ticket")
        	if(commodity == "landfill")
            	api_url = window.cfg.apiUrl + "export/exportOutgoingTicket_solids.php?startdate=" + startdate + "&enddate=" + enddate;
            else if(commodity == "oil")
            	api_url = window.cfg.apiUrl + "export/exportOutgoingTicket_oil.php?startdate=" + startdate + "&enddate=" + enddate;
            else
            	api_url = window.cfg.apiUrl + "export/exportOutgoingTicket_water.php?startdate=" + startdate + "&enddate=" + enddate;
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            window.open(window.cfg.rootUrl + "/data/export/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Function is not working");

        });
    };

$scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.wells = data.data;
            //alert($scope.wells.length);
        })
    }
$scope.init=function()
    {
        $scope.getWells();

        //AppDataService.loadWaterTypeList(null, null, function (result) {
        //    $scope.waterTypeList = result.data;
        //}, function (result) { });


        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
        
}]);


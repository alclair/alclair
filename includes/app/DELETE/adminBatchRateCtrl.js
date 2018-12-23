swdApp.controller('adminBatchRateCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.apifolder = "rates";
    $scope.entityName = "Set Batch Rate";
    $scope.batchEdit = {};
	$scope.batchEdit.use_default=0;
	$scope.wellPageIndex=0;
	$scope.wellPageSize=30;
	$scope.wellStart=0;
	$scope.wellEnd=$scope.wellPageSize;
	
	$scope.operator_search="";
	$scope.well_search="";
	$scope.trucking_company_search="";
	$scope.wellNext=function()
	{
		$scope.wellPageIndex=$scope.wellPageIndex+1;
		$scope.wellStart=$scope.wellPageIndex*$scope.wellPageSize;
		$scope.wellEnd=$scope.wellStart+$scope.wellPageSize;
		if($scope.wellEnd>$scope.source_well_list.length)
		{
			$scope.wellEnd=$scope.source_well_list.length;
		}
	}
	$scope.wellPrevious=function()
	{
		$scope.wellPageIndex=$scope.wellPageIndex-1;
		$scope.wellStart=$scope.wellPageIndex*$scope.wellPageSize;
		$scope.wellEnd=$scope.wellStart+$scope.wellPageSize;
	}
    $scope.SetOperatorsAllChecked=function()
	{
		for (var i = 0; i < $scope.operator_list.length; i++) 
		{
			$scope.operator_list[i].checked=$scope.operatorsCheckedAll;
		}
		$scope.GetSourceWells();
	}
	$scope.SetWellsAllChecked=function()
	{
		for (var i = 0; i < $scope.source_well_list.length; i++) 
		{
			$scope.source_well_list[i].checked=$scope.wellsCheckedAll;
		}
	}
	$scope.SetTruckingCompanysAllChecked=function()
	{
		for (var i = 0; i < $scope.trucking_company_list.length; i++) 
		{
			$scope.trucking_company_list[i].checked=$scope.truckingcompanysCheckedAll;
		}
	}
    $scope.CheckRequiredFields=function()
    {
        $scope.batchEdit.operator_ids = [];
        var valid = true;
        var noids = 0;
        for (var i = 0; i < $scope.operator_list.length; i++) {
            if ($scope.operator_list[i].checked) {                
                noids = noids + 1;
                $scope.batchEdit.operator_ids.push($scope.operator_list[i].id);
            }
        }
        if(noids==0)
        {
            toastr.error("Please select at least one operator to set the rate.");
            valid = false;
        }
		if(noids==$scope.operator_list.length)
		{
			$scope.batchEdit.operator_ids=[0];
		}
        var nwells = 0;
        $scope.batchEdit.well_ids = [];
        for (var i = 0; i < $scope.source_well_list.length; i++) {
            if ($scope.source_well_list[i].checked) {
                nwells = nwells + 1;
                $scope.batchEdit.well_ids.push($scope.source_well_list[i].id);
            }
        }
        if (nwells == 0) {
            toastr.error("Please select at least one well to set the rate.");
            valid = false;
        }
		
        $scope.batchEdit.trucking_company_ids = [];
        if ($scope.batchEdit.billto_option == "trucking company")
        {
            var ntc = 0;
            for (var i = 0; i < $scope.trucking_company_list.length; i++) {
                if ($scope.trucking_company_list[i].checked) {
                    ntc = ntc + 1;
                    $scope.batchEdit.trucking_company_ids.push($scope.trucking_company_list[i].id);
                }
            }
            if (ntc == 0) {
                toastr.error("Please select at least one trucking company to set the rate.");
                valid = false;
            }			
        }
        //alert(valid);
        return valid;
    }
    $scope.filterOperator=function(item)
    {
        if (isEmpty($scope.operator_search)) return true;
        if (item.name.toLowerCase().indexOf($scope.operator_search.toLowerCase()) == 0) {
            return true;
        }
        else
            return false;
    }
    $scope.filterTruckingCompany = function (item) {
        if (isEmpty($scope.trucking_company_search)) return true;
        if (item.name.toLowerCase().indexOf($scope.trucking_company_search.toLowerCase()) == 0) {
            return true;
        }
        else
            return false;
    }
    $scope.filterWell = function (item) {
        if (isEmpty($scope.well_search)) return true;
        if (item.source_well_file_number.toString().toLowerCase().indexOf($scope.well_search.toLowerCase()) == 0 || item.source_well_name.toLowerCase().indexOf($scope.well_search.toLowerCase()) == 0) {
            return true;
        }
        else
            return false;
    }
	$scope.GetOperators=function()
	{
		myblockui();
		$("#operator_loader").show();	
		var api_url = window.cfg.apiUrl + "operators/get.php?all=1&SearchText=" + $scope.operator_search;
		//alert(api_url);
		$http.get(api_url).success(function (result) {                
			$scope.operator_list = result.data;
			if($scope.operator_list_total==undefined)
			{
				$scope.operator_list_total=$scope.operator_list.length;
			}
			$("#operator_loader").hide();
			$scope.SetOperatorsAllChecked();
			$.unblockUI();
		});
	}
	$scope.GetTruckingCompanys=function()
	{
		myblockui();
		$("#operator_loader").show();	
		var api_url = window.cfg.apiUrl + "truckingcompany/get.php?SearchText=" + $scope.trucking_company_search;
		//alert(api_url);
		$http.get(api_url).success(function (result) {                
			$scope.trucking_company_list = result.data;
			$scope.SetTruckingCompanysAllChecked();
			$.unblockUI();
		});
	}
    $scope.GetSourceWells = function () {
        
        var oids = "";
        var noids = 0;
        //myblockui();
        for (var i = 0; i < $scope.operator_list.length; i++)
        {
            if ($scope.operator_list[i].checked)
            {
                //alert($scope.operator_list[i].id);
                if (oids != "") oids = oids + "|";
                oids = oids + $scope.operator_list[i].id;
                noids = noids + 1;
                //alert(noids);
            }
        }
        if (oids == "")
        {          
            $scope.source_well_list = [];
        }
        else
        {          
            myblockui();
			
            var api_url = window.cfg.apiUrl + "wells/get.php";
            //alert(api_url);
			var vm={};
			vm.q=$scope.well_search;
			vm.operator_id=oids;
			$http({
                method: 'POST',
                url: api_url,
                data: $.param(vm),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).success(function (result) {                
                $scope.source_well_list = result.data;
				$scope.wellTotalPages=Math.ceil($scope.source_well_list.length/$scope.wellPageSize);
				if($scope.wellEnd>$scope.source_well_list.length)
				{
					$scope.wellEnd=$scope.source_well_list.length;
				}
				$scope.wellPageIndex=0;	
				$scope.wellStart=0;
				$scope.SetWellsAllChecked();
				$.unblockUI();
            });
        }        
    }
    $scope.batchUpdate=function()
    {
        if ($scope.CheckRequiredFields() == true)
        {
            var api_url = window.cfg.apiUrl + $scope.apifolder + "/batchUpdate.php";
           myblockui();
            $http({
                method: 'POST',
                url: api_url,
                data: $.param($scope.batchEdit),
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
             .success(function (result) {
                 if (result.code == "success") {
                     $.unblockUI();
                     toastr.success(result.message);                    
                 }
             }).error(function (data) {
                 toastr.error("Update error.");
             });
        }
    }
    $scope.init = function () {
        myblockui();
        $scope.water_type_list = window.cfg.water_type_list;
        $scope.disposal_well_list = window.cfg.disposal_well_list;
		$scope.GetTruckingCompanys();
        
		$scope.GetOperators();
        $.unblockUI();
    }
    $scope.init();
}]);
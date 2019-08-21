swdApp.service('AppDataService', ['$http', function ($http, $scope) {
	
    this.load = function (name, params, success, error) {
	    if(name != 'alclair')
	        var api_url = window.cfg.apiUrl + name + "/get.php";
		else 	        
			var api_url = window.cfg.apiUrl + name + "/get_status_type.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    
        
    this.load_alclair_monitors = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_monitors.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
	this.load_alclair_monitors_not_universals = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_monitors_not_universals.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };

	this.load_alclair_impression_colors = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name +  "/get_impression_colors.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };

    this.load_alclair_buildType = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_buildType.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    
    this.load_alclair_sound_faults = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name +  "/get_sound_faults.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    this.load_alclair_fit_faults = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name +  "/get_fit_faults.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
 this.load_alclair_design_faults = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name +  "/get_design_faults.php";
		
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
 
    
    this.load_alclair_initial = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_status_type_initial.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
	this.load_alclair_failure = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_status_type_failure.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
 	this.load_alclair_impressions_vs_shipped = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_status_type_orders.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };   
this.load_alclair_repairs_vs_shipped = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_status_type_repairs.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };   
    
    
    this.load_alclair_orderStatusTableList = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_order_status_table.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
	this.load_alclair_repairStatusTableList = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_repair_status_table.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
	
	
	this.load_alclair_batchTypeList = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_batch_type_list.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    this.load_alclair_batchStatusList = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_batch_status_list.php";
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    this.load_alclair_inHouseNextStepsList = function (name, params, success, error) {
		var api_url = window.cfg.apiUrl + name + "/get_inhouse_next_steps_list.php?customer_status=" + params;
			
        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
	
  
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // ALCLAIR
    this.loadMonitorList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_monitors('alclair', params, success, error);
    };
    this.loadMonitorList_not_Universals = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_monitors_not_universals('alclair', params, success, error);
    };
	this.loadImpressionColorList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_impression_colors('alclair_manufacturing', params, success, error);
    };
    this.loadSoundFaultsList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_sound_faults('alclair', params, success, error);
    };
	this.loadFitFaultsList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_fit_faults('alclair', params, success, error);
    };
	this.loadDesignFaultsList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_design_faults('alclair', params, success, error);
    };


    this.loadBuildTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_buildType('alclair', params, success, error);
    };
     // THIS IS FOR ALCLAIR / IT'S EITHER PASS OR FAIL
    this.loadStatusTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('alclair', params, success, error);
    };
    this.loadStatusTypeList_initial = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_initial('alclair', params, success, error);
    };
	this.loadStatusTypeList_failure = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_failure('alclair', params, success, error);
    };
    this.loadStatusTypeList_orders = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_impressions_vs_shipped('alclair', params, success, error);
    };
    this.loadStatusTypeList_repairs = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_repairs_vs_shipped('alclair', params, success, error);
    };
    this.loadOrderStatusTableList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_orderStatusTableList('alclair', params, success, error);
    };
    this.loadRepairStatusTableList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_repairStatusTableList('alclair', params, success, error);
    };
/////////////////////////////////////////////////////////////////////////////////////////////////// BATCH STUFF ////////////////////////////////////////////////////////////////////////    
    this.loadBatchTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_batchTypeList('alclair_batch', params, success, error);
    };
 	this.loadBatchStatusList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_batchStatusList('alclair_batch', params, success, error);
    };   
    this.loadInHouseNextStepsList= function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_inHouseNextStepsList('alclair_batch', params, success, error);
    };   

		
    this.loadFileList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('file', params, success, error);
    };
    
    this.AddorSubtract = [
        //{ value: '0', label: 'Add or Subtract' },
        { value: 'Add', label: 'Add' },
        { value: 'Subtract', label: 'Subtract' },
    ];    
    
    this.ReasonForAdjust= [
        //{ value: '0', label: 'Add or Subtract' },
        { value: 'Purchase', label: 'Purchase' },
        { value: 'Inventory Adjustment', label: 'Inventory Adjustment' },
        //{ value: 'Transfer', label: 'Transfer' },
    ]; 
    
    // ALCLAIR
    this.PASS_OR_FAIL = [
        { value: '0', label: 'All States' },
        { value: 'PASS', label: 'Passed' },
        { value: 'FAIL', label: 'Failed' },
        { value: 'PASS AND FAIL' , label: 'Pass & Failed' },
        { value: 'READY TO SHIP', label: 'Ready to Ship' },
		{ value: 'WAITING FOR ARTWORK', label: 'Waiting for Artwork' },
		{ value: 'IMPORTED', label: 'Imported' },
    ];
    this.CableTypeList = [
        { value: '0', label: 'Select a cable' },
        { value: 'Black', label: 'Black' },
        { value: 'Clear', label: 'Clear' },
        { value: 'Mic Cable', label: 'Mic Cable' },
        { value: 'Other', label: 'Other' },
    ];
        this.ArtworkTypeList = [
        { value: 'None', label: 'None' },
        { value: 'Yes', label: 'Yes' },
        /*{ value: 'Logo', label: 'Logo' },
        { value: 'C-Icon', label: 'C-Icon' },
        { value: 'V-Stamp', label: 'V-Stamp' },
        { value: 'Script', label: 'Script' },
        { value: 'Custom', label: 'Custom' },*/
    ];
    this.PRINTED_OR_NOT = [
        { value: '0', label: 'Printed & Not' },
        { value: 'TRUE', label: 'Printed' },
        { value: 'FALSE', label: 'Not Printed' },
    ];  
    this.TODAY_OR_NEXT_WEEK = [
	    { value: '0', label: 'Past Due' },
        { value: '1', label: 'Today' },
        { value: '2', label: 'Tomorrow' },
        { value: '8', label: 'Next 3 Calendar Days' },
        { value: '3', label: 'Next 7 Calendar Days' },
        { value: '4', label: 'Next 14 Calendar Days' },
        { value: '5', label: 'Next 21 Calendar Days' },
        { value: '6', label: 'Next 28 Calendar Days' },
		{ value: '7', label: 'Next 35 Calendar Days' },
    ];    
    this.REPAIRED_OR_NOT = [
        //{ value: '0', label: 'Repaired & Not' },
        { value: 'TRUE', label: 'Repaired' },
        { value: 'FALSE', label: 'Not Repaired' },
    ];    
     this.HEARING_PROTECTION_COLORS = [
        { value: '0', label: 'Pick a color' },
        { value: 'Black', label: 'Black' },
        { value: 'Clear', label: 'Clear' },
        { value: 'Green' , label: 'Green' },
        { value: 'Blue', label: 'Blue' },
		{ value: 'Yellow', label: 'Yellow' },
		{ value: 'Red', label: 'Red' },
		{ value: 'Orange', label: 'Orange' },
		{ value: 'Brown', label: 'Brown' },
		{ value: 'Pink', label: 'Pink' },
		{ value: 'Purple', label: 'Purple' },
    ];
    this.FAULT_TYPES = [
        { value: '0', label: 'Pick a classification' },
        { value: 'Sound', label: 'Sound' },
        { value: 'Fit', label: 'Fit' },
        { value: 'Design' , label: 'Design' },
    ];
    this.month_range = [
        { value: 30, label: '30 Days' },
        { value: 60, label: '60 Days' },
        { value: 90, label: '90 Days' },
    ];
    this.day_to_view = [
        { value: 0, label: 'Today' },
        { value: 1, label: 'Tomorrow' },
        { value: 2, label: '+2 Days' },
        { value: 3, label: '+3 Days' },
        { value: 4, label: '+4 Days' },
    ];
	this.customer_status = [
        { value: '0', label: 'New' },
        { value: '1', label: 'Existing' },
    ];
     
    this.monthRange = [
        //{ value: '0', label: 'Select a decimal' },
        { value: '01', label: 'Jan' },
        { value: '02', label: 'Feb' },
        { value: '03', label: 'Mar' },
        { value: '04', label: 'Apr' },
        { value: '05', label: 'May' },
		{ value: '06', label: 'Jun' },
		{ value: '07', label: 'Jul' },
		{ value: '08', label: 'Aug' },
		{ value: '09', label: 'Sep' },
		{ value: '10', label: 'Oct' },
		{ value: '11', label: 'Nov' },
		{ value: '12', label: 'Dec' },
    ];
}]);

swdApp.service('FileUpload', ['Upload', function (Upload) {
    this.upload = function (file, ticket_id) {
        return Upload.upload({
            url: window.cfg.apiUrl + "file/upload.php?ticket_id=" + ticket_id,
            method: "POST",
            file: file,
            headers: {},
        });
    };
}]);
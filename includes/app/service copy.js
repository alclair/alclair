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

    this.load2 = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get2.php?index="+params;

        $http({
            method: 'GET',
            url: api_url,
            params: params == null ? {} : params,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (result) {
            console.log(result.test)
            console.log(result.test2)
			console.log(result.test3)
            success(result);
        }).error(function (result) {
            error(result);
        });
    };
    
    this.loadQueens = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get.php?customer_id=" + params;
		
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
        
    this.load_landfill = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_landfill.php";
		
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
	this.load_oil = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_oil.php";
		
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
    this.load_water = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_water.php";
		
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
	
    this.load_initital= function (name, params, success, error) {
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
	this.load_ifi_employees = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_employees.php";
		
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
    this.load_ifi_products = function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_products.php";
		
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
    this.load_ifi_categories= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_categories.php";
		
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
    this.load_ifi_products_in_categories= function (name, params, success, error) {
	    //console.log("The params are " + params)
        var api_url = window.cfg.apiUrl + name + "/get_products_in_category.php?category_in_inventory=" + params;
		
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
    this.load_ifi_new_or_demo= function (name, params, success, error) {
	    //console.log("The params are " + params)
        var api_url = window.cfg.apiUrl + name + "/get_new_or_demo.php";
		
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
    
    this.load_ifi_ship_to= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_ship_to.php";
		
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
    this.load_ifi_carriers= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_carriers.php";
		
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
    this.load_ifi_warehouses= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_warehouses.php";
		
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
    this.load_ifi_ordertypes= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_ordertypes.php";
		
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
	this.load_ifi_addresses= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_addresses.php";
		
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
    
    
    this.load_tbl_product_list= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_product.php";
		
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
    this.load_tbl_title= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_title.php";
		
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
    this.load_tbl_reason= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_reason.php";
		
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
    this.load_tbl_unit_type= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_unit_type.php";
		
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
    this.load_tbl_tag= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_tag.php";
		
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
    this.load_tbl_iclub= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_iclub.php";
		
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
    this.load_tbl_status= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_status.php";
		
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
	this.load_tbl_sector= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_sector.php";
		
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
	this.load_tbl_country= function (name, params, success, error) {
        var api_url = window.cfg.apiUrl + name + "/get_tbl_country.php";
		
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

    
    
    
    
    this.loadDisposalWellList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('disposalwell', params, success, error);
    };
    
    this.loadCustomers_LNG = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('customers', params, success, error);
    };
    this.loadTicketCreators = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('users', params, success, error);
    };
    
    this.loadLngQueensList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.loadQueens('queens', params, success, error);
    };

    this.loadWaterTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('watertype', params, success, error);
    };
    
    this.loadFluidTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('fluidtype', params, success, error);
    };
	this.loadOperatorList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('operators', params, success, error);
    };
    this.loadTruckingCompanyList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('truckingcompany', params, success, error);
    };
    this.loadRigsList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('rigs', params, success, error);
    };
    this.loadTankList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('tanks', params, success, error);
    };
    // SECOND TANK LIST CALL FOR WWL AND TRD SITES.  NEEDED TO FILTER OUT ONE TANK AFTER FIRST TANK IS SELECTED
	this.loadTankList2 = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();
        this.load2('tanks', params, success, error);
    };
    this.loadFluidTypeList2 = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();
			console.log(params)
        this.load2('fluidtype', params, success, error);
    };
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	this.loadUniqueCustomerList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('uniquecustomer', params, success, error);
    };
    
     this.loadAccountCodes = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('accountcodes', params, success, error);
    };
    
    // ALCLAIR
    this.loadMonitorList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_alclair_monitors('alclair', params, success, error);
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
    

	// iFi audio
	this.loadEmployeeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_employees('ifi', params, success, error);
    };
    this.loadProductList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_products('ifi', params, success, error);
    };
    this.loadCategoryTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_categories('ifi', params, success, error);
    };
	this.loadProductsInCategoryList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_products_in_categories('ifi', params, success, error);
    };
    this.loadNewOrDemoList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_new_or_demo('ifi_tbl', params, success, error);
    };
    this.loadShipToList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_ship_to('ifi', params, success, error);
    };
	this.loadCarriersList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_carriers('ifi', params, success, error);
    };
    this.loadWarehousesList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_warehouses('ifi', params, success, error);
    };
    this.loadOrderTypesList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_ordertypes('ifi', params, success, error);
    };
    this.loadAddressesList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_ifi_addresses('ifi', params, success, error);
    };
    
    
    this.load_tbl_ProductList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_product_list('ifi_tbl', params, success, error);
    };
    this.load_tbl_titleList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_title('ifi_tbl', params, success, error);
    };
    this.load_tbl_reasonList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_reason('ifi_tbl', params, success, error);
    };
    this.load_tbl_unitTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_unit_type('ifi_tbl', params, success, error);
    };
    this.load_tbl_tagList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_tag('ifi_tbl', params, success, error);
    };
    this.load_tbl_iclubList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_iclub('ifi_tbl', params, success, error);
    };
    this.load_tbl_statusList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_status('ifi_tbl', params, success, error);
    };
    this.load_tbl_sectorList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_sector('ifi_tbl', params, success, error);
    };
    this.load_tbl_countryList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_tbl_country('ifi_tbl', params, success, error);
    };
	
    this.loadFileList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('file', params, success, error);
    };
    
    this.loadFileList_landfill = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_landfill('file', params, success, error);
    };
    this.loadFileList_oil = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_oil('file', params, success, error);
    };
    this.loadFileList_water = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load_water('file', params, success, error);
    };

    this.loadLandfillDisposalSites = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('landfilldisposalsites', params, success, error);
    };
    
    this.loadTruckTypeList = function (params, before, success, error) {
        if (before != null && before != undefined)
            before();

        this.load('trucktype', params, success, error);
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
    
    this.DeliveryMethodList = [
        { value: '0', label: 'Select a delivery method' },
        { value: 'Pipeline', label: 'Pipeline' },
        { value: 'Truck', label: 'Truck' },
    ];

    this.WaterSourceTypeList = [
        { value: '0', label: 'Select a water source type' },
        { value: 'Pit', label: 'Pit' },
        { value: 'Well', label: 'Well' },
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
    this.loadStatusList = [
        { value: '0', label: 'All' },
        { value: 'SEALED', label: 'Sealed' },
        { value: 'AMAZON', label: 'Amazon' },
        { value: 'DEMO', label: 'Demo' },
        { value: 'FAULTY', label: 'Faulty' },
        { value: 'SHIPPED', label: 'Shipped' },
    ]; 
    this.loadMovementList = [
        { value: '0', label: 'All' },
        { value: 'IMPORT', label: 'Imported' },
        { value: 'EXPORT', label: 'Exported' },
    ];    
    this.loadApprovedList = [
        { value: '0', label: 'All' },
        { value: 'APPROVED', label: 'Approved' },
        { value: 'NEEDS_APPROVAL', label: 'Needs Approval' },
        { value: 'UNAPPROVED', label: 'Unapproved' },
    ]; 
    this.loadWhoGetsShippingRequest = [
        { value: '0', label: 'Who gets it?' },
        { value: 'UK', label: 'Sarah/Owen' },
        { value: 'USA', label: 'Steph/Jared' },
        { value: 'Vivian', label: 'Factory' },
    ]; 
    this.TruckTypeList = [
        //{ value: '0', label: 'Select a truck type' },
        { value: 'Vac Truck', label: 'Vac Truck' },
        { value: 'Hydro Vac', label: 'Hydro Vac' },
    ];

    this.TopDecimalList = [
        //{ value: '0', label: 'Select a decimal' },
        { value: '.0', label: '0"' },
        { value: '.25', label: '1/4"' },
        { value: '.5', label: '1/2"' },
        { value: '.75', label: '3/4"' },
        //{ value: 'zero', label: '0"' },
        //{ value: 'quarter', label: '1/4"' },
        //{ value: 'half', label: '1/2"' },
        //{ value: 'three_quarters', label: '3/4"' },
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

    this.filterSock = [
        { value: '0', label: '0' },
        { value: '1', label: '1' },
        { value: '2', label: '2' },
        { value: '3', label: '3' },
        { value: '4', label: '4' },
		{ value: '5', label: '5' },
    ];
	
	this.trd_bill_to = [
        { value: '0', label: 'Producer' },
        { value: '1', label: 'Trucking Company' },
        { value: '2', label: 'Other' },
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
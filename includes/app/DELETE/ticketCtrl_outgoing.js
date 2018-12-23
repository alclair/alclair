swdApp.controller('ticketCtrl', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
    $scope.ticketList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    $scope.SearchDisposalWell = "";
    $scope.SearchOutgoingTicketTypes = "";
	
    if (window.cfg.Id > 0)
        $scope.PageIndex = window.cfg.Id;
	$scope.openStart = function ($event) {        
        $scope.openedStart = true;
    };
	$scope.openEnd = function ($event) {        
        $scope.openedEnd = true;
    };
	$scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
	$scope.SearchStartDate=window.cfg.CurrentMonthFirstDate;
	$scope.SearchEndDate=window.cfg.CurrentMonthLastDate;
    $scope.ClearSearch=function()
    {
        $scope.SearchText = "";
        $scope.SearchDisposalWell = "";
        $scope.SearchOutgoingTicketTypes = "";
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
        $cookies.put("SearchDisposalWell", "");
        $cookies.put("SearchOutgoingTicketTypes", "");
    }
    $scope.LoadData = function () {
        myblockui();
        $cookies.put("SearchText", $scope.SearchText);
        $cookies.put("SearchDisposalWell", $scope.SearchDisposalWell);
        $cookies.put("SearchOutgoingTicketTypes", $scope.SearchOutgoingTicketTypes);
		$cookies.put("SearchStartDate",$scope.SearchStartDate);
		$cookies.put("SearchEndDate",$scope.SearchEndDate);
        var api_url = window.cfg.apiUrl + "ticket/get_landfillOilWater.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&SearchDisposalWell="+$scope.SearchDisposalWell+"&SearchOutgoingTicketTypes="+$scope.SearchOutgoingTicketTypes +"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        
        //var api_url = window.cfg.apiUrl + "ticket/get_landfill.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&SearchDisposalWell="+$scope.SearchDisposalWell+"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                $scope.ticketList = result.data;
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;
				//console.log($scope);

                $scope.PageRange = [];
                $scope.PageWindowStart = (Math.ceil($scope.PageIndex / $scope.PageWindowSize) - 1) * $scope.PageWindowSize + 1;
                $scope.PageWindowEnd = $scope.PageWindowStart + $scope.PageWindowSize - 1;
                if ($scope.PageWindowEnd > $scope.TotalPages) {
                    $scope.PageWindowEnd = $scope.TotalPages;
                }
                for (var i = $scope.PageWindowStart; i <= $scope.PageWindowEnd; i++) {
                    $scope.PageRange.push(i);
                }

                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get tickets error.");
            });
    };

    $scope.GoToPage = function (v) {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteTicket = function (id, type) {
        console.log(id);
        if (confirm("Are you sure to delete this ticket?") == false) {
            return;
        }

		if(type == 'Solids') {
        $http.get(window.cfg.apiUrl + "ticket/delete_landfill.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ticketList.length; i++) {
                if ($scope.ticketList[i].id == id) {
                    toastr.success("Delete ticket successful!", "Message");
                    $scope.ticketList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete ticket, please try again.");
        });
        						}
else if(type == 'Oil') {
	$http.get(window.cfg.apiUrl + "ticket/delete_oil.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ticketList.length; i++) {
                if ($scope.ticketList[i].id == id) {
                    toastr.success("Delete ticket successful!", "Message");
                    $scope.ticketList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete ticket, please try again.");
        });
								}
else {
	$http.get(window.cfg.apiUrl + "ticket/delete_water.php?id=" + id).success(function (result) {
            for (var i = 0; i < $scope.ticketList.length; i++) {
                if ($scope.ticketList[i].id == id) {
                    toastr.success("Delete ticket successful!", "Message");
                    $scope.ticketList.splice(i, 1);
                    $scope.TotalRecords = $scope.TotalRecords - 1;
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete ticket, please try again.");
        });
								}

    };

 
 
    $scope.LoadDisposalWells=function()
    {
        $http.get(window.cfg.apiUrl + "disposalwell/get.php").success(function (result) {
            $scope.DisposalWells = result.data;
			if(!isEmpty(window.cfg.disposal_well_id))
			{		
				$scope.SearchDisposalWell=window.cfg.disposal_well_id;
			}
        });
    }
    
    $scope.LoadOutgoingTicketTypes=function()
    {
        $http.get(window.cfg.apiUrl + "outgoingtickettypes/get.php").success(function (result) {
            $scope.OutgoingTicketTypes = result.data;
			if(!isEmpty(window.cfg.type_id))
			{		
				$scope.SearchOutgoingTicketTypes=window.cfg.type_id;
			}
        });
    }
    
    $scope.init = function () {
        $scope.SearchText = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        $scope.SearchDisposalWell = $cookies.get("SearchDisposalWell");
        if (isEmpty($scope.SearchDisposalWell)) $scope.SearchDisposalWell = "0";
        $scope.SearchOutgoingTicketTypes = $cookies.get("SearchOutgoingTicketTypes");
        if (isEmpty($scope.SearchOutgoingTicketTypes)) $scope.SearchOutgoingTicketTypes = "0";
		if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}
        $scope.LoadData();
        $scope.LoadDisposalWells();
        $scope.LoadOutgoingTicketTypes();
    }
    $scope.init();
}]);

swdApp.controller('ticketViewCtrl', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.fileList = [];
    $scope.ticket = {};   
    
    $scope.LoadData=function()
    {
        if (window.cfg.Id > 0) {
           
            myblockui();
            var api_url = window.cfg.apiUrl + "ticket/get_landfill.php?id=" + window.cfg.Id;
            //alert(api_url);
            $http.get(api_url)
                .success(function (result) {
                    if (result.data.length > 0) {
                        $scope.ticket = result.data[0];
                        $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
                        //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                        console.log($scope.ticket);
                    }
                    $.unblockUI();
                }).error(function (result) {
                    $.unblockUI();
                    toastr.error("Get tickets error.");
                });

            AppDataService.loadFileList_landfill({ ticket_id: window.cfg.Id }, null, function (result) {
                $scope.fileList = result.data;
                console.log($scope.fileList);
            }, function (result) { });
        }
    }
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}
    $scope.init = function () {
        $scope.LoadData();
    }
    $scope.init();

    $scope.exportPDF = function () { 
        myblockui();
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_landfill.php?id=" + window.cfg.Id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    }; 
}]);

swdApp.controller('ticketExportPDF', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
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

    $scope.disposalWellList = [];
    $scope.disposalwell = 0;

    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
        //$scope.disposalwell = result.data[0].id;
    }, function (result) {
    });

    $scope.truckingCompanyList = [];
    $scope.truckingcompany = 0;
    AppDataService.loadTruckingCompanyList(null, null, function (result) {
        $scope.truckingCompanyList = result.data;
        $scope.truckingCompanyList.unshift({ id: 0, name: "All" });
    }, function (result) { });
	
	$scope.operatorList = [];
    $scope.operator_id = 0;
    AppDataService.loadOperatorList(null, null, function (result) {
        $scope.operatorList = result.data;
        $scope.operatorList.unshift({ id: 0, name: "All" });
    }, function (result) { });

    $scope.source_well_name = "";
    $scope.source_well_id = 0;

    $scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/get.php?operator_id="+$scope.operator_id;        
        $http.get(api_url).success(function (data) {
            $scope.wells = data.data;            
        })
    };

    
    $scope.export = function () {
        var startdate = moment($scope.startdate).format("YYYY-MM-DD");
        var enddate = moment($scope.enddate).format("YYYY-MM-DD");

        myblockui();

        var params = [];
        //if ($scope.enableDate == true)
        params.push("startdate=" + startdate + "&enddate=" + enddate);

        if ($scope.disposalwell != 0)
            params.push("disposalwell=" + $scope.disposalwell);
        if ($scope.source_well_id != 0)
            params.push("sourcewell=" + $scope.source_well_id);
        if ($scope.truckingcompany != 0)
            params.push("truckingcompany=" + $scope.truckingcompany);
		if ($scope.operator_id != 0)
            params.push("operator_id=" + $scope.operator_id);

        var api_url = window.cfg.apiUrl + "export/exportTicketPDF.php?" + params.join("&");
        //alert(api_url);
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            if (result.data.length == 0)
                toastr.error("Haven't found ticket");
            else
                window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
    $scope.init = function () {
        $scope.getWells();
    };
    $scope.init();
}]);

	swdApp.controller('ticketEditCtrl_landfill', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    console.log("We are in Edit 1")
    //$scope.onlyNumbers = /^\d+$/;
    $scope.ticket = {};
    //$scope.disposalWellList = [];
    $scope.localList = [];
    $scope.selectedFiles = [];
    $scope.newwell = {};
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}

    $scope.deleteDocument = function (fileid) {
        console.log(fileid);
        if (confirm("Are you sure to delete this document?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "file/delete_landfill.php?id=" + fileid).success(function (result) {
            for (var i = 0; i < $scope.fileList.length; i++) {
                if ($scope.fileList[i].id == fileid) {
                    toastr.success("Delete document " + $scope.fileList[i].filepath + " successful!", "Message");
                    $scope.fileList.splice(i, 1);
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete document, please try again.");
        });
    }


    $scope.open = function ($event) {        
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    //$scope.waterTypeList = [];
    //AppDataService.loadWaterTypeList(null, null, function (result) {
    //    $scope.waterTypeList = result.data;
    //}, 
    

    $scope.UploadFile=function()
    {       
        var api_url = window.cfg.apiUrl + 'file/upload_landfill.php';

        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                   }
                   else {
                       toastr.error(data.message);
                   }
                   window.location.href = window.cfg.rootUrl + "/ticket/view_landfill/" + window.cfg.Id;
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_landfill/" + window.cfg.Id;
        }
    }
    $scope.SaveData = function () {
		console.log("landfill integer is " + $scope.ticket.landfill_disposal_site)
		console.log("landfill integer is " + $scope.ticket.id)

        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.ship_date).format("MM/DD/YYYY");
        }
        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.ship_date).format("MM/DD/YYYY");
        }		
		         //initialize numeric values if empty
        if (isEmpty($scope.ticket.tare_weight)) {
            $scope.ticket.tare_weight = 0;
        }       
        
        if (isEmpty($scope.ticket.loaded_weight)) {
            $scope.ticket.loaded_weight = 0;
        }
        
        if (isEmpty($scope.ticket.radium_226)) {
            $scope.ticket.radium_228 = 0;
        }
        
        if (isEmpty($scope.ticket.radium_228)) {
            $scope.ticket.radium_228 = 0;
        }
		if (isEmpty($scope.ticket.tank_id)) {
            $scope.ticket.tank_id = 0;
        }
		if (isEmpty($scope.ticket.fluid_type_id)) {
            $scope.ticket.fluid_type_id = 0;
        }
        //if (isEmpty($scope.ticket.yards) || isNaN($scope.ticket.yards)) {
        //    $scope.ticket.yards = 0;
        //}
        
        if (isEmpty($scope.ticket.tons)) {
            $scope.ticket.tons = 0;
        }
        
        if (isEmpty($scope.ticket.total_dollars)) {
            $scope.ticket.total_dollars = 0;
        }
		if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }
		
        var api_url = window.cfg.apiUrl + 'ticket/update_landfill.php';
       // console.log(result);

                
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
                 
                 $scope.UploadFile();
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 //console.log(result);
                 toastr.error(result.message == undefined ? result.data : result.message);
                 console.log(result.code);
                 console.log(result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ticket/get_landfill.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log(result);
                if (result.data.length > 0) {
                    $scope.ticket = result.data[0];
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        
			        //set numeric values for washout and picocuries
			        if ($scope.ticket.washout == true) {
				    	$scope.ticket.washoutValue = 1;    
			        } else {
				        $scope.ticket.washoutValue = 0;
			        }
			        
			        if ($scope.ticket.picocuries == true) {
				    	$scope.ticket.picocuriesValue = 1;    
			        } else {
				        $scope.ticket.picocuriesValue = 0;
			        }
                    
                    if ($scope.ticket.source_well_id != undefined)
                    {
                        api_url = window.cfg.apiUrl + "wells/get.php?id=" + $scope.ticket.source_well_id;
                        $http.get(api_url).success(function(data){
                            $scope.ticket.source_well_name = data.data.name;
                            });                        

                    }
                    console.log($scope);
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
        AppDataService.loadFileList_landfill({ ticket_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });
    }
    
    //$scope.ticket.landfill_disposal_site_id =[];
 $scope.LoadLandfillDisposalSites=function()
    {
        $http.get(window.cfg.apiUrl + "landfilldisposalsites/get.php").success(function (result) {
            $scope.landfillDisposalSites = result.data;
        });
    }



    $scope.init = function () {
	    
	   	AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });
        
        AppDataService.loadFluidTypeList(null, null, function (result) {
		 	$scope.fluidTypeList = result.data;
        }, function (result) { });
	    
    	AppDataService.loadTruckingCompanyList(null, null, function (result) {
           	$scope.truckingCompanyList = result.data;
        }, function (result) { });	   
	    
        $scope.LoadLandfillDisposalSites();
        //$scope.getWells();
        $scope.LoadData();

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);


swdApp.controller('ticketEditCtrl_oil', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    //$scope.onlyNumbers = /^\d+$/;
    $scope.ticket = {};
    //$scope.disposalWellList = [];
    $scope.localList = [];
    $scope.selectedFiles = [];
    $scope.newwell = {};
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.field_list = [];
    $scope.county_list = [];
    $scope.operator_list = [];
    $scope.searchingwells = [];
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}
    $scope.ClearSearchFilters=function()
    {
        $scope.newwell.field_name = undefined;
        $scope.newwell.county_name = undefined;
        $scope.newwell.operator_id = undefined;
        $scope.newwell.operator_name = undefined;
    }
    $scope.OperatorSelected=function(id)
    {
        $scope.newwell.operator_id = id;
        $scope.SearchWells();
    }
    $scope.WellSelected=function(item)
    {
        $scope.ticket.source_well_id = item.id;
        for(var i=0;i<$scope.wells.length;i++)
        {
            if(item.id==$scope.wells[i].id)
            {
                $scope.ticket.source_well_name = $scope.wells[i].name;
                $('#SearchWellModal').modal("hide");
                break;
            }
        }
    }
    $scope.CreateWell=function()
    {
        var api_url = window.cfg.apiUrl + "wells/add.php";
        //alert($.param($scope.newwell));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.newwell),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             $('#SearchWellModal').modal("hide");
             $scope.getWells();
             $scope.ticket.source_well_id = result.data.id;
             $scope.ticket.source_well_name = result.data.name;
             $("#tblWells").unblock();
         })
               .error(function (data) {
                   $("#tblWells").unblock();
                   toastr.error("Error to get wells.");
               });
    }
    $scope.LoadSearchWellModal=function() {
        //alert("showModel");
        $('#SearchWellModal').modal("show");
        
        
    }
    $scope.SearchWells=function()
    {
        var vm = {};
        vm.county_name = $scope.newwell.county_name;
        vm.operator_id = $scope.newwell.operator_id;
        vm.field_name = $scope.newwell.field_name;
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url + "?" + $.param(vm));
        $("#tblWells").block();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param(vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             $scope.searchingwells = result.data;
             $("#tblWells").unblock();
         })
               .error(function (data) {
                   $("#tblWells").unblock();
                   toastr.error("Error to get wells.");                   
               });
    }
    $scope.deleteDocument = function (fileid) {
        console.log(fileid);
        if (confirm("Are you sure to delete this document?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "file/delete_oil.php?id=" + fileid).success(function (result) {
            for (var i = 0; i < $scope.fileList.length; i++) {
                if ($scope.fileList[i].id == fileid) {
                    toastr.success("Delete document " + $scope.fileList[i].filepath + " successful!", "Message");
                    $scope.fileList.splice(i, 1);
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete document, please try again.");
        });
    }
    //AppDataService.loadDisposalWellList(null, null, function (result) {
    //    $scope.disposalWellList = result.data;
    //}, function (result) {
    //});
    
    //AppDataService.loadLocalList(null, null, function (result) {
      //  $scope.localList = result.data;
    //}, function (result) {
    //});
    
    $scope.wells = [];
    $scope.getWells=function()
    {
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url);
       $http.get(api_url).success(function (data) {
           $scope.wells = data.data;
           //alert($scope.wells.length);
        })
    }
    $scope.open = function ($event) {        
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    //$scope.waterTypeList = [];
    //AppDataService.loadWaterTypeList(null, null, function (result) {
    //    $scope.waterTypeList = result.data;
    //}, 
    
    $scope.fluidTypeList = [];
    AppDataService.loadFluidTypeList(null, null, function (result) {
        $scope.fluidTypeList = result.data;
    }, function (result) { });

    
    $scope.TopDecimalList = AppDataService.TopDecimalList;
	//$scope.TopDecimalList.unshift({ id: 0, label: "Select a decimal" });
    $scope.UploadFile=function()
    {       
        var api_url = window.cfg.apiUrl + 'file/upload_oil.php';

        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                   }
                   else {
                       toastr.error(data.message);
                   }
                   window.location.href = window.cfg.rootUrl + "/ticket/view_oil/" + window.cfg.Id;
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_oil/" + window.cfg.Id;
        }
    }
    $scope.SaveData = function () {
        var api_url = window.cfg.apiUrl + 'ticket/update_oil.php';
       // console.log(result);
        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }
        
                 //initialize numeric values if empty
        if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       
        
        if (isEmpty($scope.ticket.top_ft)) {
            $scope.ticket.top_ft = 0;
        }
        if (isEmpty($scope.ticket.top_in)) {
            $scope.ticket.top_in = 0;
        }
		if (isEmpty($scope.ticket.top_decimal)) {
            $scope.ticket.top_decimal = 0;
        }
		if (isEmpty($scope.ticket.bottom_ft)) {
            $scope.ticket.bottom_ft = 0;
        }
        if (isEmpty($scope.ticket.bottom_in)) {
            $scope.ticket.bottom_in = 0;
        }
		if (isEmpty($scope.ticket.bottom_decimal)) {
            $scope.ticket.bottom_decimal = 0;
        }
        
        if (isEmpty($scope.ticket.top_temperature)) {
            $scope.ticket.top_temperature = 0;
        }
        if (isEmpty($scope.ticket.bottom_temperature)) {
            $scope.ticket.bottom_temperature = 0;
        }
		if (isEmpty($scope.ticket.observed_temperature)) {
            $scope.ticket.observed_temperature = 0;
        }
        
        if (isEmpty($scope.ticket.bsw)) {
            $scope.ticket.bsw = 0;
        }
        
        if (isEmpty($scope.ticket.gravity)) {
            $scope.ticket.gravity = 0;
        }
		//if (isEmpty($scope.ticket.tank_number)) {
        //    $scope.ticket.tank_number = 0;
        //}
        
        if (isEmpty($scope.ticket.oil_price)) {
            $scope.ticket.oil_price = 0;
        }
        
        if (isEmpty($scope.ticket.total_dollars)) {
            $scope.ticket.total_dollars = 0;
        }        
               
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
                 
                 $scope.UploadFile();
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 //console.log(result);
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ticket/get_oil.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
	            console.log(result);
                if (result.data.length > 0) {
                    $scope.ticket = result.data[0];
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        
			        //set numeric values for washout and picocuries
			        if ($scope.ticket.washout == true) {
				    	$scope.ticket.washoutValue = 1;    
			        } else {
				        $scope.ticket.washoutValue = 0;
			        }
			        
			        if ($scope.ticket.picocuries == true) {
				    	$scope.ticket.picocuriesValue = 1;    
			        } else {
				        $scope.ticket.picocuriesValue = 0;
			        }
                    
                    if ($scope.ticket.source_well_id != undefined)
                    {
                        api_url = window.cfg.apiUrl + "wells/get.php?id=" + $scope.ticket.source_well_id;
                        $http.get(api_url).success(function(data){
                            $scope.ticket.source_well_name = data.data.name;
                            });                        

                    }
                    console.log($scope.ticket);
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
        AppDataService.loadFileList_oil({ ticket_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });
    }
    
    $scope.loadOtherFluidList = function (params) {
	    $scope.fluidTypeList = [];
		 AppDataService.loadFluidTypeList2(params, null, function (result) {
		 	$scope.fluidTypeList = result.data;
        }, function (result) { });
	};

    $scope.init = function () {
        //$scope.getWells();
        $scope.LoadData();
        
		AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });
        //$scope.county_list = window.cfg.county_list;
       
        //$scope.field_list = window.cfg.field_list;
        //$scope.operator_list = window.cfg.operator_list;

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);

swdApp.controller('ticketEditCtrl_water', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.ticket = {};
    $scope.disposalWellList = [];
    $scope.selectedFiles = [];
    $scope.newwell = {};
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.field_list = [];
    $scope.county_list = [];
    $scope.operator_list = [];
    $scope.searchingwells = [];
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}
    $scope.ClearSearchFilters=function()
    {
        $scope.newwell.field_name = undefined;
        $scope.newwell.county_name = undefined;
        $scope.newwell.operator_id = undefined;
        $scope.newwell.operator_name = undefined;
    }
    $scope.OperatorSelected=function(id)
    {
        $scope.newwell.operator_id = id;
        $scope.SearchWells();
    }
    $scope.WellSelected=function(item)
    {
        $scope.ticket.source_well_id = item.id;
        for(var i=0;i<$scope.wells.length;i++)
        {
            if(item.id==$scope.wells[i].id)
            {
                $scope.ticket.source_well_name = $scope.wells[i].name;
                $('#SearchWellModal').modal("hide");
                break;
            }
        }
    }
    $scope.CreateWell=function()
    {
        var api_url = window.cfg.apiUrl + "wells/add.php";
        //alert($.param($scope.newwell));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.newwell),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             $('#SearchWellModal').modal("hide");
             $scope.getWells();
             $scope.ticket.source_well_id = result.data.id;
             $scope.ticket.source_well_name = result.data.name;
             $("#tblWells").unblock();
         })
               .error(function (data) {
                   $("#tblWells").unblock();
                   toastr.error("Error to get wells.");
               });
    }
    $scope.LoadSearchWellModal=function() {
        //alert("showModel");
        $('#SearchWellModal').modal("show");
        
        
    }
    $scope.SearchWells=function()
    {
        var vm = {};
        vm.county_name = $scope.newwell.county_name;
        vm.operator_id = $scope.newwell.operator_id;
        vm.field_name = $scope.newwell.field_name;
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url + "?" + $.param(vm));
        $("#tblWells").block();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param(vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             $scope.searchingwells = result.data;
             $("#tblWells").unblock();
         })
               .error(function (data) {
                   $("#tblWells").unblock();
                   toastr.error("Error to get wells.");                   
               });
    }
    $scope.deleteDocument = function (fileid) {
        console.log(fileid);
        if (confirm("Are you sure to delete this document?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "file/delete_water.php?id=" + fileid).success(function (result) {
            for (var i = 0; i < $scope.fileList.length; i++) {
                if ($scope.fileList[i].id == fileid) {
                    toastr.success("Delete document " + $scope.fileList[i].filepath + " successful!", "Message");
                    $scope.fileList.splice(i, 1);
                    break;
                }
            }
        }).error(function (result) {
            toastr.error("Failed to delete document, please try again.");
        });
    }
    
    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
    }, function (result) {});
    
    $scope.wells = [];
    $scope.getWells=function()
    {
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url);
       $http.get(api_url).success(function (data) {
           $scope.wells = data.data;
           //alert($scope.wells.length);
        })
    }
    $scope.open = function ($event) {        
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    $scope.waterTypeList = [];
    
    AppDataService.loadWaterTypeList(null, null, function (result) {
        $scope.waterTypeList = result.data;
    }, function (result) { });

    $scope.truckingCompanyList = [];
    AppDataService.loadTruckingCompanyList(null, null, function (result) {
        $scope.truckingCompanyList = result.data;
    }, function (result) { });

    $scope.DeliveryMethodList = AppDataService.DeliveryMethodList;
    $scope.WaterSourceTypeList = AppDataService.WaterSourceTypeList;
    $scope.UploadFile=function()
    {       
        var api_url = window.cfg.apiUrl + 'file/upload_water.php';
        
        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                   }
                   else {
                       toastr.error(data.message);
                   }
                   window.location.href = window.cfg.rootUrl + "/ticket/view_water/" + window.cfg.Id;
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_water/" + window.cfg.Id;
        }
    }
    
    $scope.SaveData = function () {
        var api_url = window.cfg.apiUrl + 'ticket/update_water.php';
        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }
        
        $scope.ticket.Id = window.cfg.Id;
        //alert(JSON.stringify($scope.ticket));
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
             if (result.code == "success") {
                 
                 $scope.UploadFile();
                 $.unblockUI();
                 //window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ticket/get_water.php?id=" + window.cfg.Id;
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
                if (result.data.length > 0) {
                    $scope.ticket = result.data[0];
                    //alert($scope.ticket.id);
                    //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                    $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
                    if ($scope.ticket.source_well_id != undefined)
                    {
                        api_url = window.cfg.apiUrl + "wells/get.php?id=" + $scope.ticket.source_well_id;
                        $http.get(api_url).success(function(data){
                            $scope.ticket.source_well_name = data.data.name;
                            });                        

                    }
                    
					AppDataService.loadFluidTypeList2($scope.ticket.tank_id, null, function (result) {
						$scope.fluidTypeList = result.data;
					}, function (result) { });
        
                    console.log($scope.ticket);
                }
                $.unblockUI();
            }).error(function (result) {
                $.unblockUI();
                toastr.error("Get tickets error.");
            });
        AppDataService.loadFileList_water({ ticket_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });
    }



    $scope.keepit = function () {
        $("#divBarrelWarning").modal("hide");
    };

    $scope.changeit = function () {
        $scope.ticket.barrels_delivered = $scope.new_barrel_delivery;
        $("#divBarrelWarning").modal("hide");
    };
    
    $scope.loadOtherFluidList = function (params) {
	    $scope.fluidTypeList = [];
		 AppDataService.loadFluidTypeList2(params, null, function (result) {
		 	$scope.fluidTypeList = result.data;
        }, function (result) { });
	};

    $scope.init = function () {
	    //$scope.getWells();
        AppDataService.loadDisposalWellList(null, null, function (result) {
            $scope.disposalWellList = result.data;
        }, function (result) {
        });
	    
	    AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });
        
        $scope.getWells();
        $scope.LoadData();
        $scope.county_list = window.cfg.county_list;
       
        $scope.field_list = window.cfg.field_list;
        $scope.operator_list = window.cfg.operator_list;

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);


swdApp.controller('ticketAddCtrl_landfill', ['$http', '$scope', '$cookies', 'AppDataService', '$upload', function ($http, $scope, $cookies, AppDataService, $upload) {
	//dApp.controller('ticketCtrl', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
    $scope.greeting = 'Hola!';
    console.log("Here we are 12")
    //$scope.onlyNumbers=/^\d+$/;
    $scope.ticket = {
        test_report_number: '',
        date_delivered: new Date(),
        radium_226: "",
        radium_228: "",
        bill_loading_number: '',
        ship_date: new Date(),
        trucking_company_id: 0,
        landfill_disposal_site: 0,
        tare_weight: "",
        loaded_weight: "",
        barrels_delivered: "",
        
        notes: '',
        //landfill_disposal_site: 0,
    };
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.open = function ($event) {
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    
    $scope.landfillLocationList = [];    
       
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_landfill.php';
        //alert(api_url);
        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/ticket/view_landfill/" + $scope.ticket.id;
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_landfill/" + $scope.ticket.id;
        }
    }
     
    $scope.SaveData = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }       
        if (!isEmpty($scope.ticket.ship_date)) {
            $scope.ticket.ship_date = moment($scope.ticket.ship_date).format("MM/DD/YYYY");
        }            

         if (isEmpty($scope.ticket.tare_weight)) {
            $scope.ticket.tare_weight = 0;
        }       
        
         if (isEmpty($scope.ticket.loaded_weight)) {
            $scope.ticket.loaded_weight = 0;
        }       
        
         if (isEmpty($scope.ticket.radium_226)) {
            $scope.ticket.radium_226 = 0;
        }       
		
		if (isEmpty($scope.ticket.radium_228)) {
            $scope.ticket.radium_228 = 0;
        }  

       if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       

        var api_url = window.cfg.apiUrl + 'ticket/add_landfill.php';
		console.log(api_url+"?"+$scope.ticket);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);
			 console.log("From is " + JSON.stringify(result.from))
			 console.log("From2 is " + JSON.stringify(result.from2))			 
             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 if (result.data.id !=undefined)
                 {
                     $scope.ticket.id = result.data.id;
                     $scope.UploadFile();
                 }
                 else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert ticket error.");

         });
    };
    
    $scope.Search = function () {        
       // $scope.PageIndex = 1;
       // $cookies.put("landfill_disposal_site", $scope.ticket.landfill_disposal_site);
    };

    $scope.LoadLandfillDisposalSites=function()
    {
	    //$cookies.put("landfill_disposal_site", $scope.landfill_disposal_site);
        $http.get(window.cfg.apiUrl + "landfilldisposalsites/get.php").success(function (result) {
            $scope.landfillDisposalSites = result.data;

			//if(!isEmpty(window.cfg.type_id))
			{		
			//	$scope.ticket.landfill_disposal_site=window.cfg.type_id;
			}
        });
    }

    $scope.init=function()
    {
	    AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });
        
        AppDataService.loadFluidTypeList(null, null, function (result) {
		 	$scope.fluidTypeList = result.data;
        }, function (result) { });
	    
    	AppDataService.loadTruckingCompanyList(null, null, function (result) {
           	$scope.truckingCompanyList = result.data;
        }, function (result) { });	   
	    
		//$scope.ticket.landfill_disposal_site = $cookies.get("landfill_disposal_site");
        //if (isEmpty($scope.ticket.landfill_disposal_site)) $scope.ticket.landfill_disposal_site = "0";
        //$cookies.put("landfill_disposal_site", $scope.ticket.landfill_disposal_site);
        $scope.LoadLandfillDisposalSites();
      }
    $scope.init();
}]);


swdApp.controller('ticketAddCtrl_oil', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.ticket = {
        ticket_number: '',
        date_delivered: new Date(),
        barrels_delivered: "",
        temperature: "",
        bsw: "",
        gravity: "",
        oil_price: "",
        notes: '',
    };
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.open = function ($event) {
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
       
    $scope.TopDecimalList = AppDataService.TopDecimalList;
	$scope.TopDecimalList.unshift({ id: 0, label: "Select a decimal" });
           
    
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_oil.php';
        //alert(api_url);
        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/ticket/view_oil/" + $scope.ticket.id;
                   }
                   else {
                       //toastr.error(data.message);
                       toastr.error("here is the errory homie!")
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_oil/" + $scope.ticket.id;
        }
    }
     
    $scope.SaveData = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }       


        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }
        
         //initialize numeric values if empty
        if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       
        
        if (isEmpty($scope.ticket.top_ft)) {
            $scope.ticket.top_ft = 0;
        }
        if (isEmpty($scope.ticket.top_in)) {
            $scope.ticket.top_in = 0;
        }
        if (isEmpty($scope.ticket.top_decimal)) {
            $scope.ticket.top_decimal = 0;
        }
        
		if (isEmpty($scope.ticket.bottom_ft)) {
            $scope.ticket.bottom_ft = 0;
        }
        if (isEmpty($scope.ticket.bottom_in)) {
            $scope.ticket.bottom_in = 0;
        }
		if (isEmpty($scope.ticket.bottom_decimal)) {
            $scope.ticket.bottom_decimal = 0;
        }
        
        if (isEmpty($scope.ticket.top_temperature)) {
            $scope.ticket.top_temperature = 0;
        }
        if (isEmpty($scope.ticket.bottom_temperature)) {
            $scope.ticket.bottom_temperature = 0;
        }
		if (isEmpty($scope.ticket.observed_temperature)) {
            $scope.ticket.observed_temperature = 0;
        }
        
        if (isEmpty($scope.ticket.bsw)) {
            $scope.ticket.bsw = 0;
        }
        
        if (isEmpty($scope.ticket.gravity)) {
            $scope.ticket.gravity = 0;
        }
		if (isEmpty($scope.ticket.tank_id)) {
            $scope.ticket.tank_id = 0;
        }
        
        if (isEmpty($scope.ticket.oil_price)) {
            $scope.ticket.oil_price = 0;
        }
        
        if (isEmpty($scope.ticket.total_dollars)) {
            $scope.ticket.total_dollars = 0;
        }
		if (isEmpty($scope.ticket.deduct)) {
            $scope.ticket.deduct = 0;
        }
        
        
        var api_url = window.cfg.apiUrl + 'ticket/add_oil.php';
		console.log(api_url+"?"+$scope.ticket);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);

             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 if (result.data.id !=undefined)
                 {
                     $scope.ticket.id = result.data.id;
                     $scope.UploadFile();
                 }
                 else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert ticket error.");

         });
    };

		$scope.loadOtherFluidList = function (params) {
	    	$scope.fluidTypeList = [];
		 	AppDataService.loadFluidTypeList2(params, null, function (result) {
		 		$scope.fluidTypeList = result.data;
        	}, function (result) { });
	    };


    $scope.init=function()
    {
	    $scope.tankList = [];
		 AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });
        

    }
    $scope.init();
}]);

swdApp.controller('ticketAddCtrl_water', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.ticket = {
        ticket_number: '',
        disposal_well_id: 0,//"Killdeer TRD #3",
        barrels_delivered: 0,
        water_type_id: 2,
        date_delivered: new Date(),
        delivery_method: '0',
        water_source_type: '0',
        source_well_id: "0",//48440,
        barrel_rate: 0.38,
        trucking_company: 'Horizon TRD #3',
        //trucking_company_id: '0',
        notes: '',
    };
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/get.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.wells = data.data;
            //alert($scope.wells.length);
        })
    }
    $scope.open = function ($event) {
        $scope.opened = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.formats = ['MM/dd/yyyy'];
    $scope.format = $scope.formats[0];
    
    $scope.disposalWellList = [];
    
    $scope.waterTypeList = [];  

    $scope.truckingCompanyList = [];    

    $scope.DeliveryMethodList = AppDataService.DeliveryMethodList;
    $scope.WaterSourceTypeList = AppDataService.WaterSourceTypeList;
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_water.php';
        //alert(api_url);
        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/ticket/view_water/" + $scope.ticket.id;
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view_water/" + $scope.ticket.id;
        }

    }
    
    //TYLER ADDED - Modified UploadFile function to UploadFileThenNextTicket
    $scope.UploadFileThenNextTicket = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_water.php';
        //alert(api_url);
        if ($scope.selectedFiles.length > 0) {
            var file = $scope.selectedFiles[0];
            myblockui();
            if (file.size > 5097152) {

                $scope.error = 'File size cannot exceed 5 MB';
                toastr.error($scope.error);
            }
            $upload.upload({
                url: api_url,
                method: 'POST',
                //headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
                //withCredentials: true,
                data: $scope.ticket,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
	               //$scope.ticket.disposal_well_id=13;
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/ticket/add_water/" + $scope.ticket.id;
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
	        //$scope.ticket.disposal_well_id=13;
            window.location.href = window.cfg.rootUrl + "/ticket/add_water/" + $scope.ticket.id;
        }

    }
    
    
    $scope.SaveData = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }       
		//$scope.ticket.disposal_well_id=13;
        var api_url = window.cfg.apiUrl + 'ticket/add_water.php';
		console.log(api_url+"?"+$scope.ticket);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);

             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 if (result.data.id !=undefined)
                 {
                     $scope.ticket.id = result.data.id;
                     $scope.UploadFile();
                 }
                 else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert ticket error.");

         });
    };

// TYLER ADDED -Modified SaveData function to go Next Ticket entry
$scope.NextTicket = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }       

        var api_url = window.cfg.apiUrl + 'ticket/add_water.php';
		console.log(api_url+"?"+$scope.ticket);
        myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result);

             if (result.code == "success") {
                 $.unblockUI();
                 //alert(result.data.id);
                 if (result.data.id !=undefined)
                 {
                     $scope.ticket.id = result.data.id;
                     $scope.UploadFileThenNextTicket();
                 }
                 else
                 {
                     
                 }
                 //redirect
             }
             else {
                 $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Insert ticket error.");

         });
    };

    $scope.minimum_barrel_warning = "";
    $scope.maximum_barrel_warning = "";
    $scope.warning_message = "";
    $scope.new_barrel_delivery = "";

    $scope.barrels_warning = function () {
        if ($scope.minimum_barrel_warning != undefined && $scope.maximum_barrel_warning != undefined) {
            if ($scope.minimum_barrel_warning != null && $scope.maximum_barrel_warning != null) {
                if ($scope.minimum_barrel_warning != "" && $scope.maximum_barrel_warning != "") {                    
                    if ($scope.ticket.barrels_delivered < $scope.minimum_barrel_warning) {
                        $scope.warning_message = "is a little water!";
                        $("#divBarrelWarning").modal("show");
                    }

                    if ($scope.ticket.barrels_delivered > $scope.maximum_barrel_warning) {
                        $scope.warning_message = "is a lot of water!";
                        $("#divBarrelWarning").modal("show");
                    }
                }
            }
        }     
    };

    $scope.keepit = function () {
        $("#divBarrelWarning").modal("hide");
    };

    $scope.changeit = function () {
        $scope.ticket.barrels_delivered = $scope.new_barrel_delivery;
        $("#divBarrelWarning").modal("hide");
    };

	$scope.loadOtherFluidList = function (params) {
	    $scope.fluidTypeList = [];
		 AppDataService.loadFluidTypeList2(params, null, function (result) {
		 	$scope.fluidTypeList = result.data;
        }, function (result) { });
	};
	    
    $scope.init=function()
    {
        $scope.getWells();
        AppDataService.loadDisposalWellList(null, null, function (result) {
            $scope.disposalWellList = result.data;
        }, function (result) {
        });
        AppDataService.loadWaterTypeList(null, null, function (result) {
            $scope.waterTypeList = result.data;
            //$scope.waterTypeList.unshift({ id: 0, type: "D" });
        }, function (result) { });
        AppDataService.loadTruckingCompanyList(null, null, function (result) {
            $scope.truckingCompanyList = result.data;
        }, function (result) { });
		 AppDataService.loadTankList(null, null, function (result) {
		 	$scope.tankList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);
swdApp.controller('ticketViewCtrl_oil', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.fileList = [];
    $scope.ticket = {};   
    
    $scope.LoadData=function()
    {
        if (window.cfg.Id > 0) {
           
            myblockui();
            var api_url = window.cfg.apiUrl + "ticket/get_oil.php?id=" + window.cfg.Id;
            //alert(api_url);
            $http.get(api_url)
                .success(function (result) {
                    if (result.data.length > 0) {
                        $scope.ticket = result.data[0];
                        $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
                        //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                        console.log($scope.ticket);
                    }
                    $.unblockUI();
                }).error(function (result) {
                    $.unblockUI();
                    toastr.error("Get tickets error.");
                });

            AppDataService.loadFileList_oil({ ticket_id: window.cfg.Id }, null, function (result) {
                $scope.fileList = result.data;
                console.log($scope.fileList);
            }, function (result) { });
        }
    }
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}
    $scope.init = function () {
        $scope.LoadData();
    }
    $scope.init();

    $scope.exportPDF = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_oil.php?id=" + window.cfg.Id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
}]);

swdApp.controller('ticketViewCtrl_water', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.fileList = [];
    $scope.ticket = {};   
    
    $scope.LoadData=function()
    {
        if (window.cfg.Id > 0) {
           
            myblockui();
            var api_url = window.cfg.apiUrl + "ticket/get_water.php?id=" + window.cfg.Id;
            //alert(api_url);
            $http.get(api_url)
                .success(function (result) {
                    if (result.data.length > 0) {
                        $scope.ticket = result.data[0];
                        $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
                        //$scope.ticket.date_delivered = new Date($scope.ticket.date_delivered);
                        if ($scope.ticket.source_well_id != undefined) {
                            api_url = window.cfg.apiUrl + "wells/get.php?id=" + $scope.ticket.source_well_id;
                            
                            $http.get(api_url).success(function (data) {
                                //alert(data.data.name);
                                //$scope.ticket.source_well_name = data.data.name;
                            });

                        }
                        console.log($scope.ticket);
                    }
                    $.unblockUI();
                }).error(function (result) {
                    $.unblockUI();
                    toastr.error("Get tickets error.");
                });

            AppDataService.loadFileList_water({ ticket_id: window.cfg.Id }, null, function (result) {
                $scope.fileList = result.data;
                console.log($scope.fileList);
            }, function (result) { });

        }
    }
	$scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Ticket #'+$scope.ticket.ticket_number,'width=760,height=600,menu=0,scrollbars=1');
	}
    $scope.init = function () {
        $scope.LoadData();
    }
    $scope.init();

    $scope.exportPDF = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_water.php?id=" + window.cfg.Id;

        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error.");
        });
    };
}]);


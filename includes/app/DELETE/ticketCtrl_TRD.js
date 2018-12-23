// SEARCH "params.push("location=" + $scope.location);"

swdApp.controller('ticketCtrl', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
    $scope.ticketList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    //$scope.SearchDisposalWell = "";
    //$scope.SearchLocal="";
	
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
        //$scope.SearchDisposalWell = "";
        //$scope.SearchLocal="";
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
        //$cookies.put("SearchDisposalWell", "");
        //$cookies.put("SearchLocal","");
    }
    $scope.LoadData = function () {
        myblockui();
        $cookies.put("SearchText", $scope.SearchText);
        //$cookies.put("SearchDisposalWell", $scope.SearchDisposalWell);
        //$cookies.put("SearchLocal", $scope.SearchLocal);
		$cookies.put("SearchStartDate",$scope.SearchStartDate);
		$cookies.put("SearchEndDate",$scope.SearchEndDate);
       var api_url = window.cfg.apiUrl + "ticket/get_trd.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        $http.get(api_url)
            .success(function (result) {
	            console.log(result);
                $scope.ticketList = result.data;
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;

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


    $scope.deleteTicket = function (id) {
        console.log(id);
        if (confirm("Are you sure to delete this ticket?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "ticket/delete_trd.php?id=" + id).success(function (result) {
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
    };
    //$scope.LoadDisposalWells=function()
    //{
    //    $http.get(window.cfg.apiUrl + "disposalwell/get.php").success(function (result) {
    //        $scope.DisposalWells = result.data;
	//		if(!isEmpty(window.cfg.disposal_well_id))
	//		{		
	//			$scope.SearchDisposalWell=window.cfg.disposal_well_id;
	//		}
    //   });
    //}
    //$scope.LoadLocals=function()
    //{
    //    $http.get(window.cfg.apiUrl + "local/get.php").success(function (result) {
    //        $scope.Locations = result.data;
	//		if(!isEmpty(window.cfg.location_id))
	//		{		
	//			$scope.SearchLocation=window.cfg.location_id;
	//		}
    //    });
    //}

    $scope.init = function () {
        $scope.SearchText = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        //$scope.SearchDisposalWell = $cookies.get("SearchDisposalWell");
        //if (isEmpty($scope.SearchDisposalWell)) $scope.SearchDisposalWell = "0";
        //scope.SearchLocal = $cookies.get("Local");
        //if (isEmpty($scope.SearchLocal)) $scope.SearchLocal = "0";
		if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}
        $scope.LoadData();
        //$scope.LoadDisposalWells();
        //$scope.LoadLocal();
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
            var api_url = window.cfg.apiUrl + "ticket/get_trd.php?id=" + window.cfg.Id;
            //alert(api_url);
            $http.get(api_url)
                .success(function (result) {
	                console.log(result);
                    if (result.data.length > 0) {
                        $scope.ticket = result.data[0];
                        $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
                        

						//convert boolean values to yes/no
                        if ($scope.ticket.washout == true) {
	                        $scope.ticket.washout = "Yes";
                        } else {
	                        $scope.ticket.washout = "No";
                        }
                          if ($scope.ticket.h2s_exists == true) {
	                        $scope.ticket.h2s_exists = "Yes";
                        } else {
	                        $scope.ticket.h2s_exists = "No";
                        }

			        console.log($scope.ticket.washoutValue);
                        
                        if ($scope.ticket.picocuries == true) {
	                        $scope.ticket.picocuries = "Yes";
                        } else {
	                        $scope.ticket.picocuries = "No";
                        }
           
                        console.log($scope.ticket);
                    }
                    $.unblockUI();
                }).error(function (result) {
                    $.unblockUI();
                    toastr.error("Get tickets error.");
                });

            AppDataService.loadFileList({ ticket_id: window.cfg.Id }, null, function (result) {
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
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_TRD.php?id=" + window.cfg.Id;

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
    $scope.localList = [];
    $scope.local = 0;

    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
        $scope.disposalWellList.unshift({ id: 0, common_name: "All" });
        //$scope.disposalwell = result.data[0].id;
    }, function (result) {
    });
    

	//AppDataService.loadLocalList(null, null, function (result) {
    //    $scope.localList = result.data;
    //    $scope.localList.unshift({ id: 0, common_name: "All" });
    //    //$scope.disposalwell = result.data[0].id;
    //}, function (result) {
    //});

    $scope.truckingCompanyList = [];
    $scope.truckingcompany = 0;
    AppDataService.loadTruckingCompanyList(null, null, function (result) {
        $scope.truckingCompanyList = result.data;
        $scope.truckingCompanyList.unshift({ id: 0, name: "All" });
    }, function (result) { });
	
	
	//$scope.producerList = [];
    //$scope.producer = 0;
    //AppDataService.loadProducerList(null, null, function (result) {
    //    $scope.producerList = result.data;
    //    $scope.producerList.unshift({ id: 0, name: "All" });
    //}, function (result) { });
	
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

        //if ($scope.local != 0)
        //    params.push("location=" + $scope.local);

        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_TRD.php?" + params.join("&");
        //alert(api_url);
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            if (result.data.length == 0)
                toastr.error("Haven't found any tickets.");
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

swdApp.controller('ticketEditCtrl', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
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

        $http.get(window.cfg.apiUrl + "file/delete.php?id=" + fileid).success(function (result) {
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
    
    $scope.Microrens2Picocuries = function() {
		$scope.ticket.tenorm_picocuries = ($scope.ticket.microrens*0.599).toFixed(3);//0.599;
		$scope.isMicrorensDisabled=0;
		$scope.isPicocuriesDisabled=1;
    } 
    $scope.Picocuries2Microrens = function() {
		$scope.ticket.microrens = ($scope.ticket.tenorm_picocuries*1.669).toFixed(3);//1.669;
		$scope.isPicocuriesDisabled=0;
		$scope.isMicrorensDisabled=1;
    }  
    
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

    $scope.truckingCompanyList = [];
    AppDataService.loadTruckingCompanyList(null, null, function (result) {
        $scope.truckingCompanyList = result.data;
    }, function (result) { });
    
    $scope.rigsList = [];
    AppDataService.loadRigsList(null, null, function (result) {
        $scope.rigsList = result.data;
    }, function (result) { });
    
    //$scope.producerList = [];
    //AppDataService.loadProducerList(null, null, function (result) {
    //    $scope.producerList = result.data;
   // }, function (result) { });

    $scope.DeliveryMethodList = AppDataService.DeliveryMethodList;
    $scope.WaterSourceTypeList = AppDataService.WaterSourceTypeList;
    $scope.UploadFile=function()
    {       
        var api_url = window.cfg.apiUrl + 'file/upload.php';
        
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
                   window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
                   $.unblockUI();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
            window.location.href = window.cfg.rootUrl + "/ticket/view/" + window.cfg.Id;
        }
    }
    $scope.SaveData = function () {
        var api_url = window.cfg.apiUrl + 'ticket/update_TRD.php';
        if (!isEmpty($scope.ticket.date_delivered))
        {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }
        
         //initialize numeric values if empty
        if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       
		if (isEmpty($scope.ticket.barrel_rate)) {
            $scope.ticket.barrel_rate = 0.00;
        }       
        
        if (isEmpty($scope.ticket.percent_solid)) {
            $scope.ticket.percent_solid = 0;
        }
        
        if (isEmpty($scope.ticket.percent_h2o)) {
            $scope.ticket.percent_h2o = 0;
        }
        
        if (isEmpty($scope.ticket.percent_interphase)) {
            $scope.ticket.percent_interphase = 0;
        }
        
        if (isEmpty($scope.ticket.percent_oil)) {
            $scope.ticket.percent_oil = 0;
        }
        
        if (isEmpty($scope.ticket.picocuries)) {
            $scope.ticket.picocuries = 0;
        }
        if (isEmpty($scope.ticket.microrens)) {
            $scope.ticket.microrens = 0;
        }
        
        if (isEmpty($scope.ticket.washout_barrels)) {
            $scope.ticket.washout_barrels = 0;
        }
        if (isEmpty($scope.ticket.source_well_id)) {
            $scope.ticket.source_well_id = 0;
        }
        if (isEmpty($scope.ticket.trucking_company_id)) {
            $scope.ticket.trucking_company_id = 0;
        }
        if (isEmpty($scope.ticket.rig_id)) {
            $scope.ticket.rig_id = 0;
        }
        if (isEmpty($scope.ticket.tenorm_picocuries)) {
            $scope.ticket.tenorm_picocuries = 0;
        }
        //set boolean values for washout and picocuries
        if ($scope.ticket.washoutValue == 1) {
	    	$scope.ticket.washoutValue = true;    
        } else {
	        $scope.ticket.washoutValue = false;
        }
        
        if ($scope.ticket.picocuriesValue == 1) {
	    	$scope.ticket.picocuriesValue = true;    
        } else {
	        $scope.ticket.picocuriesValue = false;
        }
        if ($scope.ticket.h2s_exists == 1) {
	    	$scope.ticket.h2s_exists = true;    
        } else {
	        $scope.ticket.h2s_exists = false;
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
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Save ticket error.");

         });
    };

    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "ticket/get_trd.php?id=" + window.cfg.Id;
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
			        console.log($scope.ticket.washout);
			        if ($scope.ticket.washout == true) {
				    	$scope.ticket.washoutValue = 1
				    	$scope.ticket.test=1;    
			        } else {
				        $scope.ticket.washoutValue = 0;
				        $scope.ticket.test=0;
			        }
					if ($scope.ticket.h2s_exists == true) {
				    	$scope.ticket.h2s_exists = 1
				    	$scope.ticket.test2=1;    
			        } else {
				        $scope.ticket.h2s_exists = 0;
				        $scope.ticket.test2=0;
			        }
			        console.log($scope.ticket.washoutValue);
			        
			        if ($scope.ticket.picocuries == true) {
				    	$scope.ticket.picocuriesValue = 1;    
			        } else {
				        $scope.ticket.picocuriesValue = 0;
			        }
					if ($scope.ticket.h2s_exists == true) {
				    	$scope.ticket.h2s_exists = 1;    
			        } else {
				        $scope.ticket.h2s_exists = 0;
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
        AppDataService.loadFileList({ ticket_id: window.cfg.Id }, null, function (result) {
            $scope.fileList = result.data;
            console.log($scope.fileList);
        }, function (result) { });
    }


    $scope.minimum_barrel_warning = "";
    $scope.maximum_barrel_warning = "";
    $scope.warning_message = "";
    $scope.new_barrel_delivery = "";

    $scope.barrels_warning = function () {
        if ($scope.minimum_barrel_warning != undefined && $scope.maximum_barrel_warning != undefined) {
            if ($scope.minimum_barrel_warning != null && $scope.maximum_barrel_warning != null) {
                if ($scope.minimum_barrel_warning != "" && $scope.maximum_barrel_warning != "") {
                    if ($scope.ticket.barrels_delivered < $scope.minimum_barrel_warning) {
                        $scope.warning_message = "is a little fluid!";
                        $("#divBarrelWarning").modal("show");
                    }

                    if ($scope.ticket.barrels_delivered > $scope.maximum_barrel_warning) {
                        $scope.warning_message = "is a lot of fluid!";
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
    
    $scope.truckTypeList = [];  
    $scope.TruckTypeList = AppDataService.TruckTypeList;

    $scope.init = function () {
        $scope.getWells();
        $scope.LoadData();
        
        AppDataService.loadTruckTypeList(null, null, function (result) {
            $scope.truckTypeList = result.data;
        }, function (result) { });
        AppDataService.loadFluidTypeList(null, null, function (result) {
            $scope.fluidTypeList = result.data;
        }, function (result) { });
        AppDataService.loadTruckingCompanyList(null, null, function (result) {
           $scope.truckingCompanyList = result.data;
        }, function (result) { });
         AppDataService.loadRigsList(null, null, function (result) {
           $scope.RigsList = result.data;
        }, function (result) { });
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

swdApp.controller('ticketAddCtrl', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.ticket = {
        ticket_number: '',
        date_delivered: new Date,
        trucking_company_id: 0,
        fluid_type_id: 0,
        truck_type: 0,
        tank_id: 0,
        h2s_exists: 0,
    };
    
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    $scope.wells = [];
    $scope.getWells = function () {
        var api_url = window.cfg.apiUrl + "wells/get_trd.php";
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
    
    //$scope.disposalWellList = [];
    $scope.localList = [];
    
    //$scope.waterTypeList = [];  
    $scope.fluidTypeList = []; 
    $scope.truckingCompanyList = [];   
    $scope.rigsList = [];    
    $scope.tankList = [];    
	$scope.producerList = [];

    $scope.DeliveryMethodList = AppDataService.DeliveryMethodList;
    $scope.WaterSourceTypeList = AppDataService.WaterSourceTypeList;
    $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload.php';
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
	               console.log(data);
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       window.location.href = window.cfg.rootUrl + "/ticket/view/" + $scope.ticket.id;
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
            window.location.href = window.cfg.rootUrl + "/ticket/view/" + $scope.ticket.id;
        }

    }
    
    //TYLER ADDED - Modified UploadFile function to UploadFileThenNextTicket
    $scope.UploadFileThenNextTicket = function () {
        var api_url = window.cfg.apiUrl + 'file/upload.php';
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
                       window.location.href = window.cfg.rootUrl + "/ticket/add/" + $scope.ticket.id;
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
            window.location.href = window.cfg.rootUrl + "/ticket/add/" + $scope.ticket.id;
        }

    }
    
    $scope.SaveData = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        
        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }  
        
        //initialize numeric values if empty
        if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       
        
        if (isEmpty($scope.ticket.percent_solid)) {
            $scope.ticket.percent_solid = 0;
        }
        
        if (isEmpty($scope.ticket.percent_h2o)) {
            $scope.ticket.percent_h2o = 0;
        }
        
        if (isEmpty($scope.ticket.percent_interphase)) {
            $scope.ticket.percent_interphase = 0;
        }
        
        if (isEmpty($scope.ticket.percent_oil)) {
            $scope.ticket.percent_oil = 0;
        }
        
        if (isEmpty($scope.ticket.picocuries)) {
            $scope.ticket.picocuries = 0;
        }
        if (isEmpty($scope.ticket.microrens)) {
            $scope.ticket.microrens = 0;
        }
        
        if (isEmpty($scope.ticket.washout_barrels)) {
            $scope.ticket.washout_barrels = 0;
        }
        
        //set boolean values for washout and picocuries
        if ($scope.ticket.washoutValue == 1) {
	    	$scope.ticket.washoutValue = true;    
        } else {
	        $scope.ticket.washoutValue = false;
        }
        
        if ($scope.ticket.picocuriesValue == 1) {
	    	$scope.ticket.picocuriesValue = true;    
        } else {
	        $scope.ticket.picocuriesValue = false;
        }
        if ($scope.ticket.h2s_exists == 1) {
	        $scope.ticket.h2s_exists = true;
        } else {
	        $scope.ticket.h2s_exists = false;
        }
		
		console.log("H2S is " + $scope.ticket.h2s_exists)
		var api_url = window.cfg.apiUrl + 'ticket/add_trd.php';
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
             console.log("here we are again");
             console.log("message " + result.message);
             console.log("message2 " + result.message2);
                          
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
        
        //initialize numeric values if empty
        if (isEmpty($scope.ticket.barrels_delivered)) {
            $scope.ticket.barrels_delivered = 0;
        }       
        
        if (isEmpty($scope.ticket.percent_solid)) {
            $scope.ticket.percent_solid = 0;
        }
        
        if (isEmpty($scope.ticket.percent_h2o)) {
            $scope.ticket.percent_h2o = 0;
        }
        
        if (isEmpty($scope.ticket.percent_interphase)) {
            $scope.ticket.percent_interphase = 0;
        }
        
        if (isEmpty($scope.ticket.percent_oil)) {
            $scope.ticket.percent_oil = 0;
        }
        
        if (isEmpty($scope.ticket.picocuries)) {
            $scope.ticket.picocuries = 0;
        }
        if (isEmpty($scope.ticket.microrens)) {
            $scope.ticket.picocuries = 0;
        }
        
        if (isEmpty($scope.ticket.washout_barrels)) {
            $scope.ticket.washout_barrels = 0;
        }
        
        //set boolean values for washout and picocuries
        if ($scope.ticket.washoutValue == 1) {
	    	$scope.ticket.washoutValue = true;    
        } else {
	        $scope.ticket.washoutValue = false;
        }
        
        if ($scope.ticket.picocuriesValue == 1) {
	    	$scope.ticket.picocuriesValue = true;    
        } else {
	        $scope.ticket.picocuriesValue = false;
        }      

        var api_url = window.cfg.apiUrl + 'ticket/add_trd.php';
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

    /*$scope.minimum_barrel_warning = "";
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
    
        $scope.barrels_warning = function () {
        if ($scope.minimum_barrel_warning != undefined && $scope.maximum_barrel_warning != undefined) {
            if ($scope.minimum_barrel_warning != null && $scope.maximum_barrel_warning != null) {
                if ($scope.minimum_barrel_warning != "" && $scope.maximum_barrel_warning != "") {                    
                    if ($scope.ticket.barrels_delivered < $scope.minimum_barrel_warning) {
                        $scope.warning_message = "is a little fluid!";
                        $("#divBarrelWarning").modal("show");
                    }

                    if ($scope.ticket.barrels_delivered > $scope.maximum_barrel_warning) {
                        $scope.warning_message = "is a lot of fluid!";
                        $("#divBarrelWarning").modal("show");
                    }
                }
            }
        }     
    };*/


    $scope.keepit = function () {
        $("#divBarrelWarning").modal("hide");
    };

    $scope.changeit = function () {
        $scope.ticket.barrels_delivered = $scope.new_barrel_delivery;
        $("#divBarrelWarning").modal("hide");
    };
    
    
    $scope.find_operator = function() {
	    console.log("Source well ID is " + $scope.ticket.source_well_id)
	    console.log("Source well name is  " + $scope.ticket.source_well_name.source_well_name)
	    $scope.ticket.store_source_well_name = $scope.ticket.source_well_name.source_well_name;
	    console.log("Rename is  " + $scope.ticket.store_source_well_name)
		var api_url = window.cfg.apiUrl + 'ticket/find_operator_trd.php';
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log(result.operator_name);
             console.log(result.id);
             $scope.ticket.source_well_operator_name = result.operator_name;
             
             //console.log(result.post);
             //console.log(result.request);
             //console.log($scope.ticket.ticket_num)
         });
    }   
    
    $scope.find_rig_info = function() {
		var api_url = window.cfg.apiUrl + 'ticket/find_rig_info_trd.php';
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log("name is " + result.name);
             console.log("ID is " + result.id);
             $scope.ticket.company_man_name = result.company_man_name;
             $scope.ticket.company_man_number = result.company_man_number;
             //console.log(result.post);
             //console.log(result.request);
             //console.log($scope.ticket.ticket_num)
         });
    }  
    
    $scope.Microrens2Picocuries = function() {
		$scope.ticket.tenorm_picocuries = ($scope.ticket.microrens*0.599).toFixed(3);
		$scope.isMicrorensDisabled=0;
		$scope.isPicocuriesDisabled=1;
    } 
    $scope.Picocuries2Microrens = function() {
		$scope.ticket.microrens = ($scope.ticket.tenorm_picocuries*1.669).toFixed(3);
		$scope.isPicocuriesDisabled=0;
		$scope.isMicrorensDisabled=1;
    }  

    $scope.truckTypeList = [];  
    $scope.TruckTypeList = AppDataService.TruckTypeList;

    $scope.init=function()
    {
	    console.log("Tyler3 console line test");
        $scope.getWells();
  
        
        //$scope.getWells();
        //AppDataService.loadLocallList(null, null, function (result) {
        //    $scope.localList = result.data;
        //}, function (result) { });
        AppDataService.loadTruckTypeList(null, null, function (result) {
            $scope.truckTypeList = result.data;
        }, function (result) { });
        AppDataService.loadFluidTypeList(null, null, function (result) {
            $scope.fluidTypeList = result.data;
        }, function (result) { });
        AppDataService.loadTruckingCompanyList(null, null, function (result) {
           $scope.truckingCompanyList = result.data;
        }, function (result) { });
        AppDataService.loadTankList(null, null, function (result) {
           $scope.tankList = result.data;
        }, function (result) { });
        AppDataService.loadRigsList(null, null, function (result) {
           $scope.rigsList = result.data;
        }, function (result) { });
        
        //AppDataService.loadProducerList(null, null, function (result) {
        //    $scope.producerList = result.data;
        //}, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
        });
    }
    $scope.init();
}]);
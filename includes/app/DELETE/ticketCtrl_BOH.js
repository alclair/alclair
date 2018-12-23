swdApp.controller('ticketCtrl', ['$http', '$scope', '$cookies', function ($http, $scope,$cookies) {
    $scope.ticketList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    $scope.SearchDisposalWell = "";
	
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
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
        $cookies.put("SearchDisposalWell", "");
    }
    $scope.LoadData = function () {
        myblockui();
        $cookies.put("SearchText", $scope.SearchText);
        $cookies.put("SearchDisposalWell", $scope.SearchDisposalWell);
		$cookies.put("SearchStartDate",$scope.SearchStartDate);
		$cookies.put("SearchEndDate",$scope.SearchEndDate);
        var api_url = window.cfg.apiUrl + "ticket/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&SearchDisposalWell="+$scope.SearchDisposalWell+"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        //alert(api_url);
        $http.get(api_url)
            .success(function (result) {
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

        $http.get(window.cfg.apiUrl + "ticket/delete.php?id=" + id).success(function (result) {
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
    
    $scope.init = function () {
        $scope.SearchText = $cookies.get("SearchText");
        if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        $scope.SearchDisposalWell = $cookies.get("SearchDisposalWell");
        if (isEmpty($scope.SearchDisposalWell)) $scope.SearchDisposalWell = "0";
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
            var api_url = window.cfg.apiUrl + "ticket/get.php?id=" + window.cfg.Id;
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
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_BOH.php?id=" + window.cfg.Id;

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

    $scope.waterTypeList = [];
    $scope.water_type_id = 0;
    AppDataService.loadWaterTypeList(null, null, function (result) {
        $scope.waterTypeList = result.data;
        $scope.waterTypeList.unshift({ id: 0, type: "All" });
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
		if ($scope.water_type_id != 0)
            params.push("water_type_id=" + $scope.water_type_id);

        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_BOH.php?" + params.join("&");
        //alert(api_url);
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            if (result.data.length == 0)
                toastr.error("Haven't found ticket DANG");
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

// TYLER CODE FOR RECONCILIATION REPORT
swdApp.controller('ticketExportPDF_ReconReport', ['$http', '$scope', '$cookies', 'AppDataService', '$upload', function ($http, $scope, $cookies, AppDataService, $upload) {
    $scope.startdate = new Date();
    $scope.enddate = new Date(); 
    $scope.timepicker = "";
    $scope.timepicker2 = "";
    $scope.SearchDisposalWell = "";
    $scope.ticket_creator = '0';

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

    $scope.ClearSearch=function()
    {
        $scope.SearchDisposalWell = "";
        $scope.LoadData();
        $cookies.put("SearchDisposalWell", "");
    }
     
     //$scope.LoadData = function () {
	 //    myblockui();
	 //    $cookies.put("SearchDisposalWell", $scope.SearchDisposalWell);
     
        
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
    
    $scope.export = function () {
        var startdate = moment($scope.startdate).format("YYYY-MM-DD");
        var enddate = moment($scope.enddate).format("YYYY-MM-DD");
        
        if ($scope.SearchDisposalWell == 0 )  {
			toastr.error("Please choose a disposal well") }
		else if ( isEmpty($scope.timepicker) || isEmpty($scope.timepicker2) ) {
			toastr.error("Please correct the time.") }
		else {
		
        var SearchDisposalWell = $scope.SearchDisposalWell;
        //var ctrl.timepicker = moment($scope.ctrl.timepicker).format("H:i:s");

		        myblockui();

        var params = [];
        //if ($scope.enableDate == true)
        params.push("startdate=" + startdate + " " + $scope.timepicker +  "&enddate=" + enddate + " " + $scope.timepicker2 + "&SearchDisposalWell=" + $scope.SearchDisposalWell + "&TicketCreator=" + $scope.ticket_creator);
        //params.push("startdate=" + startdate + “ “ + ctrl.timepicker +  "&enddate=" + enddate + " " + ctrl.timepicker2);
		console.log($scope.timepicker)
		console.log($scope.timepicker2)
		
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF_ReconReport.php?" + params.join("&");
        //alert(api_url);
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            //console.log(result.data);
			//console.log(result.test)
			
            if (result.data.length == 0)
                toastr.error("No tickets found with the search criteria.");
            else
                window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error (BOH Report).");
        });
        } // END ELSE STATEMENT
    };
    
    $scope.email = function() {
	    var startdate = moment($scope.startdate).format("YYYY-MM-DD");
        var enddate = moment($scope.enddate).format("YYYY-MM-DD");
        
         if ($scope.SearchDisposalWell == 0 )  {
			toastr.error("Please choose a disposal well") }
		else if ( isEmpty($scope.timepicker) || isEmpty($scope.timepicker2) ) {
			toastr.error("Please correct the time.") }
		else {
        
        var SearchDisposalWell = $scope.SearchDisposalWell;
        //var ctrl.timepicker = moment($scope.ctrl.timepicker).format("H:i:s");

        myblockui();

        var params = [];
        //if ($scope.enableDate == true)
        params.push("startdate=" + startdate + " " + $scope.timepicker +  "&enddate=" + enddate + " " + $scope.timepicker2 + "&SearchDisposalWell=" + $scope.SearchDisposalWell + "&TicketCreator=" + $scope.ticket_creator);
        
        //params.push("startdate=" + startdate + “ “ + ctrl.timepicker +  "&enddate=" + enddate + " " + ctrl.timepicker2);


        var api_url = window.cfg.apiUrl + "export/emailTicketPDF_ReconReport.php?" + params.join("&");
        //alert(api_url);
        $http.get(api_url).success(function (result) {
            $.unblockUI();
            console.log(result.data);

            if (result.data.length == 0)
                toastr.error("Please choose a disposal well.");
            else
                window.open(window.cfg.rootUrl + "/data/exportpdf/" + result.data);
        }).error(function () {
            $.unblockUI();
            toastr.error("Get error (BOH Report).");
        });
        } // END ELSE STATEMENT
    };    //$scope.init = function () {
    //    $scope.getWells();
    //};
    //$scope.init();
    
    $scope.init = function () {
       // $scope.SearchText = $cookies.get("SearchText");
       // if (isEmpty($scope.SearchText)) $scope.SearchText = "";
        $scope.SearchDisposalWell = $cookies.get("SearchDisposalWell");
        if (isEmpty($scope.SearchDisposalWell)) $scope.SearchDisposalWell = "0";
		//$scope.LoadData();
        $scope.LoadDisposalWells();
        AppDataService.loadTicketCreators(null, null, function (result) {
           $scope.creatorList = result.data;
        }, function (result) { });
    }
    $scope.init();
}]);






swdApp.controller('ticketEditCtrl', ['$http', '$scope', 'AppDataService', '$upload',  function ($http, $scope, AppDataService, $upload) {
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
    AppDataService.loadDisposalWellList(null, null, function (result) {
        $scope.disposalWellList = result.data;
    }, function (result) {
    });
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
    $scope.filterSock = AppDataService.filterSock;
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
        var api_url = window.cfg.apiUrl + 'ticket/update.php';
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
        var api_url = window.cfg.apiUrl + "ticket/get.php?id=" + window.cfg.Id;
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

    $scope.init = function () {
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

swdApp.controller('ticketAddCtrl', ['$http', '$scope', 'AppDataService', '$upload', function ($http, $scope, AppDataService, $upload) {
    $scope.greeting = 'Hola!';
    $scope.ticket = {
        ticket_number: '',
        disposal_well_id: 0,
        barrels_delivered: 0,
        water_type_id: 0,
        date_delivered: "",
        delivery_method: 'Truck',
        water_source_type: '0',
        source_well_id: 0,
        trucking_company_id: 0,
        filter_sock:'0',
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
    $scope.filterSock = AppDataService.filterSock;
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
			if($scope.image_with_ticket == true) {
				toastr.error("Please add an attachment before continuing.");
				$.unblockUI();
           }
           else {
            window.location.href = window.cfg.rootUrl + "/ticket/view/" + $scope.ticket.id; }
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
			if($scope.image_with_ticket == true && $scope.selectedFiles.length <= 0) {
				toastr.error("Please add an attachment before continuing.");
				$.unblockUI();
           }
           else {
            window.location.href = window.cfg.rootUrl + "/ticket/add/" + $scope.ticket.id; };
        }

    }
    
    
    $scope.SaveData = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
        var api_url = window.cfg.apiUrl + 'ticket/does_exist.php';
		 	//myblockui();
		 	$http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.ticket),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
			.success(function (result) {
             	console.log(result.ticket_exists);
			 	console.log($scope.ticket.ticket_number)
			 	if ($scope.allow_duplicate_tickets == false  && result.ticket_exists != null ) {
			 		toastr.error("Not allowed to have duplicate tickets!");  
			 		$.unblockUI(); 
			 		}
			 	else if ($scope.image_with_ticket == true) {
			 			toastr.error("Please add an attachment before cotinuing.");
			 			$.unblockUI();
           			}
           		else {


        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }    
        console.log($scope.ticket.ticket_number)
        //console.log( isEmpty($scope.ticket.ticket_number) )
        if(typeof $scope.ticket.ticket_number === 'object') { 
	       $scope.ticket.ticket_number = $scope.ticket.ticket_number.name;
        }
        //$scope.ticket.ticket_number = $scope.ticket.ticket_number.name;
        var api_url = window.cfg.apiUrl + 'ticket/add.php';
		console.log(api_url+"?"+$scope.ticket);
        //myblockui();
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
                   }
         });
       //}  // END THE ELSE STATEMENT
    }

// TYLER ADDED -Modified SaveData function to go Next Ticket entry
$scope.NextTicket = function (uploaddocument) {
        //$scope.ticket.date_delivered = $scope.ticket.date_delivered.getTime() / 1000;
       var api_url = window.cfg.apiUrl + 'ticket/does_exist.php';
		 	//myblockui();
		 	$http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.ticket),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
			.success(function (result) {
             	console.log(result.ticket_exists);
			 	console.log($scope.ticket.ticket_number)
			 	if ($scope.allow_duplicate_tickets == false  && result.ticket_exists != null ) {
			 		toastr.error("Not allowed to have duplicate tickets!");  
			 		$.unblockUI(); 
			 		}
			 	else if  ($scope.image_with_ticket == true && $scope.selectedFiles.length <= 0) {
			 			toastr.error("Please add an attachment before continuing .");
			 			$.unblockUI();
           			}
		   		else {

        if (!isEmpty($scope.ticket.date_delivered)) {
            $scope.ticket.date_delivered = moment($scope.ticket.date_delivered).format("MM/DD/YYYY");
        }    
        console.log($scope.ticket.ticket_number)
		if(typeof $scope.ticket.ticket_number === 'object') { 
	       $scope.ticket.ticket_number = $scope.ticket.ticket_number.name;
        }
        //$scope.ticket.ticket_number = $scope.ticket.ticket_number.name;
        var api_url = window.cfg.apiUrl + 'ticket/add.php';
		console.log(api_url+"?"+$scope.ticket);
        //myblockui();
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
                   }
         });
       //}  // END THE ELSE STATEMENT    
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

    $scope.ticket_warning = function() {
		var api_url = window.cfg.apiUrl + 'ticket/does_exist.php';
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             console.log($scope.ticket_exists);
             if (result.ticket_exists != null) {
			 	toastr.error("This is a duplicate ticket!");
                 $.unblockUI();
             }
             else {
                 $.unblockUI();
                 //toastr.error("Not a repeated ticket.");
             }
         });
    }
    
    $scope.getTicket_Numbers = function () {
        var api_url = window.cfg.apiUrl + "ticket/get_ticket_numbers.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.tickets = data.data;
            //alert($scope.wells.length);
        })
    }

    $scope.init=function()
    {
        $scope.getWells();
		$scope.getTicket_Numbers();
        AppDataService.loadDisposalWellList(null, null, function (result) {
            $scope.disposalWellList = result.data;
        }, function (result) {
        });
        AppDataService.loadWaterTypeList(null, null, function (result) {
            $scope.waterTypeList = result.data;
        }, function (result) { });
        AppDataService.loadTruckingCompanyList(null, null, function (result) {
            $scope.truckingCompanyList = result.data;
        }, function (result) { });

        $http.get(window.cfg.rootUrl + "/api/settings/get.php").success(function (data) {
            $scope.minimum_barrel_warning = data.minimum_barrel_warning;
            $scope.maximum_barrel_warning = data.maximum_barrel_warning;
            $scope.image_with_ticket = data.image_with_ticket;
			$scope.allow_duplicate_tickets = data.allow_duplicate_tickets;
        });
    }
    $scope.init();
}]);
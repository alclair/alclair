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
        if (confirm("Are you sure you want to delete this ticket?") == false) {
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
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF.php?id=" + window.cfg.Id;

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
            
        var api_url = window.cfg.apiUrl + "export/exportTicketPDF.php?" + params.join("&");
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
                    console.log($scope.ticket.filter_sock)
                    console.log($scope.ticket.delivery_method)
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
        date_delivered: new Date(),
        delivery_method: '0',
        water_source_type: '0',
        source_well_id: 0,
        trucking_company_id: 0,
        notes: '',
        filter_sock:'0',
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
	$scope.getTicket_Numbers = function () {
        var api_url = window.cfg.apiUrl + "ticket/get_ticket_numbers.php";
        //alert(api_url);
        $http.get(api_url).success(function (data) {
            $scope.tickets = data.data;
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
           /* if($scope.image_with_ticket == true) {
                   toastr.error("Please add an attachment before continuing.");
                   $.unblockUI();
               }*/
          //  else {
            	window.location.href = window.cfg.rootUrl + "/ticket/view/" + $scope.ticket.id; }
        //}

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
	        /*if($scope.image_with_ticket == true) {
				toastr.error("Please add an attachment before continuing.");
				$.unblockUI();
           }*/
          //else {
            window.location.href = window.cfg.rootUrl + "/ticket/add/" + $scope.ticket.id; }
        //}

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
			 	else if ($scope.image_with_ticket == true && $scope.selectedFiles.length <= 0) {
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
			 	else if  ($scope.image_with_ticket == true  && $scope.selectedFiles.length <= 0) {
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
             console.log(result.ticket_exists);
			 console.log(result.post);
             console.log(result.request);
             //console.log($scope.ticket.ticket_num)
             if (result.ticket_exists != null) {
			 //if ($scope.ticket.ticket_number.id != null) {
			 	toastr.error("This is a duplicate ticket!");
                 $.unblockUI();
             }
             else {
                 $.unblockUI();
                 //toastr.error("Not a repeated ticket.");
             }
         });
    }   

	$scope.product_type = function() {
		var api_url = window.cfg.apiUrl + 'ticket/product_type_fill.php';
        //myblockui();
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.ticket),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             //console.log(result.ticket_exists);
             //console.log(result.post);
             console.log(result.trucking_company_id);
             $scope.ticket.water_source_type = result.water_source_type;
             $scope.ticket.delivery_method = result.delivery_method;
             $scope.ticket.trucking_company_id = result.trucking_company_id;
             $scope.ticket.disposal_well_id = result.disposal_well_id;
             //console.log($scope.ticket.ticket_num)
             /*if (result != null) {
			 //if ($scope.ticket.ticket_number.id != null) {
			 	//toastr.error("This is a duplicate ticket!");
                 $.unblockUI();
             }
             else {
                 $.unblockUI();
                 toastr.error("This is else statement!");
                 //toastr.error("Not a repeated ticket.");
             }*/
         });
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


swdApp.controller('monitorCtrl', ['$http', '$scope', function ($http, $scope) {
    $scope.vm = {};
    
    $scope.LoadData = function () {
        var api_url = window.cfg.rootUrl + "/api/lng/get.php";
        	$http.get(api_url).success(function (data) {
	        	
            	$scope.vm.q1 = data.q1;
				$scope.vm.q2 = data.q2;
				$scope.vm.q3 = data.q3;
				$scope.vm.q4 = data.q4;
				$scope.vm.q5 = data.q5;
				$scope.vm.q6 = data.q6;
				$scope.vm.q7 = data.q7;
				$scope.vm.q8 = data.q8;
				$scope.vm.q9 = data.q9;
				$scope.vm.q10 = data.q10;
				$scope.vm.pq31 = data.pq31;
				$scope.vm.pq33 = data.pq33;
				$scope.vm.pq34 = data.pq34;
				$scope.vm.pq66 = data.pq66;
				$scope.vm.pq37 = data.pq37;
				$scope.vm.pq39 = data.pq39;
				
				$scope.vm.q1_user_id = data.q1_user_id;
				$scope.vm.q1_notes = data.q1_notes;
				$scope.vm.q1_timestamp = data.q1_timestamp;
					
				$scope.vm.q2_user_id = data.q2_user_id;
				$scope.vm.q2_notes = data.q2_notes;
				$scope.vm.q2_timestamp = data.q2_timestamp;
				
				$scope.vm.q3_user_id = data.q3_user_id;
				$scope.vm.q3_notes = data.q3_notes;
				$scope.vm.q3_timestamp = data.q3_timestamp;
				
				$scope.vm.q4_user_id = data.q4_user_id;
				$scope.vm.q4_notes = data.q4_notes;
				$scope.vm.q4_timestamp = data.q4_timestamp;
				
				$scope.vm.q5_user_id = data.q5_user_id;
				$scope.vm.q5_notes = data.q5_notes;
				$scope.vm.q5_timestamp = data.q5_timestamp;
				
				$scope.vm.q6_user_id = data.q6_user_id;
				$scope.vm.q6_notes = data.q6_notes;
				$scope.vm.q6_timestamp = data.q6_timestamp;
				
				$scope.vm.q7_user_id = data.q7_user_id;
				$scope.vm.q7_notes = data.q7_notes;
				$scope.vm.q7_timestamp = data.q7_timestamp;
				
				$scope.vm.q8_user_id = data.q8_user_id;
				$scope.vm.q8_notes = data.q8_notes;
				$scope.vm.q8_timestamp = data.q8_timestamp;
				
				$scope.vm.q9_user_id = data.q9_user_id;
				$scope.vm.q9_notes = data.q9_notes;
				$scope.vm.q9_timestamp = data.q9_timestamp;
				
				$scope.vm.q10_user_id = data.q10_user_id;
				$scope.vm.q10_notes = data.q10_notes;
				$scope.vm.q10_timestamp = data.q10_timestamp;

				$scope.vm.pq31_user_id = data.pq31_user_id;
				$scope.vm.pq31_notes = data.pq31_notes;
				$scope.vm.pq31_timestamp = data.pq31_timestamp;
				
				$scope.vm.pq33_user_id = data.pq33_user_id;
				$scope.vm.pq33_notes = data.pq33_notes;
				$scope.vm.pq33_timestamp = data.pq33_timestamp;
				
				$scope.vm.pq34_user_id = data.pq34_user_id;
				$scope.vm.pq34_notes = data.pq34_notes;
				$scope.vm.pq34_timestamp = data.pq34_timestamp;
				
				$scope.vm.pq66_user_id = data.pq66_user_id;
				$scope.vm.pq6_notes = data.pq66_notes;
				$scope.vm.pq66_timestamp = data.pq66_timestamp;
				
				$scope.vm.pq37_user_id = data.pq37_user_id;
				$scope.vm.pq37_notes = data.pq37_notes;
				$scope.vm.pq37_timestamp = data.pq37_timestamp;

				$scope.vm.pq39_user_id = data.pq39_user_id;
				$scope.vm.pq39_notes = data.pq39_notes;
				$scope.vm.pq39_timestamp = data.pq39_timestamp;
            	            	            	            	            	            	
            	if ($scope.vm.q1 == 1) {
			    	$scope.vm.q1 = 1;   
			    	//console.log("Q1 is " + $scope.vm.q1);
			    	//console.log("Boolean is " + Boolean($scope.vm.q1))
		        } else {
			        $scope.vm.q1 = 0;
			    }
			        
            	if ($scope.vm.q2 == 1) {
			    	$scope.vm.q2 = 1;
		        } else {
			       	$scope.vm.q2= 0;
			    }
			    
			    if ($scope.vm.q3 == true) {
			    	$scope.vm.q3 = 1;
		        } else {
			       	$scope.vm.q3= 0;
			    }
			    
				if ($scope.vm.q4 == true) {
			    	$scope.vm.q4 = 1;
		        } else {
			       	$scope.vm.q4= 0;
			    }
				
				if ($scope.vm.q5 == true) {
			    	$scope.vm.q5 = 1;
		        } else {
			       	$scope.vm.q5= 0;
			    }
			   	
				if ($scope.vm.q6 == true) {
			    	$scope.vm.q6 = 1;
		        } else {
			       	$scope.vm.q6 = 0;
			    }
			    
			    if ($scope.vm.q7 == true) {
			    	$scope.vm.q7 = 1;
		        } else {
			       	$scope.vm.q7 = 0;
			    }

				if ($scope.vm.q8 == true) {
			    	$scope.vm.q8 = 1;
		        } else {
			       	$scope.vm.q8 = 0;
			    }

				if ($scope.vm.q9 == true) {
			    	$scope.vm.q9 = 1;
		        } else {
			       	$scope.vm.q9 = 0;
			    }
			    
			    if ($scope.vm.q10 == true) {
			    	$scope.vm.q10 = 1;
		        } else {
			       	$scope.vm.q10 = 0;
			    }
			    if ($scope.vm.pq31 == true) {
			    	$scope.vm.pq31 = 1;
		        } else {
			       	$scope.vm.pq31 = 0;
			    }
			    if ($scope.vm.pq33 == true) {
			    	$scope.vm.pq33 = 1;
		        } else {
			       	$scope.vm.pq33 = 0;
			    }
			    if ($scope.vm.pq34 == true) {
			    	$scope.vm.pq34 = 1;
		        } else {
			       	$scope.vm.pq34 = 0;
			    }
			    if ($scope.vm.pq37 == true) {
			    	$scope.vm.pq37 = 1;
		        } else {
			       	$scope.vm.pq37 = 0;
			    }
			     if ($scope.vm.pq39 == true) {
			    	$scope.vm.pq39 = 1;
		        } else {
			       	$scope.vm.pq39 = 0;
			    }
			    if ($scope.vm.pq66 == true) {
			    	$scope.vm.pq66 = 1;
		        } else {
			       	$scope.vm.pq66 = 0;
			    }


        });
    }
    	
    $scope.submitNotes = function(value, queen) {
		/*if(queen == 1 {
			$scope.vm.Which_Queen = queen;
			$scope.vm.outage = value;
			console.log("Which queen" + queen);
		}*/
			/*$scope.vm.value1 = value;
			$scope.vm.value2 = $scope.vm.q2;
			$scope.vm.value3 = $scope.vm.q3;
			$scope.vm.value4 = $scope.vm.q4;
			$scope.vm.value5 = $scope.vm.q5;
			$scope.vm.value6 = $scope.vm.q6;
			$scope.vm.value7 = $scope.vm.q7;
			$scope.vm.value8 = $scope.vm.q8;
			$scope.vm.value9 = $scope.vm.q9;
			$scope.vm.value10 = $scope.vm.q10;
			$scope.vm.notes = $scope.vm.notes;*/
			$scope.vm.QueenIs = queen;
			$scope.vm.Outage = value;
			console.log("Queen is " + queen)
		//}
			var api_url = window.cfg.rootUrl + "/api/lng/save.php";
			//alert(api_url+"?"+$.param($scope.vm));
			$http({
            	method: 'POST',
				url: api_url,
				data: $.param($scope.vm),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        	})
			.success(function (data) {
             	//console.log(data);	
			 	if (data.code == "success") {
                	toastr.success("Status has been changed.");

                	$("#outageNotes_Q1").modal("hide");
					location.reload();
             	}
			 	else {
                	toastr.error(data.message);
             	}
        	});
	}  
    
    $scope.clickQ1 = function(value, queen) {
	    if( value == 1) {
			$("#outageNotes_Q1").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
   
	    /*$scope.vm.value1 = value;
	    $scope.vm.value2 = $scope.vm.q2;
		$scope.vm.value3 = $scope.vm.q3;
		$scope.vm.value4 = $scope.vm.q4;
		$scope.vm.value5 = $scope.vm.q5;
		$scope.vm.value6 = $scope.vm.q6;
		$scope.vm.value7 = $scope.vm.q7;
		$scope.vm.value8 = $scope.vm.q8;
		$scope.vm.value9 = $scope.vm.q9;
		$scope.vm.value10 = $scope.vm.q10;
		
		 var api_url = window.cfg.rootUrl + "/api/lng/save.php";
        //alert(api_url+"?"+$.param($scope.vm));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.vm),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (data) {
             //console.log(data);	
             if (data.code == "success") {
                 toastr.success("Status has been changed.");
                                  //location.reload();
             }
             else {
                 toastr.error(data.message);
             }
        });*/
	}
	$scope.clickQ2 = function(value, queen) {
	    if( value == 1) {
			$("#outageNotes_Q2").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}	
    }
    $scope.clickQ3 = function(value, queen) {
	   if( value == 1) {
			$("#outageNotes_Q3").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
   	}
    $scope.clickQ4 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q4").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}	    
	}
    $scope.clickQ5 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q5").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
    $scope.clickQ6 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q6").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickQ7 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q7").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickQ8 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q8").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}    
	}
	$scope.clickQ9 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q9").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	
	$scope.clickQ10 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_Q10").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ31 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ31").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ33 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ33").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ34 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ34").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ37 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ37").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ39 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ39").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}
	$scope.clickPQ66 = function(value, queen) {
		if( value == 1) {
			$("#outageNotes_PQ66").modal("show");
    	}
    	else {
	    	$scope.submitNotes(value, queen); // 1 REPRESENTS NO OUTAGE
    	}
	}

       $scope.init = function () {
        //$scope.vm.test4 = 1;
        //$scope.vm.allow_duplicate_tickets = 1;
        //$scope.vm.image_with_ticket = true;
        $scope.LoadData();
    }
    $scope.init();
}]);
swdApp.controller('materialsCtrl', ['$http', '$scope', '$cookies',  '$upload', 'AppDataService',  function ($http, $scope,$cookies, $upload, AppDataService) {
    $scope.apifolder = "materials";
    //$scope.adjust = {};
    $scope.ticketList = {};
    $scope.PageIndex = 1;
    $scope.PageSize = window.cfg.PageSize;
    $scope.TotalPages = 0;
    $scope.PageWindowSize = 10;
    $scope.TotalRecords = 0;
    $scope.SearchText = "";
    $scope.fileList = [];
    
    $scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }
    
    $scope.showAddModal = function () {
        $("#addMaterial").modal("show");
    };
    
    $scope.editMaterialModal = function (id) {
	    $scope.material = {};
	    myblockui();
        var api_url = window.cfg.apiUrl + "materials/get.php?id=" + id;
        $http.get(api_url)
            .success(function (result) {
                //$scope.materialsList = result.data;
                $scope.edit_material = result.data[0];
                $scope.edit_fileList = result.data2;
                //console.log("Something is " + JSON.stringify($scope.edit_fileList))
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get materialss error.");
            });
	    
        $("#editMaterial").modal("show");
    };
    
    $scope.showAdjustMaterialModal = function (id, startingQty) {
	    $scope.adjust = { Reason: 'Purchase', AddorSubtract: 'Subtract', id : id, startingQty : startingQty, invoice_date: new Date()}; 
        $("#adjustQuantity").modal("show");
    };

	$scope.saveQtyUsed = function (id, qty_used) {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/subtract_qty.php?id=" + id + "&qtyUsed=" + qty_used;//$scope.material.id;
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
       $http.get(api_url)
	   		.success(function (result) {
		   		if (result.code == "success") {
	   				//console.log("Result is " + JSON.stringify(result.data));
	   				console.log("Testing is " + JSON.stringify(result.testing));
	   				console.log("Testing2 is " + JSON.stringify(result.testing2));
	   				console.log("Testing3 is " + JSON.stringify(result.testing3));
	   				console.log("Testing4 is " + result.testing4);
	   				location.reload();
	   				$.unblockUI();
				} else {
				  $.unblockUI();
                 toastr.error(result.message == undefined ? result.data : result.message);
				}
			}).error(function (result) {
				$.unblockUI();
				toastr.error("Save error.");
			});

    };
   

    $scope.editMaterial = function (uploaddocument) {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update.php";
        myblockui();
       // $scope.edit_material.Id = window.cfg.Id;
        //console.log("Here is " + JSON.stringify($scope.edit_material))
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.edit_material),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 //toastr.success(result.message);
                 if (result.data != undefined)
                 {
                     //$scope.edit_material.id = result.data.id;
                     $scope.material = $scope.edit_material;
                     //console.log("Before uploadfile " + JSON.stringify($scope.material))
                     $scope.UploadFile();
                 }

                 $("#editMaterial").modal("hide");
                 //location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error inside edit');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Edit error.");
         });
    };
    
    $scope.addMaterial = function (uploaddocument) {
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/add.php";
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.material),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 //toastr.success(result.message);
                 if (result.data.id !=undefined)
                 {
                     $scope.material.id = result.data.id;
                     $scope.UploadFile();
                 }

                 $("#addMaterial").modal("hide");
                 //location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Add error.");
         });
    };
    
    $scope.newPurchase = function (uploaddocument) {
	    if (!isEmpty($scope.adjust.invoice_date)) {
            $scope.adjust.invoice_date = moment($scope.adjust.invoice_date).format("MM/DD/YYYY");
        }    
	    
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/purchase.php?id=" + $scope.adjust.id;
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
		console.log(api_url+"&"+$.param($scope.adjust));

        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.adjust),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
	             console.log("We have success!")
                 $.unblockUI();	 
                 //toastr.success(result.message);
                 
                 if (result.data.id !=undefined)
                 {
                     $scope.adjust.id = result.data.id;
                     $scope.UploadFile_MaterialInvoice();
                 }

                 $("#adjustQuantity").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Add error.");
         });
    };

    
    $scope.inventoryAdjustment = function () {
	    console.log("The ID is " + $scope.adjust.id)
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/adjust.php?id=" + $scope.adjust.id;
        myblockui();
		//console.log(api_url+"&"+$.param($scope.recordEdit));
		console.log(api_url+"&"+$.param($scope.adjust));

        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.adjust),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 //toastr.success(result.message);
                 
                 if (result.data.id !=undefined)
                 {
                     $scope.adjust.id = result.data.id;
                     //$scope.UploadFile();
                 }

                 $("#adjustQuantity").modal("hide");
                 location.reload();
             }
             else {
                 $.unblockUI();
                 toastr.error('This is the error');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
	         console.log("There was an error")
             toastr.error("Add error.");
         });
    };
    
    $scope.UploadFile_MaterialInvoice = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_material_invoice.php';
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
                data: $scope.adjust,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Purchase and document saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/material/view/" + $scope.material.id;
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
                   location.reload();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
	         toastr.success("Purchase made successfully.");
	        //toastr.error("Error here.");
           /* if($scope.image_with_ticket == true) {
                   toastr.error("Please add an attachment before continuing.");
                   $.unblockUI();
               }*/
          //  else {
            	//window.location.href = window.cfg.rootUrl + "/materials/view/" + $scope.material.id; 
            	}
        //}
		
    }
    
     $scope.UploadFile = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_material.php';
        //alert(api_url);
        console.log("Info is " + JSON.stringify($scope.material))
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
                data: $scope.material,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Document is saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/material/view/" + $scope.material.id;
                   }
                   else {
                       toastr.error(data.message);
                       console.log("ID is " + $scope.material.id)
                       toastr.error("Error is here!")
                   }
                   $.unblockUI();
                   location.reload();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
	        location.reload();
           /* if($scope.image_with_ticket == true) {
                   toastr.error("Please add an attachment before continuing.");
                   $.unblockUI();
               }*/
          //  else {
            	//window.location.href = window.cfg.rootUrl + "/materials/view/" + $scope.material.id; 
            	}
        //}

    }
    
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
        $scope.PageIndex = 1;
        $scope.LoadData();
        $cookies.put("SearchText", "");
        $cookies.put("SearchDisposalWell", "");
        $cookies.put("SearchOutgoingTicketTypes", "");
    }
    
    $scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "materials/get.php?PageIndex=" + $scope.PageIndex + "&PageSize=" + $scope.PageSize + "&SearchText=" + $scope.SearchText+"&StartDate="+moment($scope.SearchStartDate).format("MM/DD/YYYY")+"&EndDate="+moment($scope.SearchEndDate).format("MM/DD/YYYY");
        
        $http.get(api_url)
            .success(function (result) {
                $scope.materialsList = result.data;
                $scope.TotalPages = result.TotalPages;
                $scope.TotalRecords = result.TotalRecords;
                
                $scope.fileList = result.data2;
                console.log("file list is " + result.data2)

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
                toastr.error("Get materialss error.");
            });
    };
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.material_name,'width=760,height=600,menu=0,scrollbars=1');
	}

    $scope.GoToPage = function () {
        $scope.PageIndex = v;
        $scope.LoadData();
    };

    $scope.Search = function () {        
        $scope.PageIndex = 1;
        $scope.LoadData();
    };


    $scope.deleteMaterial = function (id) {
        console.log(id);
        if (confirm("Are you sure you want to delete this material?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + $scope.apifolder + "/delete.php?id=" + id)
        .success(function (result) {
         	if (result.code == "success") {
		 		location.reload();
                toastr.success("Delete successful!", "Message");
        	}
			else {
                toastr.error(result.message);
                console.log("adf")
            }
        })
        .error(function (result) {
            toastr.error("Failed to delete ticket, please try again.");
        });
    };
    
    $scope.init = function () {
        $scope.SearchText = $cookies.get("SearchText");
       
		if($cookies.get("SearchStartDate")!=undefined)
		{
			$scope.SearchStartDate=moment($cookies.get("SearchStartDate")).format("MM/DD/YYYY");
		}
		if($cookies.get("SearchEndDate")!=undefined)
		{
			$scope.SearchEndDate=moment($cookies.get("SearchEndDate")).format("MM/DD/YYYY");
		}
        $scope.LoadData();
        $scope.Reason = AppDataService.ReasonForAdjust;	
    	$scope.AddorSubtract = AppDataService.AddorSubtract;
    }
    $scope.init();
}]);

swdApp.controller('showMaterialTrackerCtrl', ['$http', '$scope', '$cookies',  '$upload', 'AppDataService',  function ($http, $scope,$cookies, $upload, AppDataService) {
	
	$scope.apifolder = "materials";
	$scope.selectedFiles = [];
    $scope.onFileSelect = function ($files) {
        $scope.selectedFiles = $files;
    }

	$scope.fileList = [];
		
	$scope.LoadData = function () {
        myblockui();
        var api_url = window.cfg.apiUrl + "materials/get_material_tracker_list.php?id=" +  window.cfg.Id;
        
        $http.get(api_url)
            .success(function (result) {
                $scope.materialsList = result.data;
                $scope.fileList = result.data2;
                $scope.materialName = result.name;
                $scope.TotalRecords = result.TotalRecords;
                
                //console.log("file list is  " + JSON.stringify(result.data2))
               
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get materialss error.");
            });
            
    };
    
    $scope.editPurchaseModal = function (id) {
	    
	    $scope.editPurchase = {};
	    myblockui();
        var api_url = window.cfg.apiUrl + "materials/get_purchase.php?id=" + id;
        $http.get(api_url)
            .success(function (result) {
                //$scope.materialsList = result.data;
                $scope.editPurchase = result.data[0];
                $scope.edit_fileList = result.data2;
                console.log("IN HERE " + JSON.stringify(result.data[0]))
                console.log("The id is " + id)
                //console.log("Something is " + JSON.stringify($scope.edit_fileList))
                $.unblockUI();
            }).error(function (result) {
                toastr.error("Get materialss error.");
            });

	    $scope.editPurchas = { Reason: 'Purchase', unitCost: $scope.editPurchase.unitCost }; 
        $("#editPurchase").modal("show");
    };
    
    $scope.editPurchase2 = function (id, uploaddocument) {
	    console.log("The invoice number is " + $scope.editPurchase.invoice_date)
        var api_url = window.cfg.apiUrl + $scope.apifolder + "/update_purchase.php?id=" + id;
        
		if (!isEmpty($scope.editPurchase.invoice_date)) {
        	$scope.editPurchase.invoice_date = moment($scope.editPurchase.invoice_date).format("MM/DD/YYYY");
        }   
        myblockui();
       // $scope.edit_material.Id = window.cfg.Id;
        //console.log("Here is " + JSON.stringify($scope.edit_material))
		//console.log(api_url+"&"+$.param($scope.recordEdit));
        $http({
            method: 'POST',
            url: api_url,
            data: $.param($scope.editPurchase),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function (result) {
             if (result.code == "success") {
                 $.unblockUI();	 
                 //toastr.success(result.message);
                 if (result.data != undefined)
                 {
                     //$scope.edit_material.id = result.data.id;
                     $scope.material = $scope.editPurchase;
                     //console.log("Before uploadfile " + JSON.stringify($scope.material))
                     $scope.UploadFile_MaterialInvoice();
                 }

                 $("#editMaterial").modal("hide");
                 //location.reload();
             }
             else {
                 $.unblockUI();
                 console.log("Testing is " + result.testing)
                 toastr.error('This is the error inside edit');//result.message == undefined ? result.data : result.message);
             }
         }).error(function (data) {
             toastr.error("Edit error.");
         });
    };

	    $scope.UploadFile_MaterialInvoice = function () {
        var api_url = window.cfg.apiUrl + 'file/upload_material_invoice.php';
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
                data: $scope.material,
                file: file,
                fileFormDataName: 'documentfile'
            })
               .success(function (data) {
                   if (data.code == "success") {
                       toastr.success("Purchase and document saved successfully.");
                       //window.location.href = window.cfg.rootUrl + "/material/view/" + $scope.material.id;
                   }
                   else {
                       toastr.error(data.message);
                   }
                   $.unblockUI();
                   location.reload();
               })
               .error(function (data) {
                   toastr.error("Error to save the document.");
                   $.unblockUI();
               });
        }
        else
        {
	         toastr.success("Purchase made successfully.");
	        //toastr.error("Error here.");
           /* if($scope.image_with_ticket == true) {
                   toastr.error("Please add an attachment before continuing.");
                   $.unblockUI();
               }*/
          //  else {
            	//window.location.href = window.cfg.rootUrl + "/materials/view/" + $scope.material.id; 
            	}
        //}
		
    }
    
    $scope.deleteDocument = function (fileid) {
        console.log(fileid);
        if (confirm("Are you sure to delete this document?") == false) {
            return;
        }

        $http.get(window.cfg.apiUrl + "file/delete_MaterialInvoice.php?id=" + fileid).success(function (result) {
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


    $scope.openStart = function ($event) {        
        $scope.openedStart = true;
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
    
    $scope.OpenWindow=function(filepath)
	{
		window.open(window.cfg.rootUrl+'/data/'+filepath,'Invoice #'+$scope.materialName,'width=760,height=600,menu=0,scrollbars=1');
	}

	$scope.init = function () {
        $scope.LoadData();
        $scope.Reason = AppDataService.ReasonForAdjust;	
    }
    $scope.init();

}]);
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminSiteCtrl" style="margin-top:20px;margin-bottom:20px;">
    <!-- Main Container Starts -->
    <div id="main-container" class="container">		
        <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{recordList.length}})</b>
		<div class="row" style='margin-top:15px;'>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary btn-sm" ng-click="loadRecordAddModal();">
				    <span class="glyphicon glyphicon-plus"></span> Add {{entityName}}
			    </button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-sm" style="float:right;margin-left:10px;" ng-click="Search();">
                    <span class="glyphicon glyphicon-search"></span> Search
                </button>                
                <div style="float:right;">
                    <input type="text" style="width:300px;" ng-model="SearchText"/><br />
                    <span>* Search by domain and name</span>
                </div>
            </div>
		</div>
		<table class="table table-striped">			
			<thead>				
				<tr>					
					<th>Domain</th>					
                    <th>Name</th>					
					<th style='width:100px;'>Options</th>					
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList'>
					<td>{{record.domain}}</td>					
                    <td>{{record.name}}</td>					
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit {{entityName}}" ng-click="loadRecordEdit(record.id);"></a>                      
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete {{entityName}}" ng-click="deleteRecord(record.id);"></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>


    <!--Add Popup Window-->
    <div class="modal fade modal-wide" id="modalAddRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Domain*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.domain" class='form-control' required />
                            </div>  
                            <div class="form-group col-md-12">
                                <label class="control-label">Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.name" class='form-control' required />
                            </div>                                             
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="uploadAddRecord()" ng-disabled="!frmAddRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Popup Window End-->


    <!--Edit Popup Window-->
    <div class="modal fade modal-wide" id="modalEditRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit {{entityName}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Domain:</label><br />
                                <input type="text" value="" ng-model="recordEdit.domain" class='form-control' required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.name" class='form-control' required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="updateRecordEdit()" ng-disabled="!frmEditRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Edit Popup Window End-->
</div>


<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminSiteCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminDisposalWellCtrl">
    <!-- Main Container Starts -->
     <div style="width:99%;margin-left:10px;">
        <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{recordList.length}})</b>
		<div class="row alert" style='background-color:#ddd;'>
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" ng-click="loadRecordAddModal();">
				    <span class="glyphicon glyphicon-plus"></span> Add {{entityName}}
			    </button>
            </div>
            <div class="col-md-6">
				<div class="input-group">  					
					<div style="float:right;">
						<input type="text" style="min-width:400px;" class="form-control" placeholder="Search by name" ng-model="SearchText" ng-enter="Search()"/><br />						
					</div>     
					<span class="input-group-btn">		
						<button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
					</span>
				</div>
            </div>
		</div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">					
					<th>Disposal Well Name</th>					
					<th style='width:100px;'>Options</th>					
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList'>
					<td>{{record.common_name}}</td>					
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
                                <label class="control-label">Company Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.common_name" class='form-control' required />
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
                                <label class="control-label">Company Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.common_name" class='form-control' required>
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


<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminDisposalWellCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

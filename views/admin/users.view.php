<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminUserCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-12">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{recordList.length}})</b>
			</div>
		</div>
		
		<div class="row alert" style="background-color:#ddd;">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" ng-click="loadRecordAddModal();">
				    <span class="glyphicon glyphicon-plus"></span> Add {{entityName}}
			    </button>
            </div>
            <div class="col-md-6">
				<div class="input-group">
					<div style="float:right;">
						<input type="text" placeholder="search by name and email" style="min-width:400px;" class="form-control" ng-enter="Search()" ng-model="SearchText"/><br />                    
					</div>
					<span class="input-group-btn">	
						<button class="btn btn-primary"  ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
					</span>
				</div>          
            </div>
		</div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">					
					<th>Username</th>
                    <th>First name</th>
                    <th>Last name</th>
					<th>Email</th>
					<th>Is Admin?</th>
					<th>Is Manager?</th>
					<th style='width:100px;'>Options</th>					
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList'>
					<td>{{record.username}}</td>
                    <td>{{record.first_name}}</td>
                    <td>{{record.last_name}}</td>
					<td>{{record.email}}</td>
					<td>{{record.is_superuser==1?"Yes":"No"}}</td>
					<td>{{record.is_staff==1?"Yes":"No"}}</td>
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit {{entityName}}" href="#" ng-click="loadRecordEdit(record.id);"></a>                   
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete {{entityName}}" ng-click="deleteRecord(record.id);"></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
    <!-- Main Container Starts End-->


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
                                <label class="control-label">User Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.username" class='form-control' required />
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">First Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.first_name" class='form-control' required />
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Last Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.last_name" class='form-control' required />
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Email*:</label><br />
                                <input type="email" id="email" value="" ng-model="recordAdd.email" class='form-control' required />
                                <span class="red" ng-show="frmAddRecord.email.$error.email">*invalid</span>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Password:</label><br />
                                <input type="password" name="password" ng-model="recordAdd.password" value="" class='form-control'>
                            </div>
							<div class="form-group col-md-12">
                                <label class="control-label">User Role:</label><br />
                                <input type="checkbox" ng-model="recordAdd.is_superuser" ng-true-value="1" ng-false-value="0">Admin&nbsp;&nbsp;
								<input type="checkbox" ng-model="recordAdd.is_staff" ng-true-value="1" ng-false-value="0">Manager
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
                                <label class="control-label">User Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.username" class='form-control' required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">First Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.first_name" class='form-control' required>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="control-label">Last Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.last_name" class='form-control' required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Email:</label><br />
                                <input type="email" value="" ng-model="recordEdit.email" class='form-control' required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="control-label">Password:</label><br /><input type="checkbox" ng-model="ChangePassword">Change Password?
                                <input type="password" name="password" ng-show="ChangePassword" ng-model="recordEdit.password" value="" class='form-control'>
                            </div>
							<div class="form-group col-md-12">
                                <label class="control-label">User Role:</label><br />
                                <input type="checkbox" ng-model="recordEdit.is_superuser" ng-true-value="1" ng-false-value="0" ng-checked="recordEdit.is_superuser==1">Admin&nbsp;&nbsp;
								<input type="checkbox" ng-model="recordEdit.is_staff" ng-true-value="1" ng-false-value="0" ng-checked="recordEdit.is_staff==1">Manager
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
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminUserCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

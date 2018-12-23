<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminRigsCtrl">
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
            <!--<div class="col-md-6">               
				<div class="input-group">
					<div style="float:right;">
						<input type="text" style="min-width:400px;" class="form-control" placeholder="Search by name" ng-enter="Search()" ng-model="SearchText"/><br />                    
					</div>  
					<span class="input-group-btn">	
						 <button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span> Search
						</button>
					</span>
				</div>
            </div>-->
		</div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">					
					<th>Rig Name</th>					
					<th>Company Man Name</th>
					<th>Company Man Number</th>
					<th>Producer's Site</th>
					<th style='width:100px;'>Options</th>					
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList'>
					<td>{{record.rig_name}}</td>		
					<td>{{record.company_man_name}}</td>			
					<td>{{record.company_man_number}}</td>			
					<td>{{record.source_well_name}}</td>			
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit {{entityName}}" ng-click="loadRecordEdit(record.id);"></a>                      
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete {{entityName}}" ng-click="deleteRecord(record.id);"></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	
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
                            <div class="form-group col-md-4">
                                <label class="control-label">Rig Name:</label><br />
                                <input type="text" value=""  ng-model="recordEdit.rig_name" class='form-control' required>
                            </div>
							<div class="form-group col-md-4">
                                <label class="control-label">Company Man Name:</label><br />
                                <input type="text" value=""  ng-model="recordEdit.company_man_name" class='form-control' required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">Company Man Number:</label><br />
                                <input type="text" value=""  ng-model="recordEdit.company_man_number" class='form-control' required>
                            </div>
                        </div>
                        <div class="row">
                        	<div class="form-group col-md-12">
								<label class="control-label">Producer's site:</label><br />
								<input type="text" ng-model="recordEdit.name" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="recordEdit.source_well_id=$item.id" typeahead-editable="false" class="form-control" required />
								<span>*Search by well name, producer or file number</span>
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
                            <div class="form-group col-md-4">
                                <label class="control-label">Rig Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.name" class='form-control' required />
                            </div>                  
                            <div class="form-group col-md-4">
                                <label class="control-label">Company Man Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.company_man_name" class='form-control' required />
                            </div>   
                            <div class="form-group col-md-4">
                                <label class="control-label">Company Man Number*:</label><br />
                                <input type="number" value="" ng-model="recordAdd.company_man_number" class='form-control' required />
                            </div>                  
                        </div>
						<div class="row">
                        	<div class="form-group col-md-12">
								<label class="control-label">Producer's site:</label><br />
								<input type="text" ng-model="recordAdd.source_well_name" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="recordAdd.source_well_id=$item.id" typeahead-editable="false" class="form-control" required />
								<span>*Search by well name, producer or file number</span>
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


</div>


<script src="<?=$rootScope["RootUrl"]?>/includes/app/rigsCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

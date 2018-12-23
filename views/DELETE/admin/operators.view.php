<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminOperatorCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-5">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{TotalRecords}})</b>
			</div>
			<div class="col-md-7">
				<nav ng-show="TotalPages > 1" style="margin-top:-20px;text-align:right;">
                    <ul class="pagination">
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(1)" title="Go to page 1">&laquo;&laquo;</a></li>
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(PageIndex-1)" title="Go to preious page">&laquo;</a></li>
                        <li ng-class="{active: pn == PageIndex}" ng-repeat="pn in PageRange"><a href="javascript:void(0);" ng-click="GoToPage(pn)">{{pn}} </a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(PageIndex+1)" title="Go to next page">&raquo;</a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(TotalPages)" title="Go to last page">&raquo;&raquo;</a></li>
                    </ul>
                </nav>
			</div>
		</div>	
		
        <div class="row alert" style="background-color:#ddd;">
            <div class="col-md-6">
                <a type="button" class="btn btn-primary"  ng-click="loadRecordAddModal();">
				<span class="glyphicon glyphicon-plus"></span> Add New Producer
			</a>
            </div>
            <div class="col-md-6">
				<div class="input-group">
					 <div style="float:right;">
						<input type="text" class="form-control" style="min-width:400px;" ng-enter="Search()" ng-model="SearchText" placeholder="search by name"/><br />                    
					</div>
					<span class="input-group-btn">	
						<button class="btn btn-primary" ng-click="Search();">
							<span class="glyphicon glyphicon-search"></span>Search               
						</button>
                     </span>
				</div>
            </div>
        </div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">					
					<th>Id</th>
                    <th>Name</th>	
                    <th>Parent Operator</th>
                    <th>#Wells</th>
                    <th>options</th>			
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList track by record.id'>
					<td>{{record.id}}</td>
                    <td>{{record.name}}</td>
                    <td>{{record.parent_name}}</td>
                    <td>{{record.number_wells}}</td>
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit Operatpr" ng-click="loadRecordEdit(record.id);"></a>                      
						<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete Operator" ng-click="deleteRecord(record.id);"></a>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-12">
				<nav ng-show="TotalPages > 1">
                    <ul class="pagination">
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(1)" title="Go to page 1">&laquo;&laquo;</a></li>
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(PageIndex-1)" title="Go to preious page">&laquo;</a></li>
                        <li ng-class="{active: pn == PageIndex}" ng-repeat="pn in PageRange"><a href="javascript:void(0);" ng-click="GoToPage(pn)">{{pn}} </a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(PageIndex+1)" title="Go to next page">&raquo;</a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(TotalPages)" title="Go to last page">&raquo;&raquo;</a></li>
                    </ul>
                </nav>
			</div>
		</div>
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
                                <label class="control-label">Name*:</label><br />
                                <input type="text" value="" ng-model="recordAdd.name" class='form-control' required />
                            </div>                  
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Parent Operator*:</label><br />
                                <select ng-model="recordAdd.parentid" class='form-control'>
                                    <option ng-repeat="item in parent_operator_list" value="{{item.id}}">{{item.name}}</option>
                                    </select>
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
                                <label class="control-label">Name:</label><br />
                                <input type="text" value="" ng-model="recordEdit.name" class='form-control' required>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="control-label">Parent Operator*:</label><br />
                                <select ng-model="recordEdit.parentid" class='form-control'>
                                    <option ng-repeat="item in parent_operators" value="{{item.id}}">{{item.name}}</option>
                                    </select>
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
<script type="text/javascript">
    window.cfg.parent_operator_list = <?=$viewScope["parent_operator_list"]?>; 
    
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminOperatorCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

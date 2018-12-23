<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminAccountCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-5">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName2}} Management (Total: {{recordList.length}})</b>
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
				<span class="glyphicon glyphicon-plus"></span> Add New Customer
			</a>
            </div>
            <div class="col-md-6">
				<div class="input-group">
					 <div style="float:right;">
						<input type="text" class="form-control" style="min-width:400px;" ng-enter="Search()" ng-model="SearchText" placeholder="Search by customer"/><br />                    
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
                    <th>Customer</th>	
                    <th>Operator</th>
					<th>Account Code</th>
                    <th>Product Type</th>
                    <th>options</th>			
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList track by record.id'>
					<td>{{record.id}}</td>
                    <td>{{record.customer}}</td>
                    <td>{{record.operator}}</td>
					<td>{{record.account_code}}</td>
                    <td>{{record.product_type}}</td>
					<td>
						<a class="glyphicon glyphicon-edit" style="cursor: pointer;" title="Edit Operator" ng-click="loadRecordEdit(record.id);"></a>                      
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


    <!--Edit Popup Window-->
    <div class="modal fade modal-wide" id="modalEditRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit {{entityName2}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Customer:</label><br />
                                <input type="text" value="" ng-model="recordEdit.customer" class='form-control' required>
                            </div>
							 <div class="form-group col-md-6">
                                <label class="control-label">Operator:</label><br />
                               <select class='form-control' ng-model='recordEdit.operator_id' ng-options="company.current_operator_id as company.name for company in uniqueCustomerList">
									  <option value="">Select a customer (not required)</option>
								</select>
                            </div>
                        </div>
                       <div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Account Code:</label><br />
                                <select class='form-control' ng-model='recordEdit.account_code_id' ng-options="accountcode.id as accountcode.account_code for accountcode in accountCodes">	
									<option value="">Select an account code</option>
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


    <!--Add Popup Window-->
    <div class="modal fade modal-wide" id="modalAddRecord" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName2}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Customer:</label><br />
                                <input type="text" value="" ng-model="recordAdd.customer" class='form-control' required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Operator:</label><br />
								<select class='form-control' ng-model='recordAdd.operator_id' ng-options="company.current_operator_id as company.name for company in uniqueCustomerList">
									  <option value="">Select a customer (not required)</option>
									<!--<option value='0'>Select a Customer (not required)</option>
									<option ng-repeat="customer in uniqueCustomerList" value="{{customer.id}}">{{customer.name}}</option>-->
								</select>
                            </div>                         
                        </div>
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Account Code:</label><br />
                                <select class='form-control' ng-model='recordAdd.account_code_id' ng-options="accountcode.id as accountcode.account_code for accountcode in accountCodes">	
									<option value="">Select an account code</option>
                    			</select>      
                    			<!--<label class="control-label">Product Type:</label><br />
								<select class='form-control' ng-model='recordAdd.product_type_id' ng-options="producttype.id as producttype.type for producttype in waterTypeList">	
									<option value="">Select a product type</option>
                    			</select>-->      
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
<script type="text/javascript">
    window.cfg.parent_operator_list = <?=$viewScope["parent_accounting_list"]?>; 
    
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminAccountCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
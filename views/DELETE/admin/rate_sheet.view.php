<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminRateSheetCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-5">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{recordList.length}})</b>
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
            <div class="col-md-2">
                <a type="button" class="btn btn-primary"  ng-click="loadRecordAddModal();">
				<span class="glyphicon glyphicon-plus"></span> Add New Rate
				</a>
            </div>
			<div class="col-md-2">
                <a type="button" class="btn btn-primary"  ng-click="loadProductTypeAddModal();">
				<span class="glyphicon glyphicon-plus"></span> Add New Product Type
				</a>
            </div>
            <div class="col-md-6">
				<div class="input-group">
					 <div style="float:right;">
						<input type="text" class="form-control" style="min-width:400px;" ng-enter="Search()" ng-model="SearchText" placeholder="Search by account code or product type"/><br />                    
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
					<th>Account Code</th>
					<th style="text-align:center;">Price</th>
                    <th style="text-align:center;">Product Type</th>	
                    <th style="text-align:center;">Delivery Method</th>
                    <th style="text-align:center;">Water Source Type</th>
                    <th style="text-align:center;">Trucking Company</th>
                    <th style="text-align:center;">Disposal Well</th>
                    <th style="text-align:center;">options</th>			
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList track by record.id'>
					<td>{{record.account_code}}</td>
                    <td style="text-align:center;">${{record.price}}</td>
					<td style="text-align:center;">{{record.product_type}}</td>
                    <td style="text-align:center;">{{record.delivery_method}}</td>
                    <td style="text-align:center;">{{record.water_source_type}}</td>
                    <td style="text-align:center;">{{record.trucking_company}}</td>
                    <td style="text-align:center;">{{record.disposal_well}}</td>
					<td style="text-align:center;">
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
    <div class="modal fade modal-wide" id="modalAddRecord_backup" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName}}</h4>
                    </div>
                    <div class="modal-body">
                       <!-- <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Customer:</label><br />
                                <input type="text" value="" ng-model="recordAdd.customer_name" class='form-control' required>
                            </div>
							 <div class="form-group col-md-6">
                                <label class="control-label">Operator:</label><br />
                                <select class='form-control' ng-model='recordAdd.unique_customer_id' ng-options="company.id as company.name for company in uniqueCustomerList">
									  <option value="">Select an operator</option>
                                </select>
                            </div>               
                        </div>-->
                        <div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Product Type:</label><br />
                                <select class='form-control' ng-model='recordAdd.product_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">
									  <option value="">Select a product type</option>
								</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Account Code:</label><br />
								<input type="text" value="" ng-model="recordAdd.account_code" class='form-control' required>
                            </div>                  
                        </div>
                        <div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Truck or Pipeline:</label><br />
                               <select class='form-control' ng-model='recordAdd.delivery_method' ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">
                    			</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Pit or Well:</label><br />
								<select class='form-control' ng-model='recordAdd.water_source_type' ng-options="watersourcetype.value as watersourcetype.label for watersourcetype in WaterSourceTypeList">
								</select>
                            </div>                  
                        </div>
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Trucking Company:</label><br />
								<select class='form-control' ng-model='recordAdd.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
									<option value="">Select a trucking company</option>
								</select>
	                        </div>
	                        <div class="form-group col-md-6">
                                <label class="control-label">Disposal well:</label><br />
								<select class='form-control' ng-model='recordAdd.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
									<option value="">Select a disposal well</option>
								</select>
	                        </div>
                        </div>
						<div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Price:</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.price" class='form-control'>
								</div>
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
                       <!-- <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Customer:</label><br />
                                <input type="text" value="" ng-model="recordAdd.customer_name" class='form-control' required>
                            </div>
							 <div class="form-group col-md-6">
                                <label class="control-label">Operator:</label><br />
                                <select class='form-control' ng-model='recordAdd.unique_customer_id' ng-options="company.id as company.name for company in uniqueCustomerList">
									  <option value="">Select an operator</option>
                                </select>
                            </div>               
                        </div>-->
                        <div class="row">
	                         <div class="form-group col-md-6">
								 <label class="control-label">Producer:</label><br />
								<select class='form-control' ng-model='recordAdd.producer_id' ng-options="customer.id as customer.name for customer in uniqueCustomerList">
									<option value="">Select a producer</option>
								</select>
                            </div>
                            <div class="form-group col-md-6">
                                 <label class="control-label">Rig:</label><br />
								<select class='form-control' ng-model='recordAdd.rig_id' ng-options="rig.id as rig.rig_name for rig in rigsList">
									<option value="">Select a rig</option>
								</select>
                            </div>                  
                        </div>
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Trucking Company:</label><br />
								<select class='form-control' ng-model='recordAdd.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
									<option value="">Select a trucking company</option>
								</select>
	                        </div>
	                        <div class="form-group col-md-6">
                                <label class="control-label">Bill to:</label><br />
								<select class='form-control' ng-model='recordAdd.bill_to_id' ng-options="bill_to.value as bill_to.label for bill_to in trd_bill_to">
								</select>
	                        </div>
                        </div>
						 <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">5%</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.percent_5" class='form-control'>
								</div>
                            </div>
	                        <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">6% to 15%</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.percent_6_to_15" class='form-control'>
								</div>
                            </div>
						 </div>
                         <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">16% to 35%</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.percent_16_to_35" class='form-control'>
								</div>
                            </div>
	                        <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">36% to 50%</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.percent_36_to_50" class='form-control'>
								</div>
                            </div>

                        </div>

                        <div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">Hydro Vac:</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.hydrovac" class='form-control'>
								</div>
                            </div> 
                            <div class="form-group col-md-6">
                                <label class="control-label" style="color: #007FFF">Truck Wash:</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="number" value="" ng-model="recordAdd.truck_wash" class='form-control'>
								</div>
                            </div>               
                        </div>
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">T & D Rate?:</label><br />
								<select class='form-control' ng-model='recordAdd.bill_to_id' ng-options="bill_to.value as bill_to.label for bill_to in trd_bill_to">
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
                            <div class="form-group col-md-6">
                                <label class="control-label">Product Type:</label><br />
                                <select class='form-control' ng-model='recordEdit.product_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">
									  <option value="">Select a product type</option>
								</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Account Code:</label><br />
								<input type="text" value="" ng-model="recordEdit.account_code" class='form-control' required>
                            </div>                  
                        </div>
                        <div class="row">
	                          <div class="form-group col-md-6">
                                <label class="control-label">Delivery Method:</label><br />
                               <select class='form-control' ng-model='recordEdit.delivery_method' ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">
                    			</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Water Source Type:</label><br />
								<select class='form-control' ng-model='recordEdit.water_source_type' ng-options="watersourcetype.value as watersourcetype.label for watersourcetype in WaterSourceTypeList">
								</select>
                            </div>                            
						</div>
						<div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Trucking Company:</label><br />
								<select class='form-control' ng-model='recordEdit.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
								</select>
	                        </div>
	                        <div class="form-group col-md-6">
                                <label class="control-label">Disposal well:</label><br />
								<select class='form-control' ng-model='recordEdit.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
								</select>
	                        </div>
                        </div>

						<div class="row">
	                         <div class="form-group col-md-6">
                                <label class="control-label">Price:</label><br />
                                <div class="input-group">
	                                <span class="input-group-btn">
										<button type="button" class="btn btn-default">$</button>
									</span>
									<input type="text" value="" ng-model="recordEdit.price" class='form-control'>
								</div>
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
    
    <!--Add Popup Window [Product Type]-->
    <div class="modal fade modal-wide" id="modalAddProductType" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmAddProductType">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add a New {{entityName2}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Product Type:</label><br />
								<input type="text" value="" ng-model="productTypeAdd.type" placeholder="Enter in a prodct type" uib-typeahead="producttype as producttype.name for producttype in producttypes | filter:{name:$viewValue}| limitTo:4" typeahead-on-select="productTypeAdd.type_id=$item.id" typeahead-editable="true" class='form-control' ng-blur="product_type_warning();">
                            </div>                  
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="uploadAddProductType()" ng-disabled="!frmAddProductType.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Popup Window End [Product Type]-->
    
</div>
<script type="text/javascript">
    window.cfg.parent_operator_list = <?=$viewScope["rate_sheet_list"]?>; 
    
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminRateSheetCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
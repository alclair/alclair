<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminRatesCtrl" ng-show="entityName!=undefined">
    <!-- Main Container Starts -->
    <div style="width:99%;padding-left:10px;">
        
        <div class='row'>
			<div class="col-md-5">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Management (Total: {{TotalRecords}})</b>
			</div>
			<div class="col-md-7" style="text-align:right;">
				<nav ng-show="TotalPages > 1" style="margin-top:-20px;">
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
            <div class="col-md-8">
                <a type="button" class="btn btn-primary" ng-click="loadRecordAddModal();">
					<i class="fa fa-plus"></i> Add new single rate
				</a>
				<a type="button" class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/admin/batch_rate">
					<i class="fa fa-edit"></i> Set Batch Rates
				</a>
				<a type="button" class="btn btn-primary" href="<?=$rootScope["RootUrl"]?>/admin/default_rate">
					<i class="fa fa-edit"></i> Manage Default Rates
				</a>
            </div>
            <div class="col-md-4">
				<div class="input-group">                
					<div style="float: right;">
						<input type="text" ng-model="SearchText" placeholder="search by well, operator" class="form-control" ng-enter="Search()" /><br />
					</div>
					<span class="input-group-btn">
						<button class="btn btn-primary" ng-click="Search();">
						<span class="glyphicon glyphicon-search"></span>Search               
				   
						</button>
					</span>
				</div>
            </div>
        </div>
        <table class="table table-striped" ng-show="recordList.length>0" style="border:1px solid #ccc;">
            <thead>
                <tr bgcolor="#cccccc">
                    <th>Id</th>
                    <th>Source Well</th>
                    <th>Disposal Well</th>
                    <th>Operator</th>
                    <th>Bill To</th>
                    <th>Bill To Name</th>
                    <th>Water Type</th>
                    <th>Rate/Barrel</th>
					<th>Use Default Rate?</th>
					<th></tH>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat='record in recordList'>
                    <td>{{record.id}}</td>
                    <td>{{record.source_well_filenumber}}-{{record.source_well_name}}</td>
                    <td>{{record.disposal_well_name}}</td>
                    <td>{{record.operator_name}}</td>
                    <td>{{record.billto_option}}</td>
                    <td>{{record.billto_option=="operator"?record.billto_operator_name:record.trucking_company_name}}</td>
                    <td>{{record.water_type_name}}</td>
                    <td>${{record.barrel_rate}}</td>
					<td>{{record.use_default==1?"Yes":"No"}}</td>
                    <td width="80">
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
                            <div class="col-md-6">
                                <label>Operator*: </label><br />
                                
                                <select ng-model="recordAdd.operator_id" class='form-control' ng-change="OperatorChanged()">
                                    <option ng-repeat="item in operator_list" value="{{item.id}}">{{item.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" ng-show="recordAdd.operator_id!=undefined">
                            <div class="form-group col-md-6">
                                <label class="control-label">Source Well*:</label><br />
                                <select ng-model="recordAdd.source_well_id" class='form-control' required>
                                    <option ng-repeat="item in source_well_list" value="{{item.id}}">{{item.source_well_file_number}}-{{item.source_well_name}} </option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Disposal Well*:</label><br />
                                <select ng-model="recordAdd.disposal_well_id" class='form-control' required>
                                    <option ng-repeat="item in disposal_well_list" value="{{item.id}}">{{item.common_name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" ng-show="recordAdd.operator_id!=undefined">
                            <div class="form-group col-md-6">
                                <label class="control-label">Bill To*:</label><br />
                                <select ng-model="recordAdd.billto_option" class='form-control' required>
                                    <option value="operator" selected>Operator</option>
                                    <option value="trucking company">Trucking Company</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6" ng-show="recordAdd.billto_option=='operator'">
                                <label class="control-label">Bill to Operator:</label><br />
                                <select ng-model="recordAdd.billto_operator_id" class='form-control' ng-required="recordAdd.billto_option=='operator'">
                                    <option ng-repeat="item in operator_list" value="{{item.id}}">{{item.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6" ng-show="recordAdd.billto_option=='trucking company'" >
                                <label class="control-label">Bill to Trucking Company:</label><br />
                                <select ng-model="recordAdd.trucking_company_id" class='form-control' ng-required="recordAdd.billto_option=='trucking company'">
                                    <option ng-repeat="item in trucking_company_list" value="{{item.id}}">{{item.name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" ng-show="recordAdd.operator_id!=undefined">
                            <div class="col-md-6">
                                <label>Water Type:</label><br />
                                <select ng-model="recordAdd.water_type_id" class="form-control" required>
                                    <option ng-repeat="item in water_type_list" value="{{item.id}}">{{item.type}}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Rate:</label><br />
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="text" class="form-control" ng-model="recordAdd.barrel_rate" ng-required="recordAdd.use_default==0" placeholder="rate" ng-readonly="recordAdd.use_default==1" />
                                    <div class="input-group-addon">Per Barrel</div>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-md-6">
								<label>Use Default Rate: </label>
								<label><input type="radio" ng-model="recordAdd.use_default" value="1">Yes</label>
								<label><input type="radio" ng-model="recordAdd.use_default" value="0" checked>No</label>
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
                                <label class="control-label">Source Well*:</label><br />
                                {{recordEdit.source_well_name}}
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Disposal Well*:</label><br />
                                <select ng-model="recordEdit.disposal_well_id" class='form-control' required ng-options="item.id as item.common_name for item in disposal_well_list">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Bill To*:</label><br />
                                <select ng-model="recordEdit.billto_option" class='form-control' required>
                                    <option value="operator" selected>Operator</option>
                                    <option value="trucking company">Trucking Company</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6" ng-show="recordEdit.billto_option=='operator'">
                                <label class="control-label">Bill to Operator:</label><br />
                                <select ng-model="recordEdit.billto_operator_id" class='form-control' ng-options="item.id as item.name for item in operator_list">
                                </select>
                            </div>
                            <div class="form-group col-md-6" ng-show="recordEdit.billto_option=='trucking company'">
                                <label class="control-label">Bill to Trucking Company:</label><br />
                                <select ng-model="recordEdit.trucking_company_id" class='form-control'  ng-options="item.id as item.name for item in trucking_company_list">
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Water Type:</label><br />
                                <select ng-model="recordEdit.water_type_id" class="form-control" required ng-options="item.id as item.type for item in water_type_list">
                                    
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Rate/Barrel:</label><br />
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input type="text" class="form-control" ng-model="recordEdit.barrel_rate" ng-required="recordEdit.use_default==0" placeholder="rate" ng-readonly="recordEdit.use_default==1" />
                                    <div class="input-group-addon">Per Barrel</div>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-md-6">
								<label>Use Default Rate: </label>
								<label><input type="radio" ng-model="recordEdit.use_default" value="1">Yes</label>
								<label><input type="radio" ng-model="recordEdit.use_default" value="0" checked>No</label>
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

   window.cfg.water_type_list = <?=$viewScope["water_type_list"]?>; 
    
    window.cfg.disposal_well_list = <?=$viewScope["disposal_well_list"]?>;  
    
    window.cfg.operator_list = <?=$viewScope["operator_list"]?>;
    window.cfg.trucking_company_list=<?=$viewScope["trucking_company_list"]?>;
    //alert("hello");
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminRatesCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>


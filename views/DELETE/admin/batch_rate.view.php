<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminBatchRateCtrl" style="margin-top: 20px; margin-bottom: 20px;">
    <div id="main-container" class="container">
        <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}}</b>
        <form name="frmBatchRate">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Disposal Well*:</label><br />
                    <select ng-model="batchEdit.disposal_well_id" class='form-control' required>
                        <option ng-repeat="item in disposal_well_list" value="{{item.id}}">{{item.common_name}}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Water Type*:</label><br />
                    <select ng-model="batchEdit.water_type_id" class="form-control" required ng-options="item.id as item.type for item in water_type_list">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">
                                Operator*:
                    <input type="checkbox" ng-model="operatorsCheckedAll" ng-click="SetOperatorsAllChecked()" />
                                Check All</label>
                        </div>
                        <div class="col-md-6" >
                            <div class="input-group">
                                <input type="text" ng-model="operator_search" placeholder="search operators" class="form-control" ng-enter="GetOperators()" />
                                <div class="input-group-addon"><a href="javascript:void(0);" ng-click="GetOperators()"><i class="fa fa-search"></a></i></div>
                            </div>

                        </div>
                    </div>

                    <div style="height: 120px; border: 1px solid #ccc; padding: 10px; overflow-y: auto;">
                        <div ng-repeat="item in operator_list" >
                            <label>
                                <input type="checkbox" ng-model="item.checked" ng-checked="operatorsCheckedAll" ng-click="GetSourceWells()" ng-disabled="operatorsCheckedAll" />
                                {{item.name}}</label>
                        </div>
						<div id="operator_loader" style="display:none;"><i>loading operators...</i></div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">
                                Source Well*:
                    <input type="checkbox" ng-model="wellsCheckedAll" ng-click="SetWellsAllChecked()"  />
                                Check All</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" ng-model="well_search" placeholder="search wells" class="form-control" ng-enter="GetSourceWells()" />
                                <div class="input-group-addon"><a href="javascript:void(0);" ng-click="GetSourceWells()"><i class="fa fa-search"></a></i></div>
                            </div>

                        </div>
                    </div>

                    <div style="height: 120px; max-height: 120px; border: 1px solid #ccc; padding: 10px; overflow-y: auto;">
                        <div ng-repeat="item in source_well_list |limitTo:wellPageSize:wellStart track by item.id">
                            <label>
                                <input type="checkbox" ng-model="item.checked" ng-checked="wellsCheckedAll" ng-disabled="wellsCheckedAll" />
                                {{item.source_well_file_number}}-{{item.source_well_name}}</label>
                        </div>
						
                    </div>
					<div class="row" ng-show="source_well_list!=undefined&&source_well_list.length>0">
						<div class="col-md-2">
							<a href="javascript:void(0);" ng-click="wellPrevious()" ng-show="wellPageIndex>0">&laquo;Previous</a>
						</div>
						<div class="col-md-8">
						{{wellStart+1}}- {{wellEnd+1}}, total of {{source_well_list.length}} records, page {{wellPageIndex+1}}/{{wellTotalPages}}.
						</div>
						<div class="col-md=2">
							<a href="javascript:void(0);" ng-click="wellNext()" ng-show="wellPageIndex+1<wellTotalPages">Next&raquo;</a>
						</div>
					</div>	
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 ">
                    <label class="control-label">Bill To*:</label><br />
                    <select ng-model="batchEdit.billto_option" class='form-control' required>
                        <option value="operator" selected>Operator</option>
                        <option value="trucking company">Trucking Company</option>
                    </select>
                    <br />
                    <br />
                    <label>Rate*:</label><br />
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input type="text" class="form-control" ng-model="batchEdit.barrel_rate" required placeholder="rate" />
                        <div class="input-group-addon">Per Barrel</div>
                    </div>
                </div>

                <div class="form-group col-md-6" ng-show="batchEdit.billto_option=='trucking company'">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">
                                Bill to Trucking Company:
                    <input type="checkbox" ng-model="truckingcompanysCheckedAll" ng-click="SetTruckingCompanysAllChecked()" />
                                Check All</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" ng-model="trucking_company_search" placeholder="search trucking company" class="form-control" ng-enter="GetTruckingCompanys()"  />
                                <div class="input-group-addon"><a href="javascript:void(0);" ng-click="GetTruckingCompanys()"><i class="fa fa-search"></i></a></div>
                            </div>

                        </div>
                    </div>

                    <div style="height: 120px; max-height: 120px; border: 1px solid #ccc; padding: 10px; overflow-y: auto;">
                        <div ng-repeat="item in trucking_company_list">
                            <label>
                                <input type="checkbox" ng-model="item.checked" ng-checked="truckingcompanysCheckedAll" ng-disabled="truckingcompanysCheckedAll" />
                                {{item.name}}</label>
                        </div>

                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-md-6">
					<label>Use Default Rate: </label>
					<label><input type="radio" ng-model="batchEdit.use_default" value="1">Yes</label>
					<label><input type="radio" ng-model="batchEdit.use_default" value="0" checked>No</label>
				</div>
			</div>
            <div class="row">
                <button type="button" class="btn btn-primary" ng-click="batchUpdate()" ng-disabled="!frmBatchRate.$valid"><i class="fa fa-plane"></i>Submit</button>
            </div>
        </form>

    </div>
</div>
<script type="text/javascript">

    window.cfg.water_type_list = <?=$viewScope["water_type_list"]?>; 
    
    window.cfg.disposal_well_list = <?=$viewScope["disposal_well_list"]?>; 
   
    //alert("hello");
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/adminBatchRateCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

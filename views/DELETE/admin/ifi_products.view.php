<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<div ng-controller="adminProductCtrl">
    <!-- Main Container Starts -->
    <div style="width:99%;margin-left:10px;">
		<div class="row">
			<div class="col-md-5">
				<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/admin/index">Admin Site</a> - {{entityName}} Manager(Total: {{recordList.length}})</b>
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
				<span class="glyphicon glyphicon-plus"></span> Add Product
				</a>
            </div>
            <div class="form-group col-md-3">                  
                <select class='form-control' ng-model='category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList"  ng-blur="LoadData();">
					<option value="">Select a category</option>
				</select>
            </div>
           <div class="form-group col-md-2">
				<select class='form-control' ng-model='product_id' ng-options="productName.id as productName.name for productName in productsInCategoryList"  ng-blur="LoadData();">
					<option value="">Select a product</option>
				</select>
			</div>



            <!--<div class="col-md-6">
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
            </div>-->
        </div>
		<table class="table table-striped" style="border:1px solid #ddd;">			
			<thead>				
				<tr bgcolor="#cccccc">					
					<th style="text-align:center;">Category</th>
                    <th style="text-align:center;">Product</th>	
                    <th style="text-align:center;">Price</th>
                    <th style="text-align:center;">SKU</th>
                    <th style="text-align:center;">EAN</th>
                    <th style="text-align:center;">SN Prefix</th>
                    <th style="text-align:center;">Total Quantity</th>
                    <th style="text-align:center;">Sealed</th>
                    <th style="text-align:center;">Amazon</th>
                    <th style="text-align:center;">Demo</th>
                    <th style="text-align:center;">Faulty</th>
                    <th style="text-align:center;">Shipped</th>
                    <th style="text-align:center;">Options</th>			
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat='record in recordList track by record.id'>
                    <td style="text-align:center;">{{record.category}}</td>
					<td style="text-align:center;">{{record.name}}</td>
                    <td style="text-align:center;">{{record.price  | currency:"$"}}</td>
                    <td style="text-align:center;">{{record.sku}}</td>
                    <td style="text-align:center;">{{record.ean}}</td>
                    <td style="text-align:center;">{{record.sn_prefix}}</td>
                    <td style="text-align:center;">{{record.total_quantity}}</td>
                    <td style="text-align:center;">{{record.sealed_quantity}}</td>
                    <td style="text-align:center;">{{record.amazon_quantity}}</td>
                    <td style="text-align:center;">{{record.demo_quantity}}</td>
                    <td style="text-align:center;">{{record.faulty_quantity}}</td>
                    <td style="text-align:center;">{{record.shipped_quantity}}</td>
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
	                         <div class="form-group col-md-6">
                                <label class="control-label">Product Category:</label><br />
                                <select class='form-control' ng-model='recordAdd.category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList" required>
									  <option value="">Select a category</option>
								</select>
                            </div>
                            <div ng-if="recordAdd.category_id != 0" class="form-group col-md-6">
                                <label class="control-label">Product Name:</label><br />
								<input type="text" value="" ng-model="recordAdd.name" class='form-control' required>
                            </div>                  
                        </div>
                        <div class="row">
                            <div ng-if="recordAdd.name" class="form-group col-md-6">
                                <label class="control-label">Product SKU #:</label><br />
								<input type="text" value="" ng-model="recordAdd.sku" class='form-control' required>
                            </div> 
                            <div ng-if="recordAdd.sku" class="form-group col-md-6">
                                <label class="control-label">SN Prefix:</label><br />
								<input type="text" value="" ng-model="recordAdd.sn_prefix" class='form-control' required>
                            </div> 
                        </div>     
                        <div class="row">             
	                        <div ng-if="recordAdd.name" class="form-group col-md-6">
                                <label class="control-label">Product EAN #:</label><br />
								<input type="text" value="" ng-model="recordAdd.ean" class='form-control' required>
                            </div> 
                            <div ng-if="recordAdd.sn_prefix" class="form-group col-md-6">
								<label class="control-label">Price:</label><br />
								<div class="left-inner-addon">
									<span>$</span>
									<input type="number" ng-model="recordAdd.price" placeholder="" value="" class="form-control"  >
								</div>
                            </div>
						</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="addProduct()" ng-disabled="!frmAddRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
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
                                <label class="control-label">Product Category:</label><br />
                                <select class='form-control' ng-model='recordEdit.category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList">
									  <option value="">Select a  category</option>
								</select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Product Name:</label><br />
								<input type="text" value="" ng-model="recordEdit.name" class='form-control' required>
                            </div>                  
                        </div>
                        <div class="row">
	                        <div class="form-group col-md-6">
                                <label class="control-label">Product SKU #:</label><br />
								<input type="text" value="" ng-model="recordEdit.sku" class='form-control' required>
                            </div>     
                            <div class="form-group col-md-6">
                                <label class="control-label">SN Prefix:</label><br />
								<input type="text" value="" ng-model="recordEdit.sn_prefix" class='form-control' required>
                            </div>     
                        </div>
                        <div class="row">            
	                        <div class="form-group col-md-6">
                                <label class="control-label">Product EAN #:</label><br />
								<input type="text" value="" ng-model="recordEdit.ean" class='form-control' required>
                            </div>                 
	                         <div class="form-group col-md-6">
                                <label class="control-label">Price:</label><br />
								<div class="left-inner-addon">
									<span>$</span>
									<input  type="text" ng-model="recordEdit.price"  ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" placeholder="" value="" class="form-control"  /required> <!--ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" step="0.01" -->
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
    
    
</div>
<script type="text/javascript">
    window.cfg.categoryTypeList = <?=$viewScope["categoryTypeList"]?>
</script>
<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
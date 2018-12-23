<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="ifi_LogID_SNs">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-3">
                <a href="<?=$rootScope['RootURL']?>/ifi/orders"><b style="font-size:20px"></a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"></b>
            </div>
            <!--<div class="col-md-2" style="margin-top:-10px;padding-bottom:10px;text-align:left;	" >
                <a type="button" class="btn btn-danger" href="<?=$rootScope['RootUrl']?>/alclair/qc_form">
					<span class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;QC FORM
				</a>
            </div>-->
            <div class="col-md-6" style="text-align:right;">
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
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
         <!--   
		<div class="row alert" style='background-color:#ddd;'>
            <div class="form-group col-md-2">
				<select class='form-control' ng-model='category_id' ng-options="categorytype.id as categorytype.name for categorytype in categoryTypeList"  ng-blur="update_products(user.category_id);">
					<option value="">Select a category</option>
				</select>
        	</div>
			<div class="form-group col-md-2">
				<select class='form-control' ng-model='product_id' ng-options="productName.id as productName.name for productName in productsInCategoryList"  ng-blur="update_products2();">
					<option value="">Select a product</option>
				</select>
			</div>
			-->
			<!--<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>				
			</div>
			<div class="col-sm-2">
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchEndDate" is-open="openedEnd" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openEnd($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
			</div>-->
			<!--
            <div class="col-sm-3">
                    <div class="input-group">
						<input type="text"  ng-model="SearchText" placeholder="Serial Number"  uib-typeahead="customer as customer.designed_for for customer in customers| filter:{designed_for:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.designed_for" typeahead-editable="true" class="form-control" >		
						
						<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
							<span class="input-group-btn">
								<button class="btn btn-primary js-new pull-right" ng-click="Search();">
									<span class="glyphicon glyphicon-search"></span> 
								</button>
							</span>
					</div>
            </div>
			
		</div>
        -->
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Serial Number</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Status</th>	
					<th style="text-align:center;">Log ID</th>
                    <!--<th style="text-align:center;">Options</th>-->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='sn in snList'>
					<td  style="text-align:center;" data-title="Date">{{sn.serial_number}}</td>
					<td  style="text-align:center;" data-title="Product">{{sn.category_name}} {{sn.product_name}}</td>
					<td  style="text-align:center;" data-title="Type">{{sn.status}}</td>
					<td  style="text-align:center;" data-title="Log ID">{{sn.log_id}}</td>					
                    <!--<td data-title="Options">
	                    <div style="text-align:center;" >  
							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>
		                    &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.order_id);">Traveler</button>
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(qc_form.id);"></a>
						</div>
                    </td>-->
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>
		 
        <div class="row" ng-show="TotalPages > 1">
            <div class="col-lg-12">
                 <nav>
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
                        <h4 class="modal-title">Edit {{entityName}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Designed For:</label><br />
                                <input type="text" value="" ng-model="recordEdit.designed_for" class='form-control' required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Order ID:</label><br />
								<input type="text" value="" ng-model="recordEdit.order_id" class='form-control' required>
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


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Orders_Done_By_Date">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> {{Printed}} Printed</b><span style="color: #FF0000">  {{traveler.order_id}}</span></a>
            </div>
            <div class="form-group col-sm-3">
				<input type="text" id="barcode_orders" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
			</div>
			<div class="col-md-2">
			</div>
            <div class="col-md-3">
                <input type="checkbox" ng-model="rush_or_not" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">RUSH ORDERS ONLY</b><br />
            </div>
            
            <!--<div class="col-md-2" style="margin-top:-10px;padding-bottom:10px;text-align:left;	" >
                <a type="button" class="btn btn-danger" href="<?=$rootScope['RootUrl']?>/alclair/qc_form">
					<span class="glyphicon glyphicon-pencil"></span> &nbsp;&nbsp;QC FORM
				</a>
            </div>-->
            <!--
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
            -->
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="open_add_order()">
					<i class="fa fa-plus"></i> &nbsp; ADD ORDER
				</button>
				<!--<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>-->
            </div>
            <div class="form-group col-sm-1">                  
                <!--<select class='form-control' ng-model='build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
					<option value="">Select a build type</option>
				</select>-->
            </div>
			<div class="form-group col-sm-2">
                <select class='form-control' ng-model='printed_or_not' ng-options="printed.value as printed.label for printed in PRINTED_OR_NOT">
                </select>
            </div>	
			<div class="form-group col-sm-2">
                <select class='form-control' ng-model='order_status_id' ng-options="orderStatus.order_in_manufacturing as orderStatus.status_of_order for orderStatus in orderStatusTableList">
	                <option value="">-- All States --</option>
                </select>
            </div>	

			<div class="col-sm-2">
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
			</div>
            <div class="col-sm-2">
                    <div class="input-group">
						<input type="text"  ng-model="SearchText" placeholder="Name or order ID"  uib-typeahead="customer as customer.designed_for for customer in customers| filter:{designed_for:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.designed_for" typeahead-editable="true" class="form-control" >		
						
						<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
							<span class="input-group-btn">
								<button class="btn btn-primary js-new pull-right" ng-click="Search();">
									<span class="glyphicon glyphicon-search"></span> 
								</button>
							</span>
					</div>
            </div>
			
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Designed For</th>
					<th style="text-align:center;">Order Status</th>
					<th style="text-align:center;">Order ID</th>
					<th style="text-align:center;">Completed Date</th>	
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;"># Done</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td  ng-if="order.designed_for==' ' && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="order.designed_for==' ' && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
					<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>
					
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>
					<td  style="text-align:center;" data-title="Completed Date">{{order.date_done}}</td>
					<td  style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  style="text-align:center;" data-title="# Done">{{order.num_done}}</td>					
										
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    
		                    <!-- ng-disabled="order.highrise != 1" -->
		                    &nbsp;&nbsp;<button  type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.id);">Traveler</button>					
						<?php if($_SESSION["UserName"] == 'Scott' || $_SESSION["UserName"] == 'admin') { ?>
							<!-- ng-disabled="order.status_of_order == 'Done'" -->
							&nbsp;&nbsp;<button  type="button" class="btn btn-primary btn-xs" ng-click="LoadSelectDateModal(order.id);">DONE</button>		
						<?php } ?>

							<!--<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>-->		
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(order.id);"></a>
						</div>

                    </td>
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
    <div class="modal fade modal-wide" id="SelectDateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form name="frmEditRecord">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Select a date for when the order was shipped</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="row">    
	                    	<div class="form-group col-md-2">
	                    	</div>
							<div class="form-group col-md-6">
								<div class="text-left">
									<label class="control-label" style="font-size: large;color: #007FFF">CHANGE DONE DATE</label><br />
		 						</div>
		 						<div class="input-group">
		 							<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="done_date" is-open="openedDone" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
		 							<span class="input-group-btn">
		 								<button type="button" class="btn btn-default" ng-click="openDone($event)"><i class="fa fa-calendar"></i></button>
		 							</span>
                				</div>			
                			</div>
                    	</div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" ng-click="DONE()" ng-disabled="!frmEditRecord.$valid"><i class="fa fa-plane"></i>Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

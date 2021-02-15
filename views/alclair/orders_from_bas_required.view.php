<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Orders">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;
            </div>       
        </div>
        <br/>
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
					<th style="text-align:center;">Order Date</th>	
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Impressions Received</th>
					<th style="text-align:center;">Printed</th>
					<th style="text-align:center;">Check Highrise</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					
					<td  ng-if="(order.designed_for==null || order.designed_for=='') && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="(order.designed_for==null || order.designed_for=='') && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=null && order.designed_for!=''" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
<!--
					<td   style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.shipping_name}} </a></td>
					-->
<!--
					<td  ng-if="order.designed_for==' ' && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="order.designed_for==' ' && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
-->
					
					<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>
					
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>
					<td  style="text-align:center;" data-title="Order Date">{{order.date}}</td>
					<td  style="text-align:center;" data-title="Model">{{order.model}}</td>
					
					<td  ng-if="!order.received_date" style="text-align:center;" data-title="Impressions Received">NOT RECEIVED</td>
					<td  ng-if="order.received_date" style="text-align:center;" data-title="Impressions Received">{{order.received_date}}</td>
					
					<td  ng-if="order.printed" style="text-align:center;" data-title="Model">Printed</td>
					<td  ng-if="!order.printed" style="text-align:center;" data-title="Model">Not Printed</td>
					
					<td  ng-if="order.printed" style="text-align:center;" data-title="Check Highrise">
						<input type="checkbox" ng-model="order.highrise" ng-true-value="1" ng-false-value="0" ng-checked="1" ng-disabled="true">
					</td>
					<td  ng-if="!order.printed" style="text-align:center;" data-title="Check Highrise">
						<input type="checkbox" ng-model="order.highrise"  ng-true-value="1" ng-false-value="0">
					</td>
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    
		                    &nbsp;&nbsp;<button ng-disabled="order.highrise != 1" type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.id);">Traveler</button>					
						<?php if($_SESSION["UserName"] == 'Scott' || $_SESSION["UserName"] == 'admin' || $_SESSION["UserName"] == 'Amanda' || $_SESSION["UserName"] == 'Will') { ?>
							&nbsp;&nbsp;<button ng-disabled="order.status_of_order == 'Done'" type="button" class="btn btn-primary btn-xs" ng-click="LoadSelectDateModal(order.id);">DONE</button>		
						<?php } ?>

							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>		
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
									<label class="control-label" style="font-size: large;color: #007FFF">DONE DATE</label><br />
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/Orders_From_BAs_Required.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
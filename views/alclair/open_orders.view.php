<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Open_Orders">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Open Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;
                <!--<b style="display:inline; font-size:20px; color: #007FFF"> {{Printed}} Printed</b><span style="color: #FF0000">  {{traveler.order_id}}</span></a>-->
            </div>
            <!--
            <div class="form-group col-sm-3">
				<input type="text" id="barcode_orders" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
			</div>
			-->
			<div class="col-md-1">
			</div>
			<!--
            <div class="col-md-2">
                <input type="checkbox" ng-model="rush_or_not" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">RUSH ORDERS ONLY</b>
            </div>
            <div class="col-md-2">
                <input type="checkbox" ng-model="use_impression_date" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">USE IMPRESSION DATE</b><br />
            </div>
            -->
        </div>
        
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<div class="form-group col-sm-1">       
				<!--
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="open_add_order()">
					<i class="fa fa-plus"></i> &nbsp; ADD ORDER
				</button>
				-->
            </div>
            <div class="form-group col-sm-1">                  
            </div>
				<div class="form-group col-sm-3">
                <select class='form-control' ng-model='order_status_id' ng-options="orderStatus.order_in_manufacturing as orderStatus.status_of_order for orderStatus in orderStatusTableList">
	                <option value="">-- All States --</option>
                </select>
            </div>	

<!--
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
-->
            <div class="col-sm-4">
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
					<th style="text-align:center;">Order Date</th>	
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Impressions Received</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					
					<td  ng-if="(order.designed_for==null || order.designed_for=='') && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="(order.designed_for==null || order.designed_for=='') && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=null && order.designed_for!=''" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
					<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>
					<td  style="text-align:center;" data-title="Order Date">{{order.date}}</td>
					<td  style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  ng-if="!order.received_date" style="text-align:center;" data-title="Impressions Received">NOT RECEIVED</td>
					<td  ng-if="order.received_date" style="text-align:center;" data-title="Impressions Received">{{order.received_date}}</td>					
				</tr>
			</tbody>
		</table>
    <?php
	    // FORM FOR TRD COMPANY
	    } 
	?>
	</div>	
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/OpenOrders.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>	
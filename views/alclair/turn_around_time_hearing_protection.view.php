<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="TAT_hearing_protection">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">HP Over The Average</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"></b><span style="color: #FF0000"> </span></a>
            </div>
        </div>
		<!--<div class="row alert" style='background-color:#ddd;'>-->
		<div class="row alert" style='background-color:#ddd;'>

            <div class="form-group col-sm-1">                  
                <!--<select class='form-control' ng-model='build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
					<option value="">Select a build type</option>
				</select>-->
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
			<div class="col-sm-1">
				<div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="Search()">
                    	<i class="fa fa-calculator"></i> &nbsp; RUN                            
					</button>
		    	</div>
			</div>
			<div class="col-sm-2">
				<div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-success" ng-click="Excel()">
                    	<i class="fa fa-table"></i> &nbsp; Excel                            
					</button>
		    	</div>
			</div>
			<div class="col-sm-2">
				<div class="text-center">
					<b style="font-size:28px;color: #FF0000">(Active: {{TotalRecordsActive}})</b>
				</div>
			</div>
			
            <!--<div class="col-sm-2">
                    <div class="input-group">
						
						<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
							<span class="input-group-btn">
								<button class="btn btn-primary js-new pull-right" ng-click="Search();">
									<span class="glyphicon glyphicon-search"></span> 
								</button>
							</span>
					</div>
            </div>-->
			
		</div>
        
         <div class="row">
	         <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Average: <span  style="font-size: 24px;color: #000000"> {{avg}}</span></label>
					<!--<input type="text" ng-model="qrcode.order_id" placeholder="Barcode"  class="form-control">-->	
            </div>
            <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Median: <span  style="font-size: 24px;color: #000000"> {{median}}</span></label>
					<!--<input type="text" ng-model="qrcode.order_id" placeholder="Barcode"  class="form-control">-->	
            </div>
            <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Mode: <span  style="font-size: 24px;color: #000000"> {{mode}}</span></label>
            </div>
            <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Minimum: <span  style="font-size: 24px;color: #000000"> {{min}}</span></label>	
            </div>
            <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Maximum: <span  style="font-size: 24px;color: #000000"> {{max}}</span></label>
			</div>
			 <div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Total #: <span  style="font-size: 24px;color: #000000"> {{total_orders}}</span></label>
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
					<th style="text-align:center;">TAT</th>
					<th style="text-align:center;">Impressions Received</th>
					<th style="text-align:center;">Completed on</th>
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Order ID</th>
					
					<!--<th style="text-align:center;">Order Status</th>-->
				
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td  ng-if="order.designed_for==' ' && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="order.designed_for==' ' && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
					<td  style="text-align:center;" data-title="TAT">{{order.difference}}</td>
					<td  ng-if="!order.received_date" style="text-align:center;" data-title="Impressions Received">NOT RECEIVED</td>
					<td  ng-if="order.received_date" style="text-align:center;" data-title="Impressions Received">{{order.received_date}}</td>
					<td  style="text-align:center;" data-title="Completed on">{{order.done_date}}</td>
					<td  style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>

					
					
					<!--<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>-->
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    &nbsp;&nbsp;

							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>

	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(order.id);"></a>
						</div>
                    </td>
				</tr>
			</tbody>
		</table>
		
		<br />
		
		<table ng-show="there_is_a_null == 1">		
			<thead>
				<tr>
					<th style="text-align:center;">Designed For</th>
					<th style="text-align:center;">TAT</th>
					<th style="text-align:center;">Impressions Received</th>
					<th style="text-align:center;">Completed on</th>
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Order ID</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order_zero in OrdersList_zero' ng-if="order_zero.difference == 0">
					<td  ng-if="order_zero.designed_for==' ' && order_zero.billing_name==order_zero.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order_zero.id}}">{{order_zero.billing_name}}</a></td>
					<td  ng-if="order_zero.designed_for==' ' && order_zero.billing_name!=order_zero.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order_zero.id}}">Billing -{{order_zero.billing_name}} / Shipping-{{order_zero.shipping_name}}</a></td>
					<td  ng-if="order_zero.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order_zero.id}}">{{order_zero.designed_for}} </a></td>
					<td  style="text-align:center;" data-title="TAT">{{order_zero.difference}}</td>
					<td  ng-if="!order_zero.received_date" style="text-align:center;" data-title="Impressions Received">NOT RECEIVED</td>
					<td  ng-if="order_zero.received_date" style="text-align:center;" data-title="Impressions Received">{{order_zero.received_date}}</td>
					<td  style="text-align:center;" data-title="TAT">{{order_zero.done_date}}</td>
					<td  style="text-align:center;" data-title="Model">{{order_zero.model}}</td>
					<td  style="text-align:center;" data-title="Order ID">{{order_zero.order_id}}</td>
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    &nbsp;&nbsp;<button ng-disabled="order_zero.highrise != 1" type="button" class="btn btn-primary btn-xs" ng-click="PDF(order_zero.id);">Traveler</button>

							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order_zero.id}}"></a>

	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(order_zero.id);"></a>
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
	
	
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_TAT.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Pipeline_V2">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/orders"><b style="font-size:20px">Orders</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> </b><span style="color: #FF0000">  {{traveler.order_id}}</span></a>
            </div>
            
			<div class="col-md-2">
			</div>
            <div class="col-md-2">
                <input type="checkbox" ng-model="rush_or_not" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">RUSH ORDERS ONLY</b><br />
            </div>
            <div class="col-md-3">
                <input type="checkbox" ng-model="remove_hearing_protection" ng-true-value="1" ng-false-value="0" style="margin-top:16px"> &nbsp; <b style="font-size:14px;color:gray">REMOVE HEARING PROTECTION</b><br />
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

            <div class="form-group col-sm-3">
                <select class='form-control' ng-model='today_or_next_week' ng-options="day.value as day.label for day in TODAY_OR_NEXT_WEEK" ng-blur="Search();">
                </select>
            </div>	
           <div class="form-group col-sm-2">             
				<select class='form-control' ng-model='order_status_id' ng-options="orderStatus.order_in_manufacturing as orderStatus.status_of_order for orderStatus in OrderStatusList">
					<option value="">Select a status</option>
				</select>
            </div>            
             <div class="form-group col-sm-2">             
				<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Select a monitor</option>
				</select>
            </div>  
            <div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="Search()">
					<i class="fa fa-search"></i> &nbsp; SEARCH
				</button>
				<!--<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>-->
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
					<th style="text-align:center;">Date of Last Scan</th>	
					
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Days Past Due</th>
					<th style="text-align:center;">Imp Rx'd Date</th>
					<th style="text-align:center;">Due Date</th>
					<th style="text-align:center;">2 Wks After</th>
					<th style="text-align:center;">Order ID</th>
					<th style="text-align:center;">Order Date</th>	
					
					<th style="text-align:center;">Rush</th>
					<th style="text-align:center;"># of QC Forms</th>
					<th style="text-align:center;">Pass/Fail</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td  ng-if="order.designed_for==' ' && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="order.designed_for==' ' && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
					<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>
					
					<td  style="text-align:center;" data-title="Date of Last Scane">{{order.date_of_last_scan}}</td>
					
					<td  ng-if="order.model" style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  ng-if="!order.model && order.hearing_protection" style="text-align:center;" data-title="Model">AHP OR SHP</td>
					<td  ng-if="!order.model && order.musicians_plugs" style="text-align:center;" data-title="Model">MP</td>
					
					<!--
					<td  ng-if="order.model && !order.hearing_protection" style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  ng-if="order.hearing_protection && !order.model" style="text-align:center;" data-title="Model">{{order.model}}</td>
					<td  ng-if="order.hearing_protection && order.model" style="text-align:center;" data-title="Model">{{order.model}} & Hearing Protection</td>
					<td  ng-if="order.musicians_plugs && !order.model" style="text-align:center;" data-title="Model">MP</td>
					-->
					
					<td  style="text-align:center;" data-title="Days Past Due">{{order.days_past_due}}</td>
					<td  style="text-align:center;" data-title="Imp Rx'd Date">{{order.received_date}}</td>
					<td  style="text-align:center;" data-title="Due Date">{{order.estimated_ship_date}}</td>
					
					<td  style="text-align:center;" data-title="2 Wks After">{{order.plus_2wks}}</td>
					
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>
					<td  style="text-align:center;" data-title="Order Date">{{order.date}}</td>
					

					
					<td  class="bg-success" ng-if="order.rush_process == 'Yes'" style="text-align:center;font-weight: bold; color:white" data-title="Rush">RUSH</td>
					<td ng-if="order.rush_process != 'Yes'" style="text-align:center;" data-title="Rush">No</td>
					
					<td  style="text-align:center;" data-title="# of QC Forms">{{order.num_of_qc_forms}}</td>
					<td  style="text-align:center;" data-title="Pass/Fail">{{order.pass_or_fail}}</td>
										
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    &nbsp;&nbsp;<button ng-disabled="order.highrise != 1" type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.id);">Traveler</button>

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

<!--		 
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
-->

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
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_Pipeline_V2.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
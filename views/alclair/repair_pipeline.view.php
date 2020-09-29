<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Repair_Pipeline">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/repairs"><b style="font-size:20px">Repairs</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> </b><span style="color: #FF0000">  {{traveler.repair_id}}</span></a>
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
				<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Select a monitor</option>
				</select>
            </div>            <div class="form-group col-sm-1">       
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
					<th style="text-align:center;">Repair Forr</th>
					<th style="text-align:center;">Repair Status</th>
					<th style="text-align:center;">Date of Last Scan</th>
					
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Days Past Due</th>
					<th style="text-align:center;">Due Date</th>
					<th style="text-align:center;">Repair ID</th>
					<th style="text-align:center;">Repair Date</th>	
					
					<th style="text-align:center;">Rush</th>
					<th style="text-align:center;"># of QC Forms</th>
					<th style="text-align:center;">Pass/Fail</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='repair in RepairsList'>
					<td style="text-align:center;" data-title="Repair For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{repair.id}}">{{repair.customer_name}} </a></td>
					
					<td  style="text-align:center;" data-title="Order Status">{{repair.status_of_repair}}</td>
					
					<td  style="text-align:center;" data-title="Date of Last Scane">{{repair.date_of_last_scan}}</td>
					
					<td  style="text-align:center;" data-title="Model">{{repair.model}}</td>
										
					<td  style="text-align:center;" data-title="Days Past Due">{{repair.days_past_due}}</td>
					<td  style="text-align:center;" data-title="Due Date">{{repair.estimated_ship_date}}</td>
					
					<td  style="text-align:center;" data-title="Repair ID">{{repair.rma_number}}</td>
					<td  style="text-align:center;" data-title="Repair Date">{{repair.date}}</td>
					
					<td  class="bg-success" ng-if="repair.rush_process == 'Yes'" style="text-align:center;font-weight: bold; color:white" data-title="Rush">RUSH</td>
					<td ng-if="repair.rush_process != 'Yes'" style="text-align:center;" data-title="Rush">No</td>
					
					<td  style="text-align:center;" data-title="# of QC Forms">{{repair.num_of_qc_forms}}</td>
					<td  style="text-align:center;" data-title="Pass/Fail">{{repair.pass_or_fail}}</td>
										
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    &nbsp;&nbsp;<button ng-disabled="order.highrise != 1" type="button" class="btn btn-primary btn-xs" ng-click="PDF(repair.id);">Traveler</button>

							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Repair" href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{repair.id}}"></a>

	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(repair.id);"></a>
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
								<input type="text" value="" ng-model="recordEdit.repair_id" class='form-control' required>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_TAT.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
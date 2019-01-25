<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Batch_Entry">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <!--<a href="<?=$rootScope['RootURL']?>/alclair_batch/batch_list"><b style="font-size:20px">{{Batch_Name}}</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;-->
            </div>
            
			<div class="col-md-2">
			</div>            
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<div class="form-group col-sm-1" style="margin-top:10px">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="showAddItem()" >
					<i class="fa fa-plus"></i> &nbsp; ADD ITEM
				</button>
				<!--<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>-->
            </div>
             <div class="form-group col-sm-1" style="margin-top:10px; margin-left:-60px">       
             </div>
            <div class="form-group col-sm-1" style="margin-top:10px">       
				<button style="font-weight: 600" type="button" class="btn btn-success" ng-click="Back2Batches()" >
					<i class="fa fa-arrow-left"></i> &nbsp; BACK
				</button>
				<!--<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>-->
            </div>
			<div class="form-group col-sm-8" style="text-align:center">
				<!--<a href="<?=$rootScope['RootURL']?>/alclair_batch/batch_list"><b style="font-size:40px" ng-click="open_modal()">{{Batch_Name}}</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;-->
				<a data-toggle="tooltip" class="tooltipLink" data-original-title="Tooltip text goes here" style="cursor: pointer">
					<b style="font-size:40px;" ng-click="open_modal()"><span style="color:blue">{{Batch_Name}} </span></b>
				</a>
	<!--			
				
				<a data-toggle="tooltip" class="tooltipLink" data-original-title="Tooltip text goes here">
			   			<span class="glyphicon glyphicon-info-sign"></span>
					</a>
-->
				<b style="font-size:40px;">(Total: {{TotalRecords}})</b>&nbsp;&nbsp;
                <!--<select class='form-control' ng-model='batch_status_id' ng-options="printed.value as printed.label for printed in PRINTED_OR_NOT">
                </select>-->
            </div>	
			<!-- DATE STUFF GOES HERE 
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
			<!-- SEARCH BY TEXT OR NUMBER FUNCTION GOES HERE
            <div class="col-sm-2">
                    <div class="input-group">
						<input type="text"  ng-model="SearchText" placeholder="Name or batch ID"  uib-typeahead="customer as customer.designed_for for customer in customers| filter:{designed_for:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.designed_for" typeahead-editable="true" class="form-control" >		
						
						<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
							<span class="input-group-btn">
								<button class="btn btn-primary js-new pull-right" ng-click="Search();">
									<span class="glyphicon glyphicon-search"></span> 
								</button>
							</span>
					</div>
            </div>
			-->
		</div>
        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
		<table>		
			<thead>
				<tr>
					<th style="text-align:center;">Designed For</th>
					<!--<th style="text-align:center;">Ordered By</th>-->
					<th style="text-align:center;">Impression Date</th>
					<th style="text-align:center;">Order #</th>	
					<th style="text-align:center;">Paid</th>
					<th style="text-align:center;">Customer Status</th>
					<th style="text-align:center;">In-House Next Steps</th>
					<th style="text-align:center;">Notes</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat="(key, item) in ItemsList" ><!--ng-repeat='item in ItemsList'-->
					
					<td  style="text-align:center;" data-title="Designed For">{{item.designed_for}}</td>					
					<!--<td  style="text-align:center;" data-title="Ordered By">{{item.ordered_by}}</td>-->
					<td  style="text-align:center;" data-title="Impression Date">{{item.impression_date}}</td>
					
					<td  style="text-align:center;" data-title="Order #">{{item.order_number}}</td>
					
					<td  ng-if="item.paid == 1" style="text-align:center;" data-title="Paid">Yes</td>
					<td  ng-if="item.paid != 1" style="text-align:center;" data-title="Paid">No</td>
					
					<td  ng-if="item.customer_status == 0" style="text-align:center;" data-title="Customer Status">New</td>
					<td  ng-if="item.customer_status == 1" style="text-align:center;" data-title="Customer Status">Existing</td>
					
					<td  style="text-align:center;" data-title="In-House Next Steps">{{item.steps}}</td>
					
					<td  style="text-align:center;" data-title="Notes">{{item.notes}}</td>
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs"  style="font-weight: bold" ng-click="Edit_Item(item.id);">SELECT</button>					
							&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs"  style="font-weight: bold" ng-click="deleteItem(item.id);">DELETE</button>	
							<!--
							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Batch" href="<?=$rootScope['RootUrl']?>/alclair/edit_item/{{batch.id}}"></a>		
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete batch" ng-click="deleteBatch(item.id);"></a>
	                        -->
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
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="addItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmAddItem">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Item
	                        <!--<span style="font-size: large;color: #007FFF; font-weight:bold; margin-left:100px"> Designed for/Order by Same
	                        	<input type="checkbox" ng-model="add_item.same_name" ng-true-value="1" ng-false-value="0"> 
	                        </span>-->
                        </h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-6">
								<label class="control-label">Designed For:</label><br />
								<input type="text" ng-model="add_item.designed_for" class="form-control" required> 
							</div>
							<!--<div class="form-group col-md-6" ng-if="add_item.same_name">
								<label class="control-label">Ordered By:</label><br />
								<input type="text" ng-model="add_item.designed_for" class="form-control" ng-disabled="true" required> 
							</div>
							<div class="form-group col-md-6" ng-if="!add_item.same_name">
								<label class="control-label">Ordered By:</label><br />
								<input type="text" ng-model="add_item.ordered_by" class="form-control" required> 
							</div>-->
	                    </div>
	                    <div class="row">
		                    <div class="col-sm-4">
								<label class="control-label">Impression Date:</label><br /> 
								<div class="input-group">
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ImpressionDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
										</span>
								</div>
                			</div>
                			<div class="form-group col-md-4">
								<label class="control-label">Order #:</label><br />
								<input type="text" ng-model="add_item.order_number" class="form-control" required> 
							</div>				
							<div class="form-group col-md-3">
								<label class="control-label">Paid:</label><br />
								<input type="checkbox" ng-model="add_item.paid" ng-true-value="1" ng-false-value="0" style="width:30px; height:30px"> 
							</div>
	                    </div>
	                     <div class="row">
		                    <div class="form-group col-md-4">
								<label class="control-label">Customer Status:</label><br />                    
								<select class='form-control' ng-model='add_item.customer_status' ng-options="status.value as status.label for status in customerStatus" ng-blur="updateInHouseNestSteps(add_item.customer_status);">
									<!--<option value="">Select a monitor</option>-->
								</select>
							</div>
							<div class="form-group col-md-5">
								<label class="control-label">In-house Next Steps:</label><br />                    
								<select class='form-control' ng-model='add_item.next_step_id' ng-options="step.id as step.steps for step in inHouseNextStepList">
								</select>
							</div>	
						</div>
            			 <div class="row">
	            			 <div class="form-group col-md-12">
							 	<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
							 	<textarea type="text" name="notes" ng-model="add_item.notes" value="" class='form-control' rows='3'></textarea>
            				</div>
            			 </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" ng-click="Submit_Item()" ng-disabled="!frmAddItem.$valid">ADD ITEM</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 		</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->     
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="editItem" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmEditItem">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edit Item
	                        <!--<span style="font-size: large;color: #007FFF; font-weight:bold; margin-left:100px"> Designed for/Order by Same
	                        	<input type="checkbox" ng-model="edit_item.same_name" ng-true-value="1" ng-false-value="0"> 
	                        </span>-->
                        </h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-6">
								<label class="control-label">Designed For:</label><br />
								<input type="text" ng-model="edit_item.designed_for" class="form-control"  required> 
							</div>
							<!--<div class="form-group col-md-6" ng-if="edit_item.same_name">
								<label class="control-label">Ordered By:</label><br />
								<input type="text" ng-model="edit_item.designed_for" class="form-control" ng-disabled="true"> 
							</div>
							<div class="form-group col-md-6" ng-if="!edit_item.same_name">
								<label class="control-label">Ordered By:</label><br />
								<input type="text" ng-model="edit_item.ordered_by" class="form-control"> 
							</div>-->
	                    </div>
	                    <div class="row">
		                    <div class="col-sm-4">
								<label class="control-label">Impression Date:</label><br /> 
								<div class="input-group">
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ImpressionDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
										</span>
								</div>
                			</div>
                			<div class="form-group col-md-4">
								<label class="control-label">Order #:</label><br />
								<input type="text" ng-model="edit_item.order_number" class="form-control"  required> 
							</div>				
							<div class="form-group col-md-3">
								<label class="control-label">Paid:</label><br />
								<input type="checkbox" ng-model="edit_item.paid" ng-true-value="1" ng-false-value="0" style="width:30px; height:30px"> 
							</div>
	                    </div>
	                     <div class="row">
		                    <div class="form-group col-md-4">
								<label class="control-label">Customer Status:</label><br />                    
								<select class='form-control' ng-model='edit_item.customer_status' ng-options="status.value as status.label for status in customerStatus" ng-blur="updateInHouseNestSteps2(edit_item.customer_status);">
									<!--<option value="">Select a monitor</option>-->
								</select>
							</div>
							<div class="form-group col-md-5">
								<label class="control-label">In-house Next Steps:</label><br />                    
								<select class='form-control' ng-model='edit_item.next_step_id' ng-options="step.id as step.steps for step in inHouseNextStepList">
								</select>
							</div>	
						</div>
            			 <div class="row">
	            			 <div class="form-group col-md-12">
							 	<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
							 	<textarea type="text" name="notes" ng-model="edit_item.notes" value="" class='form-control' rows='3'></textarea>
            				</div>
            			 </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" ng-click="Update_Item(edit_item.id)" ng-disabled="!frmEditItem.$valid">UPDATE ITEM</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 		</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->     
	
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="editBatchName" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmEditBatchName">
                <div class="modal-content">
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-6">
								<label class="control-label">Batch Name:</label><br />
								<input type="text" ng-model="edit_batch.batch_name" class="form-control"> 
							</div>
	                    </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" ng-click="Update_BatchName()" ng-disabled="!frmEditBatchName.$valid"></i>UPDATE</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"></i>Close</button>
                 		</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->     
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_batch.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
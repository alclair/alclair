<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Batch_List">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-4">
                <a href="<?=$rootScope['RootURL']?>/alclair/batchs"><b style="font-size:20px">Batches</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;
            </div>
            
			<div class="col-md-2">
			</div>            
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<div class="form-group col-sm-1">       
				<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="showAddBatch()">
					<i class="fa fa-plus"></i> &nbsp; ADD BATCH
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
                <select class='form-control' ng-model='batch_type_id2' ng-options="batch_type2.id as batch_type2.types for batch_type2 in batchTypeList2" ng-blur="UpdateList()">
	                <option value="">All Groups</option>
				</select>
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
					<th style="text-align:center;">Group</th>
					<th style="text-align:center;">Batch Name</th>
					<th style="text-align:center;">Batch Owner</th>
					<th style="text-align:center;">Status</th>
					<th style="text-align:center;">Created Date</th>	
					<th style="text-align:center;">Received By</th>
					<th style="text-align:center;">Received  Date</th>
					<th style="text-align:center;">Notes</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='batch in BatchesList'>
					
					<td  style="text-align:center;" data-title="Group">{{batch.group}}</td>
					<td  style="text-align:center;" data-title="Batch Name">{{batch.batch_name}}</td>
					<td  style="text-align:center;" data-title="Batch Owner">{{batch.first_name}} {{batch.last_name}}</td>										
					<td  style="text-align:center;" data-title="Batch Status">{{batch.status}}</td>
					
					<td  style="text-align:center;" data-title="batch Date">{{batch.created_date}}</td>
										
					<td  ng-if="!batch.received_by" style="text-align:center;" data-title="Received By">
						 &nbsp;&nbsp;<button type="button" class="btn btn-danger btn-xs" ng-click="Receive_Batch(batch.id);">RECEIVE</button>	
					</td>
					<td  ng-if="batch.received_by" style="text-align:center;" data-title="Received By">{{batch.received_first_name}} {{batch.received_last_name}}</td>
					
					<td  ng-if="!batch.received_date" style="text-align:center;" data-title="Received">NOT RECEIVED</td>
					<td  ng-if="batch.received_date" style="text-align:center;" data-title="Received">{{batch.received_date}}</td>

					<td  style="text-align:center;" data-title="Notes">{{batch.batch_notes}}</td>
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                    
		                    &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs" style="font-weight: bold" ng-click="Edit_Batch(batch.id);">SELECT</button>					
							&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs" style="font-weight: bold" ng-click="deleteBatch(batch.id);">DELETE</button>	
							&nbsp;&nbsp;<button type="button" class="btn btn-secondary btn-xs" style="background-color:grey;color:white; font-weight: bold" ng-click="archiveBatch(batch.id);">ARCHIVE</button>	
							<!--
							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Batch" href="<?=$rootScope['RootUrl']?>/alclair_batch/edit_batch/{{batch.id}}"></a>		
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete batch" ng-click="deleteBatch(batch.id);"></a>
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
    <div class="modal fade modal-wide" id="addBatch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmAddBatch">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add Item</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
		                    <div class="form-group col-md-3">
								<label class="control-label">Batch Type:</label><br />                    
								<select class='form-control' ng-model='batch.batch_type_id' ng-options="batch_type.id as batch_type.types for batch_type in batchTypeList">
									<!--<option value="">Select a monitor</option>-->
								</select>
							</div>
							<div class="form-group col-md-3">
								<label class="control-label">Batch Status:</label><br />                    
								<select class='form-control' ng-model='batch.batch_status_id' ng-options="batch_status.id as batch_status.status for batch_status in batchStatusList">
								</select>
							</div>
							<div class="col-sm-4" ng-if = "batch.batch_status_id == 2">
								<label class="control-label">Date Mailed:</label><br /> 
								<div class="input-group">
									<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ShippedDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
										</span>
								</div>
                			</div>				
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label class="control-label">Batch Name:</label><br />
								<input type="text" ng-model="batch.batch_name" placeholder="Enter the batch name"  class="form-control"> 
							</div>
						</div>
            			 <div class="row">
	            			 <div class="form-group col-md-12">
							 	<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
							 	<textarea type="text" name="notes" ng-model="batch.batch_notes" value="" class='form-control' rows='3'></textarea>
            				</div>
            			 </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" ng-click="Submit_Batch()" ng-disabled="!frmAddBatch.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
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
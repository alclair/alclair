<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Repair_List">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-3">
                <b style="font-size:20px"> <a href="<?=$rootScope['RootURL']?>/alclair/repair_form">Repair Forms</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"></b>
            </div>
             <div class="form-group col-sm-3">
				<input type="text" id="barcode_rma" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
			</div>

            
            <!--<div class="col-md-6" style="text-align:right;">
                 <nav ng-show="TotalPages > 1" style="margin-top:-20px;text-align:right;">
                     <ul class="pagination">
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(1)" title="Go to page 1">&laquo;&laquo;</a></li>
                        <li ng-class="{disabled: PageIndex==1}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==1" ng-click="GoToPage(PageIndex-1)" title="Go to preious page">&laquo;</a></li>
                        <li ng-class="{active: pn == PageIndex}" ng-repeat="pn in PageRange"><a href="javascript:void(0);" ng-click="GoToPage(pn)">{{pn}} </a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(PageIndex+1)" title="Go to next page">&raquo;</a></li>
                        <li ng-class="{disabled: PageIndex==TotalPages}"><a href="javascript:void(0);" aria-hidden="true" ng-disabled="PageIndex==TotalPages" ng-click="GoToPage(TotalPages)" title="Go to last page">&raquo;&raquo;</a></li>
                    </ul>
                </nav>
            </div>-->
        </div>
        
		<!--<div class="row alert" style='background-color:#ddd;'>-->
            
            
		<div class="row alert" style='background-color:#ddd;'>
			<!--<div class="form-group col-sm-2">             
				<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Select a monitor</option>
				</select>
            </div>-->
            <div class="form-group col-sm-2">
                <select class='form-control' ng-model='repair_status_id' ng-options="repairStatus.order_in_repair as repairStatus.status_of_repair for repairStatus in repairStatusTableList">
	                <option value="">-- All States --</option>
                </select>
            </div>	

			<div class="form-group col-sm-2">
				<select class='form-control' ng-model='repaired_or_not' ng-options="repair.value as repair.label for repair in REPAIRED_OR_NOT">
					<option value="">Repaired and Not</option>
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
            <div class="col-sm-3">
                    <div class="input-group">
						<input type="text"  ng-model="SearchText" placeholder="Search by name or rma #"  uib-typeahead="customer as customer.customer_name for customer in customers| filter:{customer_name:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.customer_name" typeahead-editable="true" class="form-control" >		
						
						<!--<input type="text"  class="form-control" placeholder="Search by ticket # or producer's site" ng-model="SearchText" ng-enter="Search()"/>-->
						
						<a class="form-control-feedback form-control-clear" ng-click="ClearSearch()" style="pointer-events: auto; text-decoration: none;cursor: pointer;margin-top:10px;"></a>
							<span class="input-group-btn">
								<button class="btn btn-primary js-new pull-right" ng-click="Search();">
									<span class="glyphicon glyphicon-search"></span> Search
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
					<th style="text-align:center;">Customer's Name</th>
					<th style="text-align:center;">RMA #</th>
					<th style="text-align:center;">ReceivedDate</th>
					<th style="text-align:center;">Date Entered</th>
					<th style="text-align:center;">Repair Performed</th>
					<th style="text-align:center;">Monitor</th>
					<th style="text-align:center;">Diagnosis</th>
					<th style="text-align:center;">Quote</th>
					<th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='repair_form in Repair_FormList'>
					<td  style="text-align:center;" data-title="Customer's Name"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{repair_form.id}}">{{repair_form.customer_name}}</a></td>
					<td  style="text-align:center;" data-title="Order #">{{repair_form.rma_number}}</td>
					<td  style="text-align:center;" data-title="Received Date">{{repair_form.received_date}}</td>	
					<td  style="text-align:center;" data-title="Date Entered">{{repair_form.date_entered}}</td>	
					<td  ng-if="repair_form.rma_performed_date" style="text-align:center;" data-title="Repair Performed">{{repair_form.rma_performed_date}}</td>
					<td  ng-if="!repair_form.rma_performed_date" style="text-align:center;" data-title="Repair Performed">Not Performed Yet</td>	
					<td  style="text-align:center;" data-title="Monitor">{{repair_form.monitor_name}}</td>
			
					<td  style="text-align:center;width: 400px" data-title="Diagnosis">{{repair_form.diagnosis}}</td>
					<td  style="text-align:center;" data-title="Quote">{{repair_form.quotation | currency:"$"}}</td>
					<td  style="text-align:center;" data-title="Status">{{repair_form.status_of_repair}}</td>	
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
		                <?php if($_SESSION["UserName"] == 'Scott' || $_SESSION["UserName"] == 'admin') { ?>
							&nbsp;&nbsp;<button ng-disabled="repair_form.status_of_repair == 'Done'" type="button" class="btn btn-primary btn-xs" ng-click="LoadSelectDateModal(repair_form.id);">DONE</button>	
						<?php } ?>
                        	<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="View Form" href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{repair_form.id}}"></a>
	                        <a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteRepairForm(repair_form.id);"></a>
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
                        <h4 class="modal-title">Select a date for when the repair was shipped</h4>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="QC_List">
    <!-- Main Container Starts -->
    <div class="press-enter" style="width:99%;margin-left:10px;">
        <div class="row">
            <div class="col-md-3">
                <a href="<?=$rootScope['RootURL']?>/alclair/qc_form"><b style="font-size:20px">QC Forms</a> (Total: {{TotalRecords}})</b>&nbsp;&nbsp;<b style="display:inline; font-size:20px; color: #007FFF"> {{Passed}} Passed</b>
            </div>
            <div class="form-group col-sm-3">
				<input type="text" id="start" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
			</div>
        
        </div>
            
		<div class="row alert" style='background-color:#ddd;'>
			<div class="form-group col-sm-1">             
				<select class='form-control' ng-model='monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
					<option value="">Monitor</option>
				</select>
            </div>
            <div class="form-group col-md-2">                  
                <select class='form-control' ng-model='build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
					<option value="">Select a build type</option>
				</select>
            </div>
			<div class="form-group col-sm-2">
                <select class='form-control' ng-model='pass_or_fail' ng-options="pass_fail.value as pass_fail.label for pass_fail in PASS_OR_FAIL">
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
						<input type="text"  ng-model="SearchText" placeholder="Name or order #"  uib-typeahead="customer as customer.customer_name for customer in customers| filter:{customer_name:$viewValue}| limitTo:8"  typeahead-on-select="SearchText=$item.customer_name" typeahead-editable="true" class="form-control" >		
						
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
					<th style="text-align:center;">Customer's Name</th>
					<th style="text-align:center;">Build Type</th>
					<th style="text-align:center;">Order #</th>
					<th style="text-align:center;">Monitor</th>
					<th style="text-align:center;">Pass/Fail</th>
					<th style="text-align:center;">Saved On</th>
					<th style="text-align:center;">Shipped</th>
					<!--<th style="text-align:center;">Attachments</th>-->
                    <th style="text-align:center;">Options</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='qc_form in QC_FormList'>
					<td ng-if="qc_form.customer_name" style="text-align:center;" data-title="Customer's Name"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_qc_form_active_hp/{{qc_form.id}}">{{qc_form.customer_name}}</a></td>
					<td ng-if="!qc_form.customer_name" style="text-align:center;" data-title="Customer's Name"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_qc_form_active_hp/{{qc_form.id}}">NOT SUPPLIED</a></td>

					<td  ng-if="qc_form.build_type=='New - First Pass'"  style="text-align:center;" data-title="Build Type">Order - First Pass</td>
					<td  ng-if="qc_form.build_type=='Repair - First Pass'"  style="text-align:center;" data-title="Build Type">Repair - First Pass</td>
					<td  ng-if="qc_form.build_type=='New - Originally Failed QC'"  style="text-align:center;" data-title="Build Type">Order - Not First Pass</td>
					<td  ng-if="qc_form.build_type=='Repair - Originally Failed QC'"  style="text-align:center;" data-title="Build Type">Repair - Not First Pass</td>

					<!--<td  style="text-align:center;" data-title="Order #">{{qc_form.order_id}}</td>-->
					<td ng-if="qc_form.order_id > 100000" style="text-align:center;" data-title="Order #"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{qc_form.id_of_order}}">{{qc_form.order_id}}</a></td>
					<td ng-if="qc_form.order_id < 100000" style="text-align:center;" data-title="Order #"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_repair_form/{{qc_form.order_id}}">{{qc_form.order_id}}</a></td>

					<td  style="text-align:center;" data-title="Monitor">{{qc_form.monitor_name}}</td>
					<td  ng-if="qc_form.pass_or_fail=='PASS'" style="text-align:center;" data-title="Pass/Fail">PASSED</td>
					<td  ng-if="qc_form.pass_or_fail=='FAIL'" style="text-align:center;" data-title="Pass/Fail">FAILED</td>
					<td  ng-if="qc_form.pass_or_fail=='WAITING FOR ARTWORK'" style="text-align:center;" data-title="Pass/Fail">WAITING FOR ARTWORK</td>
					<td  ng-if="qc_form.pass_or_fail=='READY TO SHIP'" style="text-align:center;" data-title="Pass/Fail">READY TO SHIP</td>
					<td  ng-if="qc_form.pass_or_fail=='IMPORTED'" style="text-align:center;" data-title="Pass/Fail">IMPORTED</td>
					
					<td  style="text-align:center;" data-title="Saved On">{{qc_form.qc_date}}</td>
					<td  style="text-align:center;" ng-if="qc_form.shipping_date" data-title="Shipped">{{qc_form.shipping_date}}</td>	
					<td  style="text-align:center;" ng-if="!qc_form.shipping_date" data-title="Shipped">Not Shipped</td>	
					
					
                    <td data-title="Options">
	                    <div style="text-align:center;" >  
                        	<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="View Form" href="<?=$rootScope['RootUrl']?>/alclair/edit_qc_form_active_hp/{{qc_form.id}}"></a>
	                        <a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(qc_form.id);"></a>
						</div>
                    </td>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_Active_HP.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
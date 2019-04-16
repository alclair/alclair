<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<div id="main-container" class="container" ng-controller="Repair_Form_Edit">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {	
	?>
	
	<!--Add Popup Window-->
    <div class="modal fade modal-wide" id="updateRMA" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form name="frmUpdateRMA">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Repair Performed - {{repair_form.customer_name}}</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="row">
							<div class="form-group col-md-6">
								<label class="control-label"></label>
								<div class="text-left">
									<label class="control-label" style="font-size: large;color: #007FFF">CHECK APPROPRIATE BOXES</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</div>
									<label style="font-size: small;color: #FF0000">Left    &nbsp;&nbsp;  Right</label> <br/>&nbsp;
									<label><input type="checkbox" ng-model="repair_form.repaired_shell_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.repaired_shell" ng-true-value="1" ng-false-value="0">&emsp; REPAIRED SHELL</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.repaired_faceplate_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.repaired_faceplate" ng-true-value="1" ng-false-value="0">&emsp;REPAIRED FACEPLATE</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.repaired_jacks_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.repaired_jacks" ng-true-value="1" ng-false-value="0"> &emsp;REPAIRED JACKS</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.replaced_drivers_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.replaced_drivers" ng-true-value="1" ng-false-value="0"> &emsp;REPLACED DRIVER(S)</label><br/>
							</div>
							<div class="form-group col-md-6" style="margin-top: 10px;">
								<label class="control-label"></label>
								<div class="text-left">
									<label class="control-label" style="font-size: large;color: #007FFF"> </label> 
								</div>
									
									<label style="font-size: small;color: #FF0000">Left    &nbsp;&nbsp;  Right</label> <br/>&nbsp;
									<label><input type="checkbox" ng-model="repair_form.new_shells_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp; </label>
									<label><input type="checkbox" ng-model="repair_form.new_shells" ng-true-value="1" ng-false-value="0"> &emsp;NEW SHELLS</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.replaced_tubes_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.replaced_tubes" ng-true-value="1" ng-false-value="0"> &emsp;REPLACED TUBES</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.cleaned_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.cleaned" ng-true-value="1" ng-false-value="0"> &emsp;CLEANED</label><br/>&nbsp;
									
									<label><input type="checkbox" ng-model="repair_form.adjusted_fit_left" ng-true-value="1" ng-false-value="0">&nbsp;&nbsp;&emsp;</label>
									<label><input type="checkbox" ng-model="repair_form.adjusted_fit" ng-true-value="1" ng-false-value="0"> &emsp;ADJUSTED FIT</label><br/>&nbsp;							
									
	   						</div>
            			</div> <!-- END ROW -->
            			 <div class="row">
	            			 <div class="form-group col-md-12">
							 	<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
							 	<textarea type="text" name="notes" ng-model="repair_form.notes" value="" class='form-control' rows='3'></textarea>
            				</div>
            			 </div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" ng-click="updateRMA_Form_Performed(repair_form.id)" ng-disabled="!frmUpdateRMA.$valid"><i class="fa fa-arrow-right"></i>Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i>Close</button>
                 		</div>
                	</div>
                </div>
			</form>
        </div>
	</div><!-- END MODAL WINDOW -->            
	
    <form role="form">
	    <!--div class="container">-->
        		<div class="row">
            		<div class="col-md-12" style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
	            		<div class="col-md-5"> 
                    		<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/repair_list">RMA Form</a> - Update</b>
								&nbsp;&nbsp;&nbsp;&nbsp;<b id="qcform" style="font-size: 20px;color:green;cursor: pointer;" >QC FORM</b>
                		</div>
						<div class="col-md-3">  
                			<b style="font-size: 20px;" id="qcform" style="font-size: 20px;color:blue;cursor: pointer;" >Status: </b>  
                		</div>
						<div class="col-md-3" style="margin-left:-150px;margin-top:-5px">  
							<select class='form-control' ng-model='repair_form.repair_status_id' ng-options="repairStatus.order_in_repair as repairStatus.status_of_repair for repairStatus in RepairStatusList">
								<option value="">Select a status</option>
							</select>
						</div>
						<div class="col-md-2">  
							<button ng-if="(manufacturing_screen==0) && (the_user_is == 'Amanda' || the_user_is == 'admin')" style="font-weight: 600;border-radius: 4px" type="button" class="btn btn-warning" ng-click="showOnManufacturingScreen()">
                        		<i ></i> &nbsp; URGENT                         
							</button>
							<button ng-if="(manufacturing_screen==1) && (the_user_is == 'Amanda' || the_user_is == 'admin')" style="font-weight: 600;border-radius: 4px" type="button" class="btn btn-danger" ng-click="removeFromManufacturingScreen()">
								<i ></i> &nbsp; NOT URGENT                         
							</button>
						</div>
            		</div>
			</div>
        		
        			
        		
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Name:</label><br />
					<input type="text" ng-model="repair_form.customer_name" placeholder="Enter customer's name"  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Email:</label><br />
					<input type="text" ng-model="repair_form.email" placeholder="Enter customer's email address"  class="form-control"> 
                </div>  
                <div class="form-group col-md-3">
                    <label class="control-label">Phone:</label><br />
					<input type="text" ng-model="repair_form.phone" placeholder="Enter customer's phone #"  class="form-control"> 
                </div>  
                          
                <div class="form-group col-md-3">
	                 <label class="control-label">Monitor:</label><br />                    
                    <select class='form-control' ng-model='repair_form.monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
						<option value="">Select a monitor</option>
					</select>
                </div>				
            </div>
        <br />
            
        	<div class="row">
	        	<div class="form-group col-md-6">
					<label class="control-label">Address</label><br />
					<textarea type="text" name="notes" ng-model="repair_form.address" value="" class='form-control' rows='3'></textarea>
				</div>

				<div class="form-group col-md-6">
					<label class="control-label">Diagnosis</label><br />
					<textarea type="text" name="notes" ng-model="repair_form.diagnosis" value="" class='form-control' rows='3'></textarea>
            	</div>
			</div>
			<div class="row">
				<div class="form-group col-md-3">
					<label class="control-label">Quote:</label><br />
						<div class="left-inner-addon">    
							<span>$</span>
							<input type="text" ng-model="repair_form.quotation" placeholder="Enter quotation" value="" class="form-control" >
						</div>
						<!--<div style="margin-left:20px; margin-top:-30px !important">{{repair_form.quotation  | currency}}</div>-->
				</div>
				<div class="form-group col-md-3">		
				</div>
				<!--<div class="form-group col-md-6">
					<label class="control-label"></label>

					<div class="text-left">
	 					<label class="control-label" style="font-size: large;color: #007FFF">ARTWORK</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 					<label><input type="checkbox" ng-model="repair_form.artwork_white" ng-true-value="1" ng-false-value="0"> &nbsp; WHITE</label>&emsp;
	 					<label><input type="checkbox" ng-model="repair_form.artwork_black" ng-true-value="1" ng-false-value="0"> &nbsp; BLACK</label>
					</div>
					<div>
       					&emsp;<label><input type="checkbox" ng-model="repair_form.artwork_logo" ng-true-value="1" ng-false-value="0"> LOGO</label>&emsp;
	   					<label><input type="checkbox" ng-model="repair_form.artwork_icon" ng-true-value="1" ng-false-value="0">  ICON</label>&emsp;
	   					<label><input type="checkbox" ng-model="repair_form.artwork_stamp" ng-true-value="1" ng-false-value="0"> STAMP</label>&emsp;
	   					<label><input type="checkbox" ng-model="repair_form.artwork_script" ng-true-value="1" ng-false-value="0"> SCRIPT</label>&emsp;
	   					<label><input type="checkbox" ng-model="repair_form.artwork_custom" ng-true-value="1" ng-false-value="0"> CUSTOM</label>
	   				</div>
	   			</div>-->
			</div>
            
			<div class="row">
	        	<div class="form-group col-md-4">
		 			<label style="padding-right:6px; padding-top:15px"><input type="checkbox" ng-model="repair_form.customer_contacted" ng-true-value="1" ng-false-value="0">     &nbsp; CUSTOMER CONTACTED</label>
		 			<label><input type="checkbox" ng-model="repair_form.warranty_repair" ng-true-value="1" ng-false-value="0">     &nbsp; WARRANTY REPAIR</label><br />
		 			
		 			<label style="padding-right:38px"><input type="checkbox" ng-model="repair_form.customer_billed" ng-true-value="1" ng-false-value="0"> &nbsp; CUSTOMER BILLED</label>
		 			<label><input type="checkbox" ng-model="repair_form.consulted" ng-true-value="1" ng-false-value="0"> &nbsp; CONSULT BEFORE SHIP</label><br />
		 			
		 			<label style="padding-right:3px"><input type="checkbox" ng-model="repair_form.personal_item" ng-true-value="1" ng-false-value="0"> &nbsp; PERSONAL ITEMS REC'D</label>
		 			<label><input type="checkbox" ng-model="repair_form.rep_fit_issue" ng-true-value="1" ng-false-value="0"> &nbsp; REPAIR W/ FIT ISSUE</label><br />

            	</div>
				<div class="form-group col-md-2">
                	<label class="control-label">LEFT SHELL:</label><br />
					<input type="text" ng-model="repair_form.shell_left_color" placeholder="Enter left shell color"  class="form-control"> 
            	</div>
				<div class="form-group col-md-2">
                	<label class="control-label">RIGHT SHELL:</label><br />
					<input type="text" ng-model="repair_form.shell_right_color" placeholder="Enter right shell color"  class="form-control"> 
            	</div>
				<div class="form-group col-md-2">
					<label class="control-label">LEFT FACE:</label><br />
					<input type="text" ng-model="repair_form.shell_left_face" placeholder="Enter left face color"  class="form-control"> 
				</div>
				<div class="form-group col-md-2">
					<label class="control-label">RIGHT FACE:</label><br />
					<input type="text" ng-model="repair_form.shell_right_face" placeholder="Enter right face color"  class="form-control"> 
				</div>
        	</div>
			<div class="row">
				<div class="form-group col-md-3" style="margin-top:-15px;">
					<label class="control-label">Name:</label>	
					<input type="text" ng-model="repair_form.name_contacted" placeholder="Name of person contacted"  class="form-control">
            	</div>
            	<div class="form-group col-md-1">
            	</div>
				<div class="form-group col-md-2" style="margin-top:-15px;">
					<label class="control-label">LEFT TIP:</label><br />
					<input type="text" ng-model="repair_form.shell_left_tip" placeholder="Enter left tip color"  class="form-control"> 
            	</div>
				<div class="form-group col-md-2" style="margin-top:-15px;">
					<label class="control-label">RIGHT TIP:</label><br />
					<input type="text" ng-model="repair_form.shell_right_tip" placeholder="Enter right tip color"  class="form-control"> 
				</div>
				<div class="form-group col-md-2" style="margin-top:-15px;">
					<label class="control-label">LEFT LOGO:</label><br />
					<input type="text" ng-model="repair_form.left_alclair_logo" placeholder="Enter left logo"  class="form-control"> 
				</div>
				<div class="form-group col-md-2" style="margin-top:-15px;">
					<label class="control-label">RIGHT LOGO:</label><br />
					<input type="text" ng-model="repair_form.right_alclair_logo" placeholder="Enter right logo"  class="form-control"> 
				</div>
        	</div>
        	<br />
        	
        	<h3 style="color: #006400"><b>FAULTS</b></h3>
			<form name="FaultsForm" ng-submit="save()">
				<div ng-repeat="fault in faults">
					<div class="row">
						
                		<div class="form-group col-md-3">
							 <!--ng-if="traveler.hearing_protection == 1"-->
							 <label  class="control-label" style="font-size: large;color: #007FFF">CLASSIFICATION:</label><br />
							 <select class='form-control' ng-model='fault.classification' ng-options="fault.value as fault.label for fault in FAULT_TYPES"></select>
						</div>

						<div class="form-group col-md-3">
							 <label style="font-size: large;color: #007FFF" class="control-label">DESCRIPTION:</label><br />
							 <select ng-if="fault.classification == 'Sound'" class='form-control' ng-model='fault.description_id' ng-options="fault.id as fault.sound_fault for fault in soundFaultsList"></select>
							 <select ng-if="fault.classification == 'Fit'" class='form-control' ng-model='fault.description_id' ng-options="fault.id as fault.fit_fault for fault in fitFaultsList"></select>
							 <select ng-if="fault.classification == 'Design'" class='form-control' ng-model='fault.description_id' ng-options="fault.id as fault.design_fault for fault in designFaultsList"></select>
							 <!--<select class='form-control' ng-model='job.sector_id'  ng-options="sec.id as sec.sector for sec in sectorList"></select>-->
						</div>
					</div>

      			</div>
	  			<button class="btn btn-success" ng-click="newFault($event)">Add Fault</button>
	  			<button class="btn btn-danger" ng-click="removeFault($event)">Remove Fault</button>

	  			<!--<input type="submit" value="Save" ng-disabled="usersForm.$invalid || !users.length"  ng-click="save($event)"/>-->
	  		</form>
        	
			<br />
			
			<div class="row">    
				 <div class="form-group col-md-2">
                    <label class="control-label" style="font-size: large;color: #007FFF">RMA #:</label><br />
					<input type="text" ng-model="repair_form.rma_number" placeholder="Enter RMA #"  class="form-control"> 
                </div>
				<div class="form-group col-md-3">
					<div class="text-left">
						<label class="control-label" style="font-size: large;color: #007FFF">RECEIVED DATE</label><br />
		 			</div>
        			<div class="input-group">
                    	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="repair_form.received_date" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
						</span>
                	</div>			
                </div>
                <div class="form-group col-md-3">
					<div class="text-left">
						<label class="control-label" style="font-size: large;color: #007FFF">ESTIMATED SHIP DATE</label><br />
		 			</div>
        			<div class="input-group">
                    	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="repair_form.estimated_ship_date" is-open="openedShip" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default" ng-click="openShip($event)"><i class="fa fa-calendar"></i></button>
						</span>
                	</div>			
                </div>
			</div>
			<div class="row">    
                <div class="form-group col-md-2">
	                <label class="control-label" style="font-size: large;color: #007FFF"> <br /> </label>
					<div class="text-center">
						<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="updateRMA_Form()">
                        	<i class="fa fa-save"></i> &nbsp; SAVE REPAIR FORM
						</button>
		        	</div>
		 		</div>
		 		<div class="form-group col-md-2">
	                <label class="control-label" style="font-size: large;color: #007FFF"> <br /> </label>
					<div class="text-center">
						<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-success" ng-click="showUpdateRMAModal()">
                        	<i class="fa fa-refresh"></i> &nbsp; REPAIR PERFORMED                            
						</button>
		        	</div>
		 		</div>
				<div class="form-group col-md-2">
					<label class="control-label" style="font-size: large;color: #007FFF"> <br /> </label>
					<div class="text-center">
						<button style="font-weight: 600; margin-left: 8px" type="button" class="btn btn-danger" ng-click="Print_Traveler(repair_form.id)">
                        	<i class="fa fa-print"></i> &nbsp; PRINT TRAVELER                            
						</button>
		        	</div>
				</div>
			<!--</div>-->
        <br />
    </form>
     <br/>

        <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Date</th>
					<th style="text-align:center;">Status</th>
					<th style="text-align:center;">Person</th>
					<th style="text-align:center;">Notes</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='log_entry in logList'>
					<td style="text-align:center;">{{log_entry.date_to_show_date}}  &nbsp;&nbsp; {{log_entry.date_to_show_hours}} </a></td>
					<td style="text-align:center;">{{log_entry.status_of_repair}}</a></td>
					<td style="text-align:center;">{{log_entry.first_name}} &nbsp; {{log_entry.last_name}}</a></td>
					<td style="text-align:center;">{{log_entry.notes}}</a></td>
				</tr>					
			</tbody>
		</table>
    
 <?php
 	} 
 ?>	 
        
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
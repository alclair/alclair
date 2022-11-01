<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="QC_Form">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
    <form role="form">
	    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px; display:float">
	                <div class="form-group col-md-3">
                    	<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list">QC Form</a> - New Form</b>
	                </div>
                    	<!--<label class="control-label">Barcode:</label><br />-->
					<div class="form-group col-sm-3">
						<input type="text" id="start" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus">
					</div>
					<br/><br/>
					

                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Name:</label><br />
					<input type="text" ng-model="qc_form.customer_name" placeholder="Enter customer's name"  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Order #:</label><br />
					<input type="number" ng-model="qc_form.order_id" placeholder="Enter customer's order #"  class="form-control"> 
                </div>            
                <div class="form-group col-md-3">
	                 <label class="control-label">Monitor:</label><br />                    
                    <select class='form-control' ng-model='qc_form.monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
						<option value="">Select a monitor</option>
					</select>
                </div>
				<div class="form-group col-md-3">
	                 <label class="control-label">Build Type:</label><br />                    
                    <select class='form-control' ng-model='qc_form.build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
						<option value="">Select a build type</option>
					</select>
                </div>

            </div>
        </div>
            <br />
            
            <!--SHELLS / FACEPLATE / JACKS -->
            <div class="row">
                <div class="form-group col-md-4">
		 			<div class="text-left">
	         			<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('shells')">SHELLS</label><br />
		 			</div>
			 		<label><input type="checkbox" ng-model="qc_form.shells_hp_material" ng-true-value="1" ng-false-value="0">     &nbsp; CANALS HAVE HP MATERIAL
			 		</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_defects" ng-true-value="1" ng-false-value="0"> &nbsp; NO DEFECTS/BUBBLES/RESIDUE</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_colors" ng-true-value="1" ng-false-value="0"> &nbsp; COLORS CORRECT</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_matched_height" ng-true-value="1" ng-false-value="0"> &nbsp; MATCHING HEIGHT</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_canal_length" ng-true-value="1" ng-false-value="0"> &nbsp; CANAL LENGTH</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_helix_trimmed" ng-true-value="1" ng-false-value="0"> &nbsp; HELIX TRIMMED BACK</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_label" ng-true-value="1" ng-false-value="0"> &nbsp; NAME LABEL CORRECT</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_edges" ng-true-value="1" ng-false-value="0"> &nbsp; NO SHARP EDGES</label><br />
					<label><input type="checkbox" ng-model="qc_form.shells_high_shine" ng-true-value="1" ng-false-value="0"> &nbsp; HIGH SHINE</label>
			 			
                </div>
                
                <div class="form-group col-md-4">
	                <div class="text-left">
	         			<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('faceplate')">FACEPLATE</label><br />
		 			</div>
        			<label><input type="checkbox" ng-model="qc_form.faceplate_colors" ng-true-value="1" ng-false-value="0"> &nbsp; COLORS CORRECT
</label><br />
					<label><input type="checkbox" ng-model="qc_form.faceplate_buffing_material" ng-true-value="1" ng-false-value="0"> &nbsp; CLEAN FROM EXCESS BUFFING MATERIAL</label><br />
					<label><input type="checkbox" ng-model="qc_form.faceplate_seam" ng-true-value="1" ng-false-value="0"> &nbsp; SEAM ATTACHED WELL</label><br />
					<label><input type="checkbox" ng-model="qc_form.faceplate_orientation" ng-true-value="1" ng-false-value="0"> &nbsp; ORIENTATION CORRECT</label><br />
					<ul>
						<li>BUTTON ON TOP & VC ON BOTTOM </li>
						<li>MATCHES ON ROTATIONAL AXIS</li>
					</ul>  
					<label><input type="checkbox" ng-model="qc_form.faceplate_lanyard_loop" ng-true-value="1" ng-false-value="0"> &nbsp; LANYARD LOOP</label><br />
					<label><input type="checkbox" ng-model="qc_form.faceplate_knob_buttons" ng-true-value="1" ng-false-value="0"> &nbsp; KNOB SPINS/BUTTON PUSHES IN</label>
		 		</div>
            
            <div class="form-group col-md-4">
	            <div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('battery_door')">BATTERY DOOR</label><br />
		 		</div>
       			<label><input type="checkbox" ng-model="qc_form.battery_door_closes" ng-true-value="1" ng-false-value="0"> &nbsp; FULLY CLOSES WITH BATTERY INSERTED</label><br />
				<label><input type="checkbox" ng-model="qc_form.battery_door_correct" ng-true-value="1" ng-false-value="0"> &nbsp; CORRECT BATTERY DOOR ON L & R</label><br />
				<label><input type="checkbox" ng-model="qc_form.battery_door_opens_forward" ng-true-value="1" ng-false-value="0"> &nbsp; OPENS FORWARD</label>
         	</div>
        </div>
        
        <!--PORTS / SOUND / ARTWORK -->
        <div class="row">
            <div class="form-group col-md-4">
		 		<div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('ports')">PORTS</label><br />
		 		</div>
		 		<label><input type="checkbox" ng-model="qc_form.ports_cleaned" ng-true-value="1" ng-false-value="0">     &nbsp; CLEANED AND CLEAR</label><br />
				<label><input type="checkbox" ng-model="qc_form.ports_mic_flush" ng-true-value="1" ng-false-value="0"> &nbsp; (MIC) FLUSH TO SHELL AND SMOOTH</label><br />
				<label><input type="checkbox" ng-model="qc_form.ports_glued_correctly" ng-true-value="1" ng-false-value="0"> &nbsp; GLUED CORRECTLY</label><br />
            </div>
                
            <div class="form-group col-md-4">
	            <div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('sound')">SOUND</label><br />
		 		</div>
        		<label><input type="checkbox" ng-model="qc_form.sound_chip_programmed" ng-true-value="1" ng-false-value="0"> &nbsp; CHIP PROGRAMMED (INITIALS ON TRAVELER)</label><br />
				<label><input type="checkbox" ng-model="qc_form.sound_battery_signal" ng-true-value="1" ng-false-value="0"> &nbsp; GOOD BATTERY SIGNAL</label><br />
				<label><input type="checkbox" ng-model="qc_form.sound_programs" ng-true-value="1" ng-false-value="0"> &nbsp; ALL PROGRAMS CYCLE CORRECTLY</label><br />
				<label><input type="checkbox" ng-model="qc_form.sound_volume_control" ng-true-value="1" ng-false-value="0"> &nbsp; VC GETS LOUDER WHEN TWISTED FORWARD</label><br />
				<label><input type="checkbox" ng-model="qc_form.sound_mic_signal" ng-true-value="1" ng-false-value="0"> &nbsp; CLEAN MICROPHONE SIGNAL</label><br />
				<label><input type="checkbox" ng-model="qc_form.sound_balanced_volume" ng-true-value="1" ng-false-value="0"> &nbsp; BALANCED L & R VOLUME</label>
		 	</div>
            
        </div> 
        <br />
        
        <div class="row">
	        <div class="form-group col-md-6">
				<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
					<textarea type="text" name="notes" ng-model="qc_form.notes" value="" class='form-control' rows='3'></textarea>
            </div>
			<div class="form-group col-md-3">
                <label class="control-label" style="font-size: large;color: #007FFF">FREQUENCY RESPONSE</label><br />
                <div class="form-group col-md-6">
                    <input type="file" ng-file-select="onFileSelect($files)" ngf-fix-orientation="true" multiple onclick="this.value = null" />
                </div>
                <label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		        <div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="SaveData('SAVE')">
                        <i class="fa fa-save"></i> &nbsp; SAVE QC FORM                            
                    </button>
		        </div>
            </div>
	        <!--<div class="form-group col-md-3">
		        <label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		        <div class="text-center">
					<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="SaveData('SAVE')">
                        <i class="fa fa-save"></i> &nbsp; SAVE QC FORM                            
                    </button>
		        </div>
			</div>-->
        </div>
        <br />

        <div ng-if="Ready2Ship=='NO'" class="row">
	        <div class="form-group col-md-12">
		        <div class="text-left">
					<button  style="font-weight: 600" type="button" class="btn btn-success" ng-click="readyToShip()">
                        <i class="fa fa-envelope-o"></i> &nbsp; READY TO SHIP                           
                    </button>
		        </div>
			</div>
        </div>
        <br />

        
        <!--SHIPPING-->
        <div ng-if="Ready2Ship=='YES'"  class="row">
	        
            <div class="form-group col-md-3">
		 		<div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF" ng-click="populateBoxes('shipping')">SHIPPING</label><br />
		 		</div>
		 		<label><input type="checkbox" ng-model="qc_form.shipping_cable" ng-true-value="1" ng-false-value="0">     &nbsp; LANYARD</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_card" ng-true-value="1" ng-false-value="0"> &nbsp; CARD</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_additional" ng-true-value="1" ng-false-value="0"> &nbsp; ADDITIONAL PURCHASES</label><br />
            </div>
                
            <div class="form-group col-md-3">
	            <div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		 		</div>
        		<label><input type="checkbox" ng-model="qc_form.shipping_tools" ng-true-value="1" ng-false-value="0"> &nbsp; TOOLS</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_case" ng-true-value="1" ng-false-value="0"> &nbsp; CASE WITH LABEL</label><br />
		 	</div>
        </div>
        <div  ng-if="Ready2Ship=='YES'" class="row">
	        <div class="form-group col-md-4">
		        <div class="text-left">
					<button style="font-weight: 600" type="button" class="btn btn-success" ng-click="SaveData('SHIP')">
                        <i class="fa fa-plane"></i> &nbsp; SHIP PRODUCT                            
                    </button>
		        </div>
			</div>
        </div>
    </form>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_Active_HP.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
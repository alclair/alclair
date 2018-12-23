<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="Maintenance_Start">
	
    <!-- Main Container Starts -->
    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/user_logs/maintenance/ticket.id">Maintenance</a> - {{date_created}}</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Injection Pump Hertz:</label><br />
					<input type="text" ng-model="ticket.injection_pump_hertz" value="" class='form-control'> 
                </div>
                <div class="form-group col-md-3">
				 	<label class="control-label">Computer PSI:</label><br />
				 	<input type="text" ng-model="ticket.computer_psi" value="" class="form-control"> <!--placeholder="Enter in Computer PSI">-->
                </div>
                <div class="form-group col-md-3">
	            <div class="text-center">
                    <label class="control-label">Check Unload Pump Oil:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test1==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.unload_pump_oil_yn" ng-click="ticket.unload_pump_oil_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test1==1"class="btn btn-tyler_2 center-block" ng-model="ticket.unload_pump_oil_yn" ng-click="ticket.unload_pump_oil_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test1==0" class="btn btn-tyler_2 center-block" ng-model="ticket.unload_pump_oil_yn" ng-click="ticket.unload_pump_oil_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test1==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.unload_pump_oil_yn" ng-click="ticket.unload_pump_oil_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>
        		</div> <!-- END TEXT-CENTER-->
                </div>
                <div class="form-group col-md-3">
				<div class="text-center">
	              	<label class="control-label">Leaks Tank Battery & Lines:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test2==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.tank_battery_lines_yn" ng-click="ticket.tank_battery_lines_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test2==1"class="btn btn-tyler_2 center-block" ng-model="ticket.tank_battery_lines_yn" ng-click="ticket.tank_battery_lines_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test2==0" class="btn btn-tyler_2 center-block" ng-model="ticket.tank_battery_lines_yn" ng-click="ticket.tank_battery_lines_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test2==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.tank_battery_lines_yn" ng-click="ticket.tank_battery_lines_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>
        		</div> <!-- END TEXT-CENTER-->
				</div>
            </div>
            
            <div class="row">
                <div class="form-group col-md-3">
					<label class="control-label">Injector Plunger Temp 1:</label><br />
					<input type="text"  ng-model="ticket.injector_plunger_temp_1" value="" class='form-control'>
                </div>
                <div class="form-group col-md-3">
					<label class="control-label">Injector Plunger Temp 2:</label><br />
					<input type="text"  ng-model="ticket.injector_plunger_temp_2" value="" class='form-control'>
                </div>
                <div class="form-group col-md-3">
	            <div class="text-center">
					<label class="control-label">Lubricator Working:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test3==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.lubricator_working_yn" ng-click="ticket.lubricator_working_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test3==1"class="btn btn-tyler_2 center-block" ng-model="ticket.lubricator_working_yn" ng-click="ticket.lubricator_working_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test3==0" class="btn btn-tyler_2 center-block" ng-model="ticket.lubricator_working_yn" ng-click="ticket.lubricator_working_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test3==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.lubricator_working_yn" ng-click="ticket.lubricator_working_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>  
        			</div> <!-- END TEXT-CENTER-->              
        		</div>
                <div class="form-group col-md-3">
				<div class="text-center">
					<label class="control-label">Oil in Tri-plex:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test4==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.oil_in_triplex_yn" ng-click="ticket.oil_in_triplex_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test4==1"class="btn btn-tyler_2 center-block" ng-model="ticket.oil_in_triplex_yn" ng-click="ticket.oil_in_triplex_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test4==0" class="btn btn-tyler_2 center-block" ng-model="ticket.oil_in_triplex_yn" ng-click="ticket.oil_in_triplex_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test4==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.oil_in_triplex_yn" ng-click="ticket.oil_in_triplex_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>  
        			</div> <!-- END TEXT-CENTER-->                     
                    </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
	                <label class="control-label">Rock Oil Level:</label><br />
                    <input type="text" ng-model="ticket.rock_oil_level" value="" class='form-control'>
                </div>
            
                <div class="form-group col-md-3">
					<label class="control-label">Check Transfer Pump House:</label><br />                    
                    <input type="text" ng-model="ticket.transfer_pump_house" value="" class='form-control'>
				</div>
				<div class="form-group col-md-3">
				<div class="text-center">
                	<label class="control-label">Leaks, Plungers, Valves, Pipes & Fittings:</label><br />
					<div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test5==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.leaks_plungers_valves_pipes_fittings_yn" ng-click="ticket.leaks_plungers_valves_pipes_fittings_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test5==1"class="btn btn-tyler_2 center-block" ng-model="ticket.leaks_plungers_valves_pipes_fittings_yn" ng-click="ticket.leaks_plungers_valves_pipes_fittings_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test5==0" class="btn btn-tyler_2 center-block" ng-model="ticket.leaks_plungers_valves_pipes_fittings_yn" ng-click="ticket.leaks_plungers_valves_pipes_fittings_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test5==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.leaks_plungers_valves_pipes_fittings_yn" ng-click="ticket.leaks_plungers_valves_pipes_fittings_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div> 
        			</div> <!-- END TEXT-CENTER-->             
                </div>
                	<div class="form-group col-md-3">
					<div class="text-center">
                    	<label class="control-label">Pump House Filters:</label><br />
						<div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test6==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.pump_house_filters_yn" ng-click="ticket.pump_house_filters_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test6==1"class="btn btn-tyler_2 center-block" ng-model="ticket.pump_house_filters_yn" ng-click="ticket.pump_house_filters_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test6==0" class="btn btn-tyler_2 center-block" ng-model="ticket.pump_house_filters_yn" ng-click="ticket.pump_house_filters_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test6==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.pump_house_filters_yn" ng-click="ticket.pump_house_filters_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        				</div> 
	                </div> <!-- END TEXT-CENTER-->
					</div>
				</div>    
				<div class="row">
                	<div class="form-group col-md-3">
						<label class="control-label">Well Head Tubing Pressure:</label><br />
						<input type="text" ng-model="ticket.well_head_tubing_pressure" value="" class='form-control'>                
                	</div>
                             	
                		<div class="form-group col-md-3">
                    		<label class="control-label">Well Head Casing Pressure:</label><br />
							<input type="text" ng-model="ticket.well_head_casing_pressure" value="" class='form-control'>
                		</div>
						<div class="form-group col-md-3">
							<label class="control-label">Drinking Water:</label><br />
							<input type="text" ng-model="ticket.drinking_water" value="" class='form-control'>                
                		</div>
                		<div class="form-group col-md-3">
                    		<label class="control-label">Fire Ext Sump Flow Meter:</label><br />
							<input type="text" ng-model="ticket.fire_ext_sump_flow_meter" value="" class='form-control'>
                		</div>
            		</div>
	            
            
            <div class="row">
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="SaveData(ticket.id)">
                        <i class="fa fa-save"></i>  Save Changes                           
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="CloseLog(ticket.id)">
                        <i class="fa fa-times"></i>  Close Log                         
                    </button>
                </div>
            </div>

        <!-- end row -->
    </form>

</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->

<script src="<?=$rootScope["RootUrl"]?>/includes/app/user_logsCtrl.js"></script>
    
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
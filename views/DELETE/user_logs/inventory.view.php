<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="Inventory_Start">
	
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
                    <label class="control-label">Quantity of drip oil:</label><br />
					<input type="text" ng-model="ticket.drip_oil" value="" class='form-control'> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of grease:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.grease" value="" class="form-control"> <!--placeholder="Enter in Computer PSI">-->
				 		</div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of cleaning supplies:</label><br />
					<input type="text" ng-model="ticket.cleaning_supplies" value="" class='form-control'> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of rags:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.rags" value="" class="form-control"> <!--placeholder="Enter in Computer PSI">-->
				 		</div>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of filter socks:</label><br />
					<input type="text" ng-model="ticket.filter_socks" value="" class='form-control'> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of waster:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.water" value="" class="form-control"> <!--placeholder="Enter in Computer PSI">-->
				 		</div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Quantity of absorbent pads:</label><br />
					<input type="text" ng-model="ticket.absorbent_pads" value="" class='form-control'> 
                </div>
                <div class="form-group col-md-3">
                    <!--<label class="control-label">Quantity of rags:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.rags" value="" class="form-control">
				 		</div>-->
                </div>
            </div>
                
			<div class="row">
                <div class="form-group col-md-3">
	            <div class="text-center">
                    <label class="control-label">Check for leaks:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test1==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.leaks_yn" ng-click="ticket.leaks_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test1==1"class="btn btn-tyler_2 center-block" ng-model="ticket.leaks_yn" ng-click="ticket.leaks_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test1==0" class="btn btn-tyler_2 center-block" ng-model="ticket.leaks_yn" ng-click="ticket.leaks_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test1==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.leaks_yn" ng-click="ticket.leaks_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>
        		</div> <!-- END TEXT-CENTER-->
                </div>
                <div class="form-group col-md-3">
				<div class="text-center">
	              	<label class="control-label">Check pump conditions:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test2==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.pump_conditions_yn" ng-click="ticket.pump_conditions_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test2==1"class="btn btn-tyler_2 center-block" ng-model="ticket.pump_conditions_yn" ng-click="ticket.pump_conditions_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test2==0" class="btn btn-tyler_2 center-block" ng-model="ticket.pump_conditions_yn" ng-click="ticket.pump_conditions_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test2==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.pump_conditions_yn" ng-click="ticket.pump_conditions_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>
        		</div> <!-- END TEXT-CENTER-->
				</div>

                <div class="form-group col-md-3">
	            <div class="text-center">
					<label class="control-label">Check meters:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test3==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.meters_yn" ng-click="ticket.meters_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test3==1"class="btn btn-tyler_2 center-block" ng-model="ticket.meters_yn" ng-click="ticket.meters_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test3==0" class="btn btn-tyler_2 center-block" ng-model="ticket.meters_yn" ng-click="ticket.meters_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test3==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.meters_yn" ng-click="ticket.meters_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>  
        			</div> <!-- END TEXT-CENTER-->              
                	</div>
                <div class="form-group col-md-3">
				<div class="text-center">
					<label class="control-label">Check fire extinguishers:</label><br />
                    <div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test4==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.fire_extinguishers_yn" ng-click="ticket.fire_extinguishers_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test4==1"class="btn btn-tyler_2 center-block" ng-model="ticket.fire_extinguishers_yn" ng-click="ticket.fire_extinguishers_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test4==0" class="btn btn-tyler_2 center-block" ng-model="ticket.fire_extinguishers_yn" ng-click="ticket.fire_extinguishers_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test4==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.fire_extinguishers_yn" ng-click="ticket.fire_extinguishers_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div>  
        		</div> <!-- END TEXT-CENTER-->                     
                </div>
            </div>
  
                    
            <div class="row">
				<div class="form-group col-md-3">
				<div class="text-center">
                	<label class="control-label">Check eye wash stations:</label><br />
					<div class="btn-group" data-toggle="buttons">	
<label ng-if="ticket.test5==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.eye_wash_station_yn" ng-click="ticket.eye_wash_station_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test5==1"class="btn btn-tyler_2 center-block" ng-model="ticket.eye_wash_station_yn" ng-click="ticket.eye_wash_station_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
<label ng-if="ticket.test5==0" class="btn btn-tyler_2 center-block" ng-model="ticket.eye_wash_station_yn" ng-click="ticket.eye_wash_station_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
<label ng-if="ticket.test5==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.eye_wash_station_yn" ng-click="ticket.eye_wash_station_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        			</div> 
        		</div> <!-- END TEXT-CENTER-->             
                </div>
                	<div class="form-group col-md-3">
					<div class="text-center">
                    	<label class="control-label">Make sure all signs are still up:</label><br />
						<div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test6==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.signs_still_up_yn" ng-click="ticket.signs_still_up_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test6==1"class="btn btn-tyler_2 center-block" ng-model="ticket.signs_still_up_yn" ng-click="ticket.signs_still_up_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test6==0" class="btn btn-tyler_2 center-block" ng-model="ticket.signs_still_up_yn" ng-click="ticket.signs_still_up_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test6==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.signs_still_up_yn" ng-click="ticket.signs_still_up_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        				</div> 
	                </div> <!-- END TEXT-CENTER-->
					</div>
					
					<div class="form-group col-md-3">
					<div class="text-center">
                    	<label class="control-label">Check for spills:</label><br />
						<div class="btn-group" data-toggle="buttons">	
				 			<label ng-if="ticket.test7==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.spills_yn" ng-click="ticket.spills_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test7==1"class="btn btn-tyler_2 center-block" ng-model="ticket.spills_yn" ng-click="ticket.spills_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test7==0" class="btn btn-tyler_2 center-block" ng-model="ticket.spills_yn" ng-click="ticket.spills_yn=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test7==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.spills_yn" ng-click="ticket.spills_yn=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        				</div> 
	                </div> <!-- END TEXT-CENTER-->
					</div>
                </div>
                
                	<div class="row">
                		<div class="form-group col-md-6">
							<label class="control-label">Notes:</label><br />
							<textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
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
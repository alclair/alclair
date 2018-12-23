<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketAddCtrl">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - Add New Ticket</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Ticket number:</label><br />
					<input type="text" ng-model="ticket.ticket_number" placeholder="Enter a ticket #" uib-typeahead="ticket as ticket.name for ticket in tickets| filter:{name:$viewValue}| limitTo:4"  typeahead-on-select="ticket.ticket_id=$item.id" typeahead-editable="true" class="form-control" ng-blur="ticket_warning();"> 
				<!--<i ng-show="loadingTickets_Numbers" class="glyphicon glyphicon-refresh"></i>
                <!--<input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'> -->
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Producer's site:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.source_well_name" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control">
                    <!--<i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="LoadSearchWellModal()">Unlisted?</button>
                    </span>-->
							<span class="input-group-btn">
								<a type="button" class="btn btn-default" href="/admin/dual_box">Unlisted?</a>
							</span>
				 		</div>
                    <span>*search by well name, producer or file number</span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Barrels delivered:</label><br />
                    <div class="input-group">
                        <input type="number" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default">bbls</button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-6">
	              	<label class="control-label">Date delivered:</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" placeholder="Select a date"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>  	
				</div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                     <?php if ( $rootScope["SWDCustomer"] == "flatland" ) { ?>
	                                    <label class="control-label">Product type | GP Item Number:</label><br />
					<?php } else { ?> 
										<label class="control-label">Product type:</label><br />
					<?php } ?>					
                    <!--<select class='form-control' ng-model='ticket.water_type_id'>
                        <option value='0'>Select a water type</option>
                        <option ng-repeat="watertype in waterTypeList" value="{{watertype.id}}">{{watertype.type}}</option>
                    </select>-->       
                    <?php if ( $rootScope["SWDCustomer"] == "boh") { ?>
					<select class='form-control' ng-model='ticket.water_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">
						<option value="">Select a product type</option>
					</select>
                    <?php } else { ?>
                    <select class='form-control' ng-model='ticket.water_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList" ng-blur="product_type();">
						<option value="">Select a product type</option>
					</select>
                    <?php } ?>
                </div>
                <div class="form-group col-md-6">
	                 <label class="control-label">Trucking company:</label><br />
                    <!--<select class='form-control' ng-model='ticket.trucking_company_id'>
                        <option value='0'>Select a trucking company</option>
                        <option ng-repeat="company in truckingCompanyList" value="{{company.id}}">{{company.name}}</option>
                    </select>-->
                    
                    <select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
						<option value="">Select a trucking company</option>
					</select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
	                <label class="control-label">Truck or Pipeline:</label><br />
                    <select class='form-control' ng-model='ticket.delivery_method' ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">
                    </select>              
                </div>
                <div class="form-group col-md-6">
	                <label class="control-label">Pit or Well:</label><br />
                    <select class='form-control' ng-model='ticket.water_source_type' ng-options="watersourcetype.value as watersourcetype.label for watersourcetype in WaterSourceTypeList">
                    </select>
                </div>
            </div>
            
     <?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>       
            <div class="row">
                <div class="form-group col-md-6">
					<label class="control-label">Disposal well:</label><br />
                    <!--<select class='form-control' ng-model='ticket.disposal_well_id'>
                        <option value='0'>Select a disposal well</option>
                        <option ng-repeat="disposalwell in disposalWellList" value="{{disposalwell.id}}">{{disposalwell.common_name}}</option>
                    </select>-->
                    
                    <select class='form-control' ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
						<option value="">Select a disposal well</option>
					</select>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Notes:</label><br />
                    <textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
                </div>
            </div>
                    
                        
            <?php } else { ?>
            
            
            <div class="row">
                <div class="form-group col-md-6">
					<label class="control-label">Disposal well:</label><br />
                    <!--<select class='form-control' ng-model='ticket.disposal_well_id'>
                        <option value='0'>Select a disposal well</option>
                        <option ng-repeat="disposalwell in disposalWellList" value="{{disposalwell.id}}">{{disposalwell.common_name}}</option>
                    </select>-->
                    
                    <select class='form-control' ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
						<option value="">Select a disposal well</option>
					</select>
                </div>
				<div class="form-group col-md-6">
                	<label class="control-label">Driver's name:</label><br />
					<input type="text" name="driver_name" ng-model="ticket.driver_name" value="" class='form-control'>      
                </div>
	            </div>
                <div class="row">
                	<div class="form-group col-md-6">
                    	<label class="control-label">Notes:</label><br />
						<textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
                	</div>
                	<div class="form-group col-md-3">
						<label class="control-label">Filter sock changes:</label><br />
						<select class='form-control' ng-model='ticket.filter_sock' ng-options="filtersock.value as filtersock.label for filtersock in filterSock">
                    </select>	                
                </div>
            	</div>
	            
        <?php } ?>
       
       
       <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br />
                    <div class="form-group col-md-6">
                        <input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                    </div>
                </div>
<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>    
                <div class="form-group col-md-3">
					<label class="control-label">Filter sock changes:</label><br />
                    <select class='form-control' ng-model='ticket.filter_sock' ng-options="filtersock.value as filtersock.label for filtersock in filterSock">
                    </select>	                
                </div>
<?php } ?>
            </div>
            <div class="row">
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="SaveData()">
                        <i class="fa fa-save"></i>  Save -> View Ticket                            
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="NextTicket()">
                        <i class="fa fa-save"></i>  Add Next Ticket                           
                    </button>
                </div>
            </div>

        <!-- end row -->
    </form>

    <?php
	    // FORM FOR TRD COMPANY
	    } elseif( $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {   
	?>
<form role="form">
	<div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - Add New Ticket</b>
                </div>
            </div>

<!-- ROW ONE / Ticket/BOL, Date & Locationy-->
    <div class="row">
        <div class="form-group col-md-3">
            <label class="control-label">BOL/Ticket:</label><br />
            <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>
         </div>
       	 <div class="form-group col-md-3">
	   		 	<label class="control-label">Date delivered:</label><br />
	   		 	<div class="input-group">
                <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
            	</div>
        	</div>
        	<div class="form-group col-md-6">

	        	<label class="control-label">Producer's site:</label><br />
                <input type="text" ng-model="ticket.source_well_name" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control"  ng-blur="find_operator();">

                <!--<input type="text" ng-model="ticket.source_well_name" placeholder="search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control" ng-blur="find_operator();">
                    <i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>-->

                    		<!--<span class="input-group-btn">
								<a type="button" class="btn btn-default" href="/admin/dual_box">Unlisted?</a>
							</span>
				 		</div>-->
                    <span>*search by well name, producer or file number</span>
                </div>

    </div>
<!-- ROW TWO / Producer (Oil Company), Trucking Company, BBLs Delivered & Fluid Type-->
		<div class="row">
       		 <div class="form-group col-md-3">
                   <label class="control-label">Producer (Oil company):</label><br />
                   <input type="text" name="ticket_producer_id" ng-model="ticket.source_well_operator_name" value="" class='form-control' disabled>   
            </div>
			<div class="form-group col-md-3">
                     <label class="control-label">Trucking company:</label><br />

					<select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
						<option value="">Select a trucking company</option>
					</select>
            </div>	
            <div class="form-group col-md-3">
                <label class="control-label">Company man name:</label><br />
				<input type="text" name="company_man_name" ng-model="ticket.company_man_name" value="" class='form-control'>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label">Company man number:</label><br />
                <input type="text" name="company_man_number" ng-model="ticket.company_man_number" restrict-input="^[0-9-]*$" value="" class='form-control'>
            </div>
		</div>
		<div class="row">		
            <div class="form-group col-md-3">
                 <label class="control-label">Barrels delivered:</label><br />
                 <div class="input-group">
                    <input type="number" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default">bbls</button>
                     </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label">Barrel rate:</label><br />
                <div class="input-group">
					<input type="number" name="barrel_rate" ng-model="ticket.barrel_rate" value="" class='form-control'>      
					<span class="input-group-btn">
                    	<button type="button" class="btn btn-default">$/bbl</button>
					</span>
                </div>
        	</div>
            <div class="form-group col-md-3">
                <label class="control-label">Fluid type:</label><br />
               <!-- <select class='form-control' ng-model='ticket.fluid_type_id'>
                    <option value='0'>Select a fluid type</option>
                    <option ng-repeat="fluidtype in fluidTypeList" value="{{fluidtype.id}}">{{fluidtype.type}}</option>
                </select>-->
                <select class='form-control' ng-model='ticket.fluid_type_id' ng-options="fluid.id as fluid.type for fluid in fluidTypeList">
					<option value="">Select a fluid type</option>
				</select>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label">Driver name:</label><br />
				<input type="text" name="driver_name_id" ng-model="ticket.driver_name_id" value="" class='form-control'>      
            </div>
		</div>

<!-- ROW THREE / Co. Man Name, Co. Man Number, Percent Solid & Percent H2O-->
<!-- Co. Man Name FROM Oil Company -->
<!-- Co. Man Number FROM Co. Man Name -->
		<div class="row">
            <div class="form-group col-md-4">
                <label class="control-label">Truck type:</label><br />
                <!--<select class='form-control' ng-model='ticket.truck_type' ng-options="trucktype.value as trucktype.label for trucktype in TruckTypeList">
                </select>-->
                <select class='form-control' ng-model='ticket.truck_type' ng-options="trucktype.value as trucktype.label for trucktype in TruckTypeList">
					<option value="">Select a truck type</option>
				</select>
            </div>
            <div class="form-group col-md-2">
	            <div class="text-center">
                 	<label class="control-label ">% Solid</label><br />
                 	<div class="input-group">
					 	<input type="number" style="text-align:center" name="percent_solid" ng-model="ticket.percent_solid" restrict-input="^[0-9-]*$" value="" class='form-control'>
					 	<span class="input-group-btn">
                    		<button type="button" class="btn btn-default">%</button>
						</span>
                 	</div>
	            </div>
            </div>
            <div class="form-group col-md-2">
				<div class="text-center">
	                <label class="control-label">% H2O</label><br />
					<div class="input-group">
						<input type="number" style="text-align:center" name="percent_h2o" ng-model="ticket.percent_h2o" restrict-input="^[0-9-]*$" value="" class='form-control'>
						<span class="input-group-btn">
                    		<button type="button" class="btn btn-default">%</button>
						</span>
					</div>
				</div>
            </div>
            <div class="form-group col-md-2">
	            	<div class="text-center">
                 		<label class="control-label ">% Interphase</label><br />
				 		<div class="input-group">
				 			<input type="number" style="text-align:center" name="percent_interphase" ng-model="ticket.percent_interphase" restrict-input="^[0-9-]*$" value="" class='form-control'>
				 			<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">%</button>
							</span>
				 		</div>
	            	</div>
            </div>
			<div class="form-group col-md-2">
				<div class="text-center">
	                <label class="control-label">% Oil</label><br />
					<div class="input-group">
						<input type="number" style="text-align:center" name="percent_oil" ng-model="ticket.percent_oil" restrict-input="^[0-9-]*$" value="" class='form-control'>
						<span class="input-group-btn">
                    		<button type="button" class="btn btn-default">%</button>
						</span>
					</div>
				</div>
            </div>
		</div>
<!-- ROW FOUR / Driver name, Driver Number (optional), Percent Interphase & Percent Oil-->
<!-- Driver Name FROM Trucking Company -->
<!-- Driver Number (optional) FROM Driver Name -->

<!-- ROW FIVE / AFO/PO (optional)-->
            <div class="row">
                <div class="form-group col-md-2">
					<label class="control-label">AFE/PO (if required):</label><br />
					<input type="text" name="afepo" ng-model="ticket.afepo" value="" class='form-control'>
         		</div>         
         		 <div class="form-group col-md-2">
					<label class="control-label">Rig:</label><br />
					<select class='form-control' ng-model='ticket.rig_id' ng-options="rig.id as rig.rig_name for rig in rigsList" ng-blur="find_rig_info();">
						<option value="">Select a rig</option>
					</select>
         		</div>     
         		<div class="form-group col-md-2">
		 			<div class="text-center">
	         			<label class="control-label">H2S:</label><br />
			 			<div class="btn-group" data-toggle="buttons">						
							<label class="btn btn-tyler_2 center-block" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists = 1" ng-value=1><input type="radio" value="Yes">Yes</label>
							<label class="btn btn-tyler_2 center-block active" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists = 0" ng-value="0"><input type="radio" value="No">No</label>
        				</div>
        		  	</div>
         		</div>
         		<div class="form-group col-md-2">
	         		<div class="text-center">
	         			<label class="control-label">pCi / g  >  5</label><br />
			 			<div ng-if="ticket.microrens > 0">
				 			<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries<5">
									<input type="radio" value="Yes">Yes</label>
								<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries>=5">
									<input type="radio" value="No">No</label>		
							</div>		
				 		</div>	
				 		<div ng-if="ticket.microrens <= 0  || ticket.microrens==null">
				 			<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries<5 || !ticket.tenorm_picocuries.length">
									<input type="radio" value="Yes">Yes</label>
								<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries>=5">
									<input type="radio" value="No">No</label>		
							</div>		
				 		</div>		
					</div>
				</div>

						<!-- USED 8.347 INSTEAD OF 5 BECAUSE COULD NOT FIGURE OUT HOW TO USE NG-MODEL WITH A FORMULA FOR THE PICOCURIES.  THE DECISION INSTEAD WAS TO FIGURE OUT WHAT THE MICRO RENS/HOUR WOULD BE THAT WOULD EQUAT TO 5 PICOCURIES -->
				<div class="form-group col-md-2">
					<label class="control-label">Microrems/hour:</label><br />
					<input type="text" name="microrens" ng-model="ticket.microrens"  value="" class='form-control' ng-blur="Microrens2Picocuries();" ng-disabled="isMicrorensDisabled && ticket.tenorm_picocuries > 0">
         		</div>   
         		<div class="form-group col-md-2">
					<label class="control-label">Picocuries:</label><br />
					<div> <!--ng-if="ticket.microrens <= 0 || ticket.microrens == null"-->
						<input type="text" value="{{}}" class='form-control' ng-model="ticket.tenorm_picocuries" ng-blur="Picocuries2Microrens();" ng-disabled="isPicocuriesDisabled && ticket.microrens > 0">
					</div>
					<!--<div ng-if="ticket.microrens > 0">     
	                	<input type="text" value="{{ticket.microrens*0.599}}" class='form-control'>    
	                	<!-- USED 0.599 INSTEAD OF DIVIDING BY 1.67 BECAUSE THE OUTPUT HAS LESS DECIMAL POINTS -->               
                   	<!--</div>-->
         		</div>           		
            </div>
<!-- ROW SIX / Notes  & Attach Document-->
            <div class="row">
	            <div class="form-group col-md-6">
                	<label class="control-label">Notes:</label><br />
					<textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
            	</div>
            	 <div class="form-group col-md-2">
	         		<div class="text-center">
	         			<label class="control-label">Washout:</label><br />

					<div class="btn-group" data-toggle="buttons">						
						<label class="btn btn-tyler_2 center-block" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue = 1" ng-value=1><input type="radio" value="Yes">Yes</label>
						<label class="btn btn-tyler_2 center-block active" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue = 0" ng-value="0"><input type="radio" value="No">No</label>
        			</div>
        		</div>
        		</div>
				<div class="form-group col-md-2">
					<label class="control-label">Washout barrels:</label><br />
					<input type="text" name="washout_barrels" ng-init="ticket.washoutValue" ng-model="ticket.washout_barrels" value="" class='form-control' ng-disabled="!ticket.washoutValue">
         		</div>   
         		<div class="form-group col-md-2">
                	<label class="control-label">Destination Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList">
						<option value="">Select a tank</option>
					</select>
            	</div>	
            </div>
            <div class="row">
                <div class="form-group col-md-3">
	                <div class="container">
                    	<label class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br />
						<div class="form-group col-md-3">
                        	<input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                    	</div>
	                </div>
                </div>
            </div>
			<div class="row">
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="SaveData()">
                        <i class="fa fa-save"></i>  Save -> View Ticket                            
                    </button>
                </div>
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="NextTicket()">
                        <i class="fa fa-save"></i>  Add Next Ticket                           
                    </button>
                </div>
            </div>
</form>

 <?php
 	} 
 ?>	 
        
        
        
    <div class="modal fade modal-wide" id="divBarrelWarning" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Warning - Barrels Delivered</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span style="font-weight:bold;">{{ticket.barrels_delivered}} bbl </span><span>{{warning_message}}</span>
                            <br />
                            <p>Correct the amount and then click the "Change it" button to try again.</p>
                            <br />
                            <div class="input-group">
                                <input type="number" class="form-control ng-pristine ng-valid ng-touched" ng-model="new_barrel_delivery">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default">bbl</button>
                                    <button type="button" class="btn btn-primary" ng-click="changeit();">Change it</button>
                                </span>
                            </div>
                            <br />
                            <div style="text-align:center;">
                                <span style="font-weight:bold;color:red;">OR</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span>If this is the correct amount, click "Keep it"</span>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <button type="button" class="btn btn-primary" ng-click="keepit()">Keep it</button>                                        
                </div>
            </div>
        </div>
    </div>

</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "boh") {   
	?>			
		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_BOH.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd") {   
		    //print_r("Heading for ticktetCtrl_TRD")
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_WWL.js"></script>
	<?php
 	} else {
	 ?>
	 		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
 	<?php
	 	}
 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<style class="text/css">
    .modal.modal-wide .modal-dialog {
        width: 90%;
    }
    .modal-wide .modal-body {
        overflow-y: auto;
    }
    .modal.modal-medium .modal-dialog {
        width: 80%;
    }
    .modal-medium .modal-body {
        overflow-y: auto;
    }
    .red-border{
        border:1px solid #f00;
    }
    .zero-border{
        border:1px solid #000;
    }
    .active-button{
        border-left:4px solid #999 !important;
        border-top:4px solid #999 !important;
        border-bottom:4px solid #333 !important;
        border-right:4px solid #333 !important;
    }
</style>
<div id="main-container" class="container" ng-controller="ticketEditCtrl">
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - Edit Ticket</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label">Ticket number:</label><br />
				<input type="text" ng-model="ticket.ticket_number" placeholder="Enter a ticket #" uib-typeahead="ticket as ticket.name for ticket in tickets| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="ticket.ticket_id=$item.id" typeahead-editable="true" class="form-control">
                <i ng-show="loadingTickets_Numbers" class="glyphicon glyphicon-refresh"></i>
               <!-- <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>-->
            </div>
            <div class="form-group col-md-6">
	            <label class="control-label">Producer's site:</label><br />
				 		<div class="input-group">
				 			<input type="text" ng-model="ticket.source_well_name" placeholder="Search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8"  typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control">

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
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions"  ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
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
										<label class="control-label">Water type:</label><br />
					<?php } ?>					
                <select class='form-control' ng-model='ticket.water_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">
                </select>
                
			</div>
            <div class="form-group col-md-6">
	            <label class="control-label">Trucking company:</label><br />
                <select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
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
                <select class='form-control' ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
                </select>
            </div>
            <div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                <textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
            </div>
        </div>
        
        <?php } elseif( $rootScope["SWDCustomer"] == "boh") { ?>
	        
	    <div class="row">
		    <div class="form-group col-md-6">
				   <label class="control-label">Disposal well:</label><br />
                <select class='form-control' ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
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
                <select class='form-control' ng-model="ticket.filter_sock" ng-options="filtersock.value as filtersock.label for filtersock in filterSock">
                </select>	    
            </div>
		</div>
	       
        <?php } ?>
        
        <div class="row">
            <div class="form-group col-md-6">
                <label class="control-label" style="font-weight: bold;">Add Document/Image:</label><br />
	                <input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                <label ng-if="fileList.length == 0">No document</label>
                <div ng-if="fileList.length > 0">
                    <hr />
                    <b>Existing Documents/Images:</b>
                    <table class="table" style="width: 60%;">
                        <tr ng-repeat="file in fileList">
                            <td>
							<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
								<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}">
									{{file.filepath}}
								</a>
							</span>
							<span ng-show="file.filepath.indexOf('.pdf')>=0">
								<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
									{{file.filepath}}
								</a>
							</span>
							</td>
							<!-- IF STATEMENT exists because BOH had tickets without images attached
									Could not figure out how it was happening.  The thought here is to prevent anyone from deleting images if they were attached -->
							<?php if($rootScope["SWDCustomer"] != "boh") { ?>
	                            <td><a title="Delete document" ng-click="deleteDocument(file.id);"><i class="fa fa-trash"></i></a></td>
	                        <?php } ?>
                        </tr>
                    </table>
                </div>
            </div>
<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>     
            <div class="form-group col-md-3">
				<label class="control-label">Filter sock changes:</label><br />
                <select class='form-control' ng-model="ticket.filter_sock" ng-options="filtersock.value as filtersock.label for filtersock in filterSock">
                </select>	    
            </div>
       
<?php } ?>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <button type="button" class="btn btn-primary" ng-click="SaveData()">
                    <i class="fa fa-save"></i>Save                            
                               
                </button>
                <a href="<?=$rootScope["RootUrl"]?>/ticket/view/<?=$rootScope['Id']?>" style="margin-left:10px;" class="btn btn-default" ng-show="ticket.id!=undefined"><i class="fa fa-search"></i>View Ticket</a>
                <a href="<?=$rootScope["RootUrl"]?>/ticket" style="margin-left:10px;" ng-show="ticket.id==undefined" class="btn btn-default"><i class="fa fa-backward"></i>Back</a>

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
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">All tickets</a> - Edit Ticket</b>
                </div>
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
                <div class="input-group">
                <input type="text" ng-model="ticket.source_well_name" placeholder="search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="LoadSearchWellModal()">Unlisted?</button>
                    </span>
                </div>
                <span>*search by well name, producer or file number</span>
            </div>
    	</div>
<!-- ROW TWO / Producer (Oil Company), Trucking Company, BBLs Delivered & Fluid Type-->
		<div class="row">
       		 <div class="form-group col-md-3">
                <label class="control-label">Producer (Oil company):</label><br />
                <input type="text" name="ticket_producer_id" ng-model="ticket.source_well_operator_name" value="" class='form-control' disabled> 
				<!-- <input type="text" name="ticket_producer_id" ng-model="ticket.producer_name" value="" class='form-control'> -->
        	</div>
            <div class="form-group col-md-3">
                <label class="control-label">Trucking company:</label><br />
                <select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
                </select>
            </div>
             <div class="form-group col-md-3">
                <label class="control-label">Company man name:</label><br />
                <input type="text" name="company_man_name" ng-model="ticket.company_man_name" value="" class='form-control'>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label">Company Man Number:</label><br />
                <input type="text" name="company_man_number" ng-model="ticket.company_man_number" restrict-input="^[0-9-]*$" value="" class='form-control'>
            </div>
		</div>
		<div class="row">
			<div class="form-group col-md-3">
                 <label class="control-label">Barrels delivered:</label><br />
                 <div class="input-group">
                    <input type="text" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();" disabled>
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default">bbl</button>
                     </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label">Barrel rate:</label><br />
				<div class="input-group">
					<input type="text" name="barrel_rate" ng-model="ticket.barrel_rate" value="" class='form-control'> 
					<span class="input-group-btn">
                    	<button type="button" class="btn btn-default">$/bbl</button>
					</span>
				</div>
        	</div>
            <div class="form-group col-md-3">
                <label class="control-label">Fluid type:</label><br />
                <select class='form-control' ng-model='ticket.fluid_type_id' ng-options="fluidtype.id as fluidtype.type for fluidtype in fluidTypeList" disabled>
                </select>
            </div>
             <div class="form-group col-md-3">
                <label class="control-label">Driver name:</label><br />
				<input type="text" name="driver_name_id" ng-model="ticket.driver_name" value="" class='form-control'>
        	</div>
		</div>

<!-- ROW THREE / Co. Man Name, Co. Man Number, Percent Solid & Percent H2O-->
<!-- Co. Man Name FROM Oil Company -->
<!-- Co. Man Number FROM Co. Man Name -->
		<div class="row">
			 <div class="form-group col-md-4">
                <label class="control-label">Truck type:</label><br />
                <select class='form-control' ng-model='ticket.truck_type' ng-options="trucktype.value as trucktype.label for trucktype in TruckTypeList">
                </select>
            </div>
            <div class="form-group col-md-2">
	            <div class="text-center">
                 	<label class="control-label ">% Solid</label><br />
				 	<div class="input-group">
				 		<input type="text" style="text-align:center" name="percent_solid" ng-model="ticket.percent_solid" restrict-input="^[0-9-]*$" value="" class='form-control'>
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
						<input type="text" style="text-align:center" name="percent_h2o" ng-model="ticket.percent_h2o"   restrict-input="^[0-9-]*$" value="" class='form-control'>
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
				 		<input type="text" style="text-align:center" name="percent_interphase" string-to-number ng-model="ticket.percent_interphase"  value="" class='form-control'>
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
						<input type="text" style="text-align:center" name="percent_oil" ng-model="ticket.percent_oil" restrict-input="^[0-9-]*$" value="" class='form-control'>
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
				 			<label ng-if="ticket.test2==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test2==1"class="btn btn-tyler_2 center-block" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test2==0" class="btn btn-tyler_2 center-block" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test2==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.h2s_exists" ng-click="ticket.h2s_exists=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
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
				 			<div ng-if="ticket.microrens <= 0 ">
				 				<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries<5 || !ticket.tenorm_picocuries.length">
									<input type="radio" value="Yes">Yes</label>
									<label class="btn btn-tyler_3 center-block" ng-model="ticket.picocuriesValue" ng-click="" ng-value="0" ng_disabled="ticket.tenorm_picocuries>=5">
									<input type="radio" value="No">No</label>		
								</div>		
				 			</div>									
        				
        			</div>
        		</div>
				<div class="form-group col-md-2">
					<label class="control-label">Microrems/hour:</label><br />
					<input type="text" name="microrens" ng-model="ticket.microrens"  value="" class='form-control' ng-blur="Microrens2Picocuries();" ng-disabled="isMicrorensDisabled && ticket.tenorm_picocuries > 0">
         		</div>   
         		<div class="form-group col-md-2">
					<label class="control-label">Picocuries:</label><br />
					<div>
						<input type="text" value="{{}}" class='form-control' ng-model="ticket.tenorm_picocuries" ng-blur="Picocuries2Microrens();" ng-disabled="isPicocuriesDisabled && ticket.microrens > 0">
					</div>
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
				 			<label ng-if="ticket.test==1" class="btn btn-tyler_2 center-block active" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test==1"class="btn btn-tyler_2 center-block" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
				 			<label ng-if="ticket.test==0" class="btn btn-tyler_2 center-block" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue=1" ng-value="1">
				 				<input type="radio" value="Yes">Yes</label>
				 			<label ng-if="ticket.test==0"class="btn btn-tyler_2 center-block active" ng-model="ticket.washoutValue" ng-click="ticket.washoutValue=0" ng-value="0">
				 				<input type="radio" value="No">No</label>
        				</div>
        			</div>
        		</div>
				<div class="form-group col-md-2">
					<label class="control-label">Washout barrels:</label><br />
					<input type="text" name="washout_barrels" ng-init="washoutValue=1" ng-model="ticket.washout_barrels" value="" class='form-control' ng-disabled="!ticket.washoutValue">
         		</div>   
		 		<div class="form-group col-md-2">
                	<label class="control-label">Destination Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList">
						<option value="">Select a tank</option>
					</select>
            	</div>	
            </div>
            <div class="row">

            <div class="form-group col-md-12">
                <label class="control-label" style="font-weight: bold;">Add Document/Image:</label><br />

                <input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />

                <label ng-if="fileList.length == 0">No document</label>
                <div ng-if="fileList.length > 0">
                    <hr />
                    <b>Existing Documents/Images:</b>
                    <table class="table" style="width: 60%;">
                        <tr ng-repeat="file in fileList">
                            <td>
							<span ng-hide="file.filepath.indexOf('.pdf')!=-1">
					<a href="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}" data-lightbox="<?=$rootScope["RootUrl"]?>/data/{{file.filepath}}">
					{{file.filepath}}
					</a>
					</span>
					<span ng-show="file.filepath.indexOf('.pdf')>=0">
					<a href="javascript:void(0);" ng-click="OpenWindow(file.filepath)">
					{{file.filepath}}
					</a>
					</span>
							
							</td>
                            <td><a title="Delete document" ng-click="deleteDocument(file.id);"><i class="fa fa-trash"></i></a></td>

                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <button type="button" class="btn btn-primary" ng-click="SaveData()">
                    <i class="fa fa-save"></i>Save                            
                               
                </button>
                <a href="<?=$rootScope["RootUrl"]?>/ticket/view/<?=$rootScope['Id']?>" style="margin-left:10px;" class="btn btn-default" ng-show="ticket.id!=undefined"><i class="fa fa-search"></i>View Ticket</a>
                <a href="<?=$rootScope["RootUrl"]?>/ticket" style="margin-left:10px;" ng-show="ticket.id==undefined" class="btn btn-default"><i class="fa fa-backward"></i>Back</a>
            </div>
        </div>

        <!-- end row --></form>
 <?php
 	} 
 ?>	 
    <div class="modal fade modal-wide" id="SearchWellModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form name="frmNewWell">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Find or Create a Well</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>
                            Search for a well by entering some (or all) of the information requested below. If no well is found, a new, temporary well can be created for future use.
                        <button class="btn btn-default" type="button" ng-click="ClearSearchFilters()">Clear Filters</button>
                        </p>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Producer</label>
                                 <input type="text" ng-model="newwell.operator_name" placeholder="search for an operator" uib-typeahead="operator as operator.name for operator in operator_list| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="OperatorSelected($item.id)" typeahead-editable="false" class="form-control" />
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-6" id="divFields">
                                <label>Field Name</label>
                                <select class="form-control" ng-model="newwell.field_name" ng-change="SearchWells()" id="lstFields">
                                    
                                    <option ng-repeat="item in field_list" value="{{item.name}}">{{item.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="divCounty">
                                <label>County</label>
                                <select class="form-control" ng-model="newwell.county_name" ng-change="SearchWells()" id="lstCounty">
                                    
                                    <option ng-repeat="item in county_list" value="{{item.name}}">{{item.name}}</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Township</label>
                                <input type="text" class="form-control" ng-model="newwell.township" />
                                
                            </div>
                            <div class="col-md-4">
                                <label>Range</label>
                                <input type="number" class="form-control" ng-model="newwell.range" name="newwell_range" min="46" max="107" />
                                <div class="alert alert-error" ng-show="frmNewWell.newwell_range.$error.number">Range must be between 46 and 107</div>
                            </div>
                            <div class="col-md-4">
                                <label>Section</label>
                                <input type="number" class="form-control" ng-model="newwell.section" name="newwell_section" min="1" max="36" />
                                <div class="alert alert-error" ng-show="frmNewWell.newwell_section.$error.number">Section can only be between 1 and 36</div>
                            </div>
                        </div>
                        <p>
                            To create a new well, a temporary well name must be entered below.
                        </p>
                        <div class="row">
                            <div class="col-md-12">
                                <label>New Well Name</label>
                                <input type="text" class="form-control" ng-model="newwell.name" required />
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6" >
                        <div class="row">
                            <div class="col-md-12">
                                 {{searchingwells.length}} matching wells were found.
                            </div>
                        </div>
                        <div style="max-height:460px;overflow-y:auto;border:1px solid #ccc;">
                            <table class="table" id="tblWells" >
                            <thead>
                                <tr><th>Well</th><th>Producer</th><th>File Number</th></tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in searchingwells">
                                    <td><a href="#" ng-click="WellSelected(item)" >{{item.source_well_name}}</a></td>
                                    <td>{{item.source_well_operator_name}}</td>
                                    <td>{{item.source_well_file_number}}</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
                <button id="btnCreateWell" type="button" class="btn btn-primary" ng-click="CreateWell()" ng-disabled="!frmNewWell.$valid"><i class="fa fa-plane"></i> Create new well</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
            </div>
        </div>
            </form>
    </div>        
</div>


<div class="modal fade modal-wide" id="divBarrelWarning" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width:600px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Warning - Barrels Delivered</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-weight: bold;">{{ticket.barrels_delivered}} bbl </span><span>{{warning_message}}</span>
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
                                <div style="text-align: center;">
                                    <span style="font-weight: bold; color: red;">OR</span>
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
<script type="text/javascript">
    window.cfg.county_list = <?=$viewScope["county_list"]?>;  
    window.cfg.field_list = <?=$viewScope["field_list"]?>;  
    window.cfg.operator_list = <?=$viewScope["operator_list"]?>;
</script>
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "boh" ) {
	?>
		   	<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_BOH.js"></script>
	<?php
		} elseif( $rootScope["SWDCustomer"] == "trd" ) {   
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
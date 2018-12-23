<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketAddCtrl_landfill">
	
    <!-- Main Container Starts -->

    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing">Landfill Ticket</a> - Add New Ticket</b>
                </div>
            </div>           
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Test Report #:</label><br />
                    <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>
                </div>
                <div class="form-group col-md-3">
                     <label class="control-label">Test Date:</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Radium 226 pCi/g:</label><br />
                    <input type="text" name="radium_226" ng-model="ticket.radium_226" value="" class='form-control'>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Radium 228 pCi/g:</label><br />
                    <input type="text" name="radium_228" ng-model="ticket.radium_228" value="" class='form-control'>
                </div>
            </div>
            <div class="row">
	            <div class="form-group col-md-3">
                    <label class="control-label">Bill of Lading #:</label><br />
                    <input type="text" name="bill_lading_number" ng-model="ticket.bill_lading_number" value="" class='form-control'>
                </div>
                <div class="form-group col-md-3">
                     <label class="control-label">Ship Date:</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.ship_date" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-3">
	                 <label class="control-label">Trucking company:</label><br />
                    <select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
						<option value="">Select a trucking company</option>
					</select>
                </div>
                <div class="form-group col-md-3">
	                 <label class="control-label">Landfill Location:</label><br />
                    <select class='form-control' ng-model='ticket.landfill_disposal_site' ng-options="disposalSites.id as disposalSites.name for disposalSites in landfillDisposalSites">
						<option value="">Select a landfill location</option>
					</select>
                </div>
                
				<!--<div class="form-group col-md-3">
                    <label class="control-label">Landfill Location:</label>
                    <select class='form-control' ng-model='ticket.landfill_disposal_site' ng-change="Search()">  
<option value='2'>Select a landfill location</option>
<option ng-repeat="landfilldisposal in landfillDisposalSites" value="{{landfilldisposal.id}}" ng-selected="landfilldisposal.id==ticket.landfill_disposal_site" >{{landfilldisposal.name}}</option>
                    </select>
	             </div>	-->             
            </div>
			<div class="row">
				<div class="form-group col-md-3">
                	<label class="control-label">Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList">
						<option value="">Select a tank</option>
					</select>
            	</div>
            	<div class="form-group col-md-3">
					<label class="control-label">Fluid Type:</label><br />
					<select class='form-control' ng-model='ticket.fluid_type_id' ng-options="fluidType.id as fluidType.type for fluidType in fluidTypeList">
						<option value="">Select a fluid type</option>
					</select>
                </div>	
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
                <label class="control-label">Yards:</label><br />
                <input type="text" value="{{(ticket.barrels_delivered)*1000}}" class='form-control' disabled>                 
            </div>
        </div>
        <div class="row">
	        <div class="form-group col-md-3">
				<label class="control-label">Landfill Ticket #:</label><br />
                <input type="text" name="landfill_ticket_number" ng-model="ticket.landfill_ticket_number" value="" class='form-control'>
			</div>
            <div class="form-group col-md-3">
                <label class="control-label">Tare Weight:</label><br />
				<div class="input-group">
                 	<input type="number" name="tare_weight" ng-model="ticket.tare_weight" value="" class='form-control'>
					<span class="input-group-btn">
 	              		<button type="button" class="btn btn-default">Lbs.</button>
					</span>
				</div>
            </div>
            <div class="form-group col-md-3">
            	<label class="control-label">Loaded Weight:</label><br />
                <div class="input-group">
                    <input type="number" name="loaded_weight" ng-model="ticket.loaded_weight" value="" class='form-control'>
					<span class="input-group-btn">
                   		<button type="button" class="btn btn-default">Lbs.</button>
					</span>
				</div>
            </div>
			<div class="form-group col-md-3">
                <label class="control-label">Tons:</label><br />
                <input type="text" value="{{(ticket.loaded_weight-ticket.tare_weight)/2*0.001/100*100}}" class='form-control' disabled>                 
            </div>
        </div>
        <div class="row">
			<div class="form-group col-md-6">
                <label class="control-label">Notes:</label><br />
                <textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
	            </div>
        </div>
        <div class="row">
			<div class="form-group col-md-12">
				<label class="control-label" style="font-weight: bold;">Attach Document/Image:</label><br />
				<div class="form-group col-md-12">
					<input type="file" ng-file-select="onFileSelect($files)" onclick="this.value = null" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2">
                <button type="button" class="btn btn-primary" ng-click="SaveData()">
                    <i class="fa fa-save"></i>  Save                           
                </button>
            </div>
        </div>
       
        <!-- end row -->
    </form>

  
</div>
<!--<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>-->


<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
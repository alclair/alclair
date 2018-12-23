<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketAddCtrl_water">
	
    <!-- Main Container Starts -->
    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing">Water Ticket</a> - Add New Ticket</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Ticket number:</label><br />
                    <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>
                </div>
                <div class="form-group col-md-6">
                    <label class="control-label">Producer's site:</label><br />
                    <!--<input type="text" name="disposal_well_id" ng-model="ticket.disposal_well_id" value="13" class='form-control'>-->

                    <select class='form-control'  ng-model='ticket.disposal_well_id' ng-options="disposalwell.id as disposalwell.common_name for disposalwell in disposalWellList">
                        <option value="">Select a disposal well</option>

                    </select>
                    
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Barrels delivered:</label><br />
                    <div class="input-group">
                        <input type="number" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default">bbl</button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-6">
	                 <label class="control-label">Disposal well:</label><br />
					 <!--<input type="text" ng-model="ticket.source_well_name" placeholder="90282 // Killdeer West SWD #1 // Horizon"  uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control">
                    <i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>
                    <span>*search by well name, producer or file number</span>-->
                    
                    <input type="text" ng-model="ticket.source_well_name" placeholder="search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control" ng-blur="find_operator();">
                    <i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>

                    <span>*search by well name, producer or file number</span>
                    
					<!--<input type="text" ng-model="ticket.source_well_id"  uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="ticket.source_well_id=$item.id" typeahead-editable="false" class="form-control">
                    <i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>
                    <span>*search by well name, producer or file number</span>-->
                    <!--<label class="control-label">Delivery method:</label><br />
                    <select class='form-control' ng-model='ticket.delivery_method' ng-options="deliverymethod.value as deliverymethod.label for deliverymethod in DeliveryMethodList">
                    </select>-->
                </div>
                
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Date delivered:</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
				<div class="form-group col-md-6">
                    <label class="control-label">Water type:</label><br />
                    <select class='form-control' ng-model='ticket.water_type_id' ng-options="watertype.id as watertype.type for watertype in waterTypeList">>
						<option value="">Select a water type</option>
					</select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Trucking Company:</label><br />
                    <!--<input type="text" name="trucking_company" ng-model="ticket.trucking_company" placeholder="Horizon TRD #3" value="Horizon TRD #3" class='form-control'>
                </div>-->
					<select class='form-control' ng-model='ticket.trucking_company_id' ng-options="company.id as company.name for company in truckingCompanyList">
						<option value="">Select a trucking company</option>
					</select>
                </div>
                <div class="form-group col-md-6">
                	<label class="control-label">Barrel Rate:</label><br />
					<div class="input-group">
						<input type="number" name="barrel_rate" ng-model="ticket.barrel_rate" value="" class='form-control'>      
						<span class="input-group-btn">
                    		<button type="button" class="btn btn-default">$/bbl</button>
						</span>
                	</div>
        		</div>
            </div>
            <div class="row">
	            <div class="form-group col-md-6">
                	<label class="control-label">Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList" ng-blur="loadOtherFluidList(ticket.tank_id);">
						<option value="">Select a tank</option>
					</select>
            	</div>
            	<div class="form-group col-md-6">
					<label class="control-label">Fluid Type:</label><br />
					<select class='form-control' ng-model='ticket.fluid_type_id' ng-options="fluidType.id as fluidType.type for fluidType in fluidTypeList">
						<option value="">Select a fluid type</option>
					</select>
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
                        <i class="fa fa-save"></i>  Save -> View Ticket                            
                    </button>
                </div>
                <!--
                <div class="form-group col-md-2">
                    <button type="button" class="btn btn-primary" ng-click="NextTicket()">
                        <i class="fa fa-save"></i>  Add Next Ticket                           
                    </button>
                </div>
            </div>
						-->
        <!-- end row -->
    </form>

 
        
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


<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_outgoing.js"></script>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
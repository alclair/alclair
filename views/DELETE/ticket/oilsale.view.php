<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ticketAddCtrl_oil">
	
    <!-- Main Container Starts -->

    <form role="form">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index_outgoing">All outgoing tickets</a> - Add New Ticket</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Ticket number:</label><br />
                    <input type="text" name="ticket_number" ng-model="ticket.ticket_number" value="" class='form-control'>
                </div>
                <div class="form-group col-md-6">
                     <label class="control-label">Pickup date:</label><br />
                    <div class="input-group">
                        <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="ticket.date_delivered" is-open="opened" datepicker-options="dateOptions" ng-inputmask="99/99/9999" ng-required="true" close-text="Close" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open($event)"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
	            <div class="form-group col-md-2">
                	<label class="control-label">Top:</label><br />
					<div class="input-group">
                    	<input type="number" name="top_ft" ng-model="ticket.top_ft" value="" class='form-control'>
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default">ft.</button>
							</span>
                	</div>
            	</div>
            	<div class="form-group col-md-2">
                	<label class="control-label">Top:</label><br />
					<div class="input-group">
                    	<input type="number" name="top_in" ng-model="ticket.top_in" value="" max="11" class='form-control'>
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default">in.</button>
						</span>
                	</div>
            	</div>
            	<div class="form-group col-md-2">
                	<label class="control-label">Top:</label><br />
					<select class='form-control' value=0 ng-model='ticket.top_decimal' ng-options="topdecimal.value as topdecimal.label for topdecimal in TopDecimalList">
                    </select>
            	</div>
            	<div class="form-group col-md-3">
                    <label class="control-label">Top:</label><br />
						<div ng-if="(ticket.top_ft <= 0 || ticket.top_ft == null)">
                    		<input type="text" value="{{}} " class='form-control' disabled>           
                   		</div>
				   		<div ng-if="ticket.top_ft > 0 ">     
	                		<input type="text" value="{{ticket.top_ft}} ft. {{ticket.top_in}}{{ticket.top_decimal}} inches" class='form-control' disabled>                   
                   		</div>
                </div>
                <div class="form-group col-md-3">
                   <label class="control-label">Top temperature:</label><br />
                   	<div class="input-group">
                    <input type="number" name="top_temperature" ng-model="ticket.top_temperature" value="" class='form-control' >
                    <span class="input-group-btn">
                        	<button type="button" class="btn btn-default">&#8457</button>
					</span>
                   	</div>
                </div>
            </div>
                
                <div class="row">
	            	<div class="form-group col-md-2">
                		<label class="control-label">Bottom:</label><br />
						<div class="input-group">
                    		<input type="number" name="bottom_ft" ng-model="ticket.bottom_ft" value="" class='form-control'>
							<span class="input-group-btn">
                        		<button type="button" class="btn btn-default">ft.</button>
								</span>
                		</div>
            		</div>
					<div class="form-group col-md-2">
                		<label class="control-label">Bottom:</label><br />
						<div class="input-group">
                    		<input type="number" name="bottom_in" ng-model="ticket.bottom_in" value="" max="11" class='form-control'>
							<span class="input-group-btn">
                        		<button type="button" class="btn btn-default">in.</button>
								</span>
                		</div>
            		</div>
					<div class="form-group col-md-2">
                		<label class="control-label">Bottom:</label><br />
						<select class='form-control' ng-model='ticket.bottom_decimal' ng-options="topdecimal.value as topdecimal.label for topdecimal in TopDecimalList">
                    	</select>
            		</div>
					<div class="form-group col-md-3">
                    	<label class="control-label">Bottom:</label><br />
						<div ng-if="ticket.bottom_ft <= 0 || ticket.bottom_ft == null">
                    		<input type="text" value="{{}} " class='form-control' disabled>           
                   		</div>
				   		<div ng-if="ticket.bottom_ft > 0 ">     
	                		<input type="text" value="{{ticket.bottom_ft}} ft. {{ticket.bottom_in}}{{ticket.bottom_decimal}} inches" class='form-control' disabled>                   
                   		</div>
                	</div>
                	<div class="form-group col-md-3">
						<label class="control-label">Bottom temperature:</label><br />
						<div class="input-group">
							<input type="number" name="bottom_temperature" ng-model="ticket.bottom_temperature" value="" class='form-control' >
							<span class="input-group-btn">
                        		<button type="button" class="btn btn-default">&#8457</button>
								</span>
                   		</div>
                	</div>
            	</div>
            	

            <div class="row">
	            <div class="form-group col-md-2">
                   <label class="control-label">Obs. temperature:</label><br />
                   <div class="input-group">
                    <input type="number" name="observed_temperature" ng-model="ticket.observed_temperature" value="" class='form-control' > 
                    <span class="input-group-btn">
                        	<button type="button" class="btn btn-default">&#8457</button>
					</span>
                   	</div>
                </div>
                <div class="form-group col-md-2">
                   <label class="control-label">BS&W (%):</label><br />
				   <div class="input-group">
                    	<input type="number" name="bsw" ng-model="ticket.bsw" value="ticket.bsw" class='form-control' > 
						<span class="input-group-btn">
                    		<button type="button" class="btn btn-default">%</button>
						</span>
				   </div>
                </div>
                <div class="form-group col-md-2">
                    <label class="control-label">API gravity:</label><br />
					<input type="number" name="gravity	" ng-model="ticket.gravity" value="" class='form-control' > 
                </div>
                <div class="form-group col-md-3">
                	<label class="control-label">Barrels sold:</label><br />
					<div class="input-group">
                    	<input type="number" name="barrels_delivered" ng-model="ticket.barrels_delivered" value="" class='form-control' ng-blur="barrels_warning();">
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default">bbls</button>
						</span>
                	</div>
            	</div>
	        	<div class="form-group col-md-3">
					<label class="control-label">Oil price:</label><br />
		        	<div class="input-group">
						<input type="number" name="oil_price" ng-model="ticket.oil_price" value="" class='form-control'> 
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default">$</button>
							</span>
            		</div>
	        	</div>
            </div>
            <div class="row">
				<div class="form-group col-md-3">
                	<label class="control-label">Tank:</label><br />
					<select class='form-control' ng-model='ticket.tank_id' ng-options="tank.id as tank.name for tank in tankList" ng-blur="loadOtherFluidList(ticket.tank_id);">
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
					<label class="control-label">Deduct:</label><br />
		        	<div class="input-group">
						<input type="number" name="deduct" ng-model="ticket.deduct" value="" class='form-control'> 
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default">$</button>
							</span>
            		</div>
	        	</div>
               	<div class="form-group col-md-3">
                    <label class="control-label">Total $ - deduct:</label><br />
						<div ng-if="ticket.oil_price <= 0 || ticket.oil_price== null">
                    		<input type="text" value="${{}} " class='form-control' disabled>           
                   		</div>
				   		<div ng-if="ticket.oil_price > 0">     
	                		<input type="text" value="${{ticket.barrels_delivered*(ticket.oil_price-ticket.deduct)}}" class='form-control' disabled>                   
                   		</div>
                </div>
            </div>
			<div class="row">
                <div class="form-group col-md-6">
                    <label class="control-label">Notes:</label><br />
                    <textarea type="text" name="notes" ng-model="ticket.notes" value="" class='form-control' rows='2'></textarea>
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
<div>
    <div class="col-md-12" ng-controller="exportAll">
        <div class="form-group col-md-12">
            <h2>Export Tickets</h2>
        </div>
    
        <div class="form-group col-md-6">
            <label class="control-label">Start date:</label><br />
            <div class="input-group">
                <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="startdate"
                     is-open="opened_startdate" datepicker-options="dateOptions" ng-inputmask="9999-99-99"
                     ng-required="true" close-text="Close" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" ng-click="openStartDate($event)"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">End date:</label><br />
            <div class="input-group">
                <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="enddate"
                     is-open="opened_enddate" datepicker-options="dateOptions"  
                    ng-inputmask="9999-99-99" ng-required="true" close-text="Close" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" ng-click="openEndDate($event)"><i class="fa fa-calendar"></i></button>
                </span>
            </div>
        </div>
 
        

  	<?php
		if ( $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev"  || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon") {
	?>      	
    
	        <div class="form-group col-md-6">
                <label>Trucking Company</label>
				<select class="form-control" ng-model="trucking_company_id" ng-options="company.id as company.name for company in truckingCompanyList">		
				</select>    
            </div>
                <div class="form-group col-md-6">
	        		<label class="control-label">Source well:</label><br />
					<input type="text" ng-model="source_well_name" placeholder="search by well name, operator or file number" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="source_well_id=$item.id" typeahead-editable="false" class="form-control">
                    	<i ng-show="loadingWells" class="glyphicon glyphicon-refresh"></i>
            	</div>
    	
    	
                <div class="form-group col-md-6">
                    <label>
                    Disposal Well</label>
				<select class="form-control" ng-model="disposal_well_id" ng-options="item.id as item.common_name for item in disposalWellList">		
				</select>   
            	</div>
    	      
     <?php } ?>

        
     <?php
	    // FORM FOR TRD COMPANY
	    if( $rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl" ) {   
	?> 

    <br/>
	<form role="form">
        <div class="row">
			<div style="margin-left: 15px;">	
	       		<div class="form-group col-md-3">
    	                <label class="control-label">Landfill Disposal Cost:</label><br />
    	                <div class="input-group">
    	                	<input type="text" name="landfill_disposal_cost" ng-model="calc.landfill_disposal_cost" value="" class='form-control'>      
							<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">$/ton</button>
							</span>
    	                </div>
            	</div>
				<div class="form-group col-md-3">
                	    <label class="control-label">SWD Disposal:</label><br />
						<div class="input-group">
							<input type="text" name="swd_disposal" ng-model="calc.swd_disposal" value="" class='form-control'>     
							<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">$/bbl</button>
							</span>           
						</div>
            	</div>
				<div class="form-group col-md-2">
                	    <label class="control-label">Oil Price:</label><br />
						<div class="input-group">
							<input type="text" name="oil_price" ng-model="calc.oil_price" value="" class='form-control'>   
							<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">$</button>
							</span>             
						</div>
            	</div>
            	<div class="form-group col-md-2">
                	    <label class="control-label">Landlord Cost:</label><br />
						<div class="input-group">
							<input type="text" name="landlord_cost" ng-model="calc.landlord_cost" value="" class='form-control'>                
							<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">$/bbl</button>
							</span>
						</div>
            	</div>
            	<div class="form-group col-md-2">
                	    <label class="control-label">Interphase Cost:</label><br />
						<div class="input-group">
							<input type="text" name="interphase_cost" ng-model="calc.interphase_cost" value="" class='form-control'>                
							<span class="input-group-btn">
                    			<button type="button" class="btn btn-default">$/bbl</button>
							</span>
						</div>
            	</div>
			</div>
        </div>
	</form>
    <?php
	        }
	?>        

        <div class="form-group col-md-12">
            <button type="button" class="btn btn-primary" ng-click="export('ticket', '<? echo $rootScope["SWDCustomer"]; ?>')">
                <i class="fa fa-save"></i> Export 
            </button>
        </div>
    </div>
</div>


<script src="<?=$rootScope["RootUrl"]?>/includes/app/exportAll.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<div>
    <div class="col-md-12" ng-controller="ticketExportPDF">
		<div class="row">
			<div class="form-group col-md-12">
            <h2>Export Tickets in PDF</h2>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
                <label>
                    Start Date</label>
				<div class="input-group">
					<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="startdate"
						is-open="opened_startdate" datepicker-options="dateOptions"  ng-inputmask="9999-99-99"
						ng-required="true" close-text="Close" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="openStartDate($event)"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">End Date</label>
				
				<div class="input-group">
					<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="enddate"
						is-open="opened_enddate" datepicker-options="dateOptions" 
						ng-inputmask="9999-99-99" ng-required="true" close-text="Close" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default" ng-click="openEndDate($event)"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
		</div>
		<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>

		<div class="row">
			<div class="form-group col-md-6">
                <label>
                    Disposal Well</label>
				<select class="form-control" ng-model="disposalwell" ng-options="item.id as item.common_name for item in disposalWellList">		
				</select>    
			</div>
			
			<div class="form-group col-md-6">
                <label>
                    Trucking Company</label>
				<select class="form-control" ng-model="truckingcompany" ng-options="company.id as company.name for company in truckingCompanyList">		
				</select>    
			</div>
		</div> 

		<div class="row">
			<div class="form-group col-md-6">
				   <label>
						Producer</label>
						<select class="form-control" ng-model="operator_id" ng-options="operator.id as operator.name for operator in operatorList" ng-change="getWells()">		
				</select> 
			</div>
			<div class="form-group col-md-6">
				   <label>
						Producer's site</label>
				<input type="text" ng-model="source_well_name" placeholder="search for a well" uib-typeahead="well as well.name for well in wells| filter:{name:$viewValue}| limitTo:8" typeahead-on-select="source_well_id=$item.id" typeahead-editable="false" class="form-control" />
			</div>
		</div> 
		<div class="row">
			 <div class="form-group col-md-6">
	         	<?php if ( $rootScope["SWDCustomer"] == "flatland" ) { ?>
			 		<label class="control-label">Product type | GP Item Number:</label><br />
				<?php } else { ?> 
					<label class="control-label">Water type:</label><br />
				<?php } ?>		
				<select class="form-control" ng-model="water_type_id" ng-options="watertype.id as watertype.type for watertype in waterTypeList">			
				<!--<select class='form-control' ng-model="water_type_id">
					<option value='0'>Select a water type</option>
					<option ng-repeat="watertype in waterTypeList" value="{{watertype.id}}">{{watertype.type}}</option>-->
                </select>
            </div>
		</div>
		
<?php } ?>
        
		<div class="row">
			<div class="form-group col-md-12">
            	<button type="button" class="btn btn-primary" ng-click="export();">
                	<i class="fa fa-save"></i>Export PDF
					</button>
        	</div>
    	</div>

</div>
<!-- -->	
	<?php 
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
		<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "boh" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_BOH.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd") {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl") {   
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

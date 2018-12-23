<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<div>
    <div class="col-md-12" ng-controller="ticketExportPDF_ReconReport">
		<div class="row">
			<div class="form-group col-md-12">
            	<h2>Export Tickets in PDF</h2>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
                <label>Start Date</label>
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
						<button type="button" class="btn btn-default" ng-click="openEndDate($event)"><i class="fa fa-calendar" ></i></button>
					</span>
				</div>
			</div>
		</div>

<div class="row">
	<div class="form-group col-md-6">
        <label class="control-label">Start Time:</label><br />
		<input type="text" name="driver_name" ng-model="timepicker" value="" class='form-control'>      
    </div>
    <div class="form-group col-md-6">
        <label class="control-label">End Time:</label><br />
		<input type="text" name="driver_name" ng-model="timepicker2" value="" class='form-control'>      
    </div>
                
	 <!--<div class="form-group col-md-6">
	 <label class="control-label">Start Time</label>
	 	<div class="input-group"
     		moment-picker="timepicker"
		 	format="HH:mm:ss">
		 	
		 	<input class="form-control"
		 	placeholder="Select a time"
		 	ng-model="timepicker"
		 	ng-model-options="{ updateOn: 'blur' }">
		 	<span class="input-group-addon">
		 		<i class="fa fa-clock-o"></i>
		 	</span>
		 	<div moment-picker="myDate"> {{ myDate }} </div>
		 </div>
	 </div>-->
	
	<!--<div class="form-group col-md-6">
	<label class="control-label">End Time</label>
		 <div class="input-group"
     	moment-picker="timepicker2"
		 format="HH:mm:ss">
		
		 <input class="form-control"
		 placeholder="Select a time"
		 ng-model="timepicker2"
		 ng-model-options="{ updateOn: 'blur' }">
		  <span class="input-group-addon">
		 	<i class="fa fa-clock-o"></i>
		 </span>
		 <div moment-picker="myDate2"> {{ myDate2 }} </div>
	</div>-->
 </div>      
 

 	<div class="row">
 			<div class="col-md-6">
	 			<label class="control-label">Disposal Well</label>
                <select ng-model="SearchDisposalWell" ng-change="Search()" class="form-control">
                    <option selected value="0">---select site---</option>
                    <option ng-repeat="item in DisposalWells" value="{{item.id}}" ng-selected="item.id==SearchDisposalWell">{{item.common_name}}</option>
                </select>
			</div>
			<div class="col-md-6">
	 			<label class="control-label">Ticket Created By</label>
                <select ng-model="ticket_creator" ng-change="Search()" class="form-control">
                    <option selected value="0">---All---</option>
                    <option ng-repeat="item in creatorList" value="{{item.id}}" ng-selected="item.id==SearchDisposalWell">{{item.first_name}} {{item.last_name}}</option>
                </select>
			</div>
 	</div>
 	<div class="row">
	 	<div class="col-md-6">
	 	</div>
			<!--<div class="col-md-1"></div>-->
			<div class="col-xs-1">
				<label class="control-label"></label>
				<div class="input-group">  
					<button type="button" class="btn btn-primary" ng-click="export();">
                		<i class="fa fa-save"></i> Produce PDF
					</button>
	        	</div>
    		</div>
    		<div class="col-xs-1"></div>

    		<div class="col-xs-1">
				<label class="control-label"></label>
				<div class="input-group">  
					<button type="button" class="btn btn-primary" ng-click="email();">
                		<i class="fa fa-save"></i> E-mail PDF
					</button>
	        	</div>
    		</div>

	</div>
	
 </div>

<!-- -->	
	<?php
		if ( $rootScope["SWDCustomer"] == "dev1" || $rootScope["SWDCustomer"] == "flatland" || $rootScope["SWDCustomer"] == "henryhill" || $rootScope["SWDCustomer"] == "horizon" || $rootScope["SWDCustomer"] == "test" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl.js"></script>
    <?php
	    } elseif( $rootScope["SWDCustomer"] == "trd" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_TRD.js"></script>
	<?php
	    } elseif( $rootScope["SWDCustomer"] == "wwl") {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_WWL.js"></script>
	 <?php
	    } elseif( $rootScope["SWDCustomer"] == "boh" || $rootScope["SWDCustomer"] == "dev" ) {   
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ticketCtrl_BOH.js"></script>
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
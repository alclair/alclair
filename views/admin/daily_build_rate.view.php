<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<div id="main-container" class="container" ng-controller="Daily_Build_Rate">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
    <form role="form">
	    <div class="container">
        	<div class="row">
            	<div class="col-md-12">
                	<div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    	<b style="font-size: 20px;"><span style="color:blue">Daily Build Rate</span> - Admin</b>
                	</div>
            	</div>
        	</div>
	    </div>
            <div class="row">
                <div class="form-group col-md-2">
                    <label class="control-label">Daily Rate Build:</label><br />
					<input type="text" ng-model="daily.daily_rate"  class="form-control" style="text-align:center"> 
                </div>
                <div class="form-group col-md-2">
                    <label class="control-label">Fudge:</label><br />
					<input type="text" ng-model="daily.fudge"   class="form-control" style="text-align:center"> 
                </div>  
                <div class="form-group col-md-2">
                    <label class="control-label">Shop Day to Build:</label><br />
					<input type="text" ng-model="daily.shop_days"   class="form-control" style="text-align:center"> 
                </div>
                <div class="form-group col-md-2">
                    <label class="control-label">Next Ship Date:</label><br />
					<input type="text" ng-model="current_ship_date"   class="form-control" style="text-align:center"> 
                </div>
                <!--  
                <div class="form-group col-md-3">
					<label style="font-size: large;color: #007FFF" class="control-label">DATE:</label><br />
					<div class="input-group">
                    	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="daily.holiday_date" is-open="openedStart2" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
						<span class="input-group-btn">
                        	<button type="button" class="btn btn-default" ng-click="openStart2($event)"><i class="fa fa-calendar"></i></button>
						</span>
                	</div>		
            	</div>
            	-->
            </div>
            
        <br />
                	
        	<h3 style="color: #006400"><b>HOLIDAYS</b></h3>
			<form name="HolidaysForm" ng-submit="save()">
				<div id="{{$index}}" ng-repeat="(key, celebrate) in holidays">
					<div class="row">
                		<div class="form-group col-md-3">
							 <!--ng-if="traveler.hearing_protection == 1"-->
							 <label  class="control-label" style="font-size: large;color: #007FFF">HOLIDAY:</label><br />
							 <input type="text" ng-model="celebrate.holiday"  class="form-control"> 
							 <!--<select class='form-control' ng-model='fault.classification' ng-options="fault.value as fault.label for fault in FAULT_TYPES"></select>-->
						</div>
						<div class="form-group col-md-3">
							 <label style="font-size: large;color: #007FFF" class="control-label">DATE:</label><br />
							 <div class="input-group">
							 	<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="celebrate.holiday_date" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
							 	<span class="input-group-btn">
							 		<button type="button" class="btn btn-default" ng-click="openStart($event, $index)"><i class="fa fa-calendar"></i></button>
							 	</span>
                			</div>		
						</div>
					</div>
				</div>
	  			<button class="btn btn-success" ng-click="newHoliday($event)">Add Holiday</button>
	  			<button class="btn btn-danger" ng-click="removeHoliday($event)">Remove Holiday</button>
	  		</form>
        	
			<br />
			<div class="row">    
                <div class="form-group col-md-2">
	                <label class="control-label" style="font-size: large;color: #007FFF"> <br /> </label>
					<div class="text-center">
						<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="Update2()">
                        	<i class="fa fa-save"></i> &nbsp; UPDATE                            
						</button>
		        	</div>
		 		</div>
			</div>
        <br />
    </form>
    
    <div class="row">
	    <div class="col-md-2">  
            <b style="font-size: 20px; margin-left:20px" id="qcform" style="font-size: 20px;color:blue;cursor: pointer;" >Day:</b>  
		</div>
		<div class="col-md-3" style="margin-left:-120px;margin-top:-5px">  
			<select class='form-control' ng-model='day_to_view' ng-options="day.value as day.label for day in DAY_TO_VIEW" ng-blur="LoadData2(add_item.customer_status);"></select>
		</div>
    </div>
	<br/>
    
    <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Customer's Name</th>
					<th style="text-align:center;">Monitor</th>
					<th style="text-align:center;">Impression Detailing Date</th>
					<th style="text-align:center;">Estimated Ship Date</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='list in DailyList'>
					<td  style="text-align:center;" data-title="Customer's Name"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{list.id}}">{{list.designed_for}}</a></td>
					<td  style="text-align:center;" data-title="Monitor">{{list.monitor_name}}</td>	
					<td  style="text-align:center;" data-title="Imp. Det. Date">{{list.fake_imp_date}}</td>
					<td  style="text-align:center;" data-title="Est. Ship Date">{{list.estimated_ship_date}}</td>	
				</tr>
			</tbody>
		</table>
 <?php
 	} 
 ?>	 
        
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl_DBR.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
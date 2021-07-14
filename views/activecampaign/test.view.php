<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="ActiveCampaign">
    <!-- Main Container Starts -->
        <div class="row">
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="GetSalesOrder()"> Get</button>
	        </div>
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="Post()"> Post</button>
	        </div>
	         <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="Update()"> Update</button>
	        </div>
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="BuildSalesOrder('13823')"> Simulate</button>
	        </div>
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="CreateCustomer()"> Customer</button>
	        </div>
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="SalesOrder()"> Sales Orders</button>
	        </div>
        </div>
        <br/>
         <div class="row">
	         <div class="form-group col-md-2">
	        </div>
	        <!--
	        <div class="form-group col-md-2">
		   		<label class="control-label">Order #:</label><br />
		   		<input type="text" ng-model="order_number_to_get" placeholder="Order #"  class="form-control"> 
			</div>    
			-->
	        
	        <div class="form-group col-md-2">
		   		<label class="control-label">Low:</label><br />
		   		<input type="text" ng-model="low_customer_id"  class="form-control" style="text-align: center"> 
			</div>    
			<div class="form-group col-md-2">
		   		<label class="control-label">High:</label><br />
		   		<input type="text" ng-model="high_customer_id"  class="form-control" style="text-align: center"> 
			</div>    
			<div class="col-sm-2">
				<label class="control-label">Date</label><br />
				<div class="input-group">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="SearchStartDate" is-open="openedStart" datepicker-options="dateOptions" ng-inputmask="99/99/9999" close-text="Close" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" ng-click="openStart($event)"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>			
			</div>
			
			
			<div class="form-group col-md-3">
				<label class="control-label"></label><br />
		        <button type="button" class="btn btn-primary" ng-click="Run_Program()"> RUN</button>
	        </div>
	        	
	        <!--
	        <div class="form-group col-md-3">
				<label class="control-label"></label><br />
		        <button type="button" class="btn btn-primary" ng-click="Process3()"> PROCESS</button>
	        </div>
	        -->
         </div>
	
    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ActiveCampaign.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
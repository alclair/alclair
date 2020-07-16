<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<div ng-controller="Orders">
    <!-- Main Container Starts -->
        <div class="row">
	        <div class="col-md-2">
		        <button type="button" class="btn btn-primary" ng-click="Get()"> Get</button>
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
		        <button type="button" class="btn btn-primary" ng-click="SalesOrder()"> Sales Order</button>
	        </div>
        </div>
        <br/>
         <div class="row">
	         <div class="col-lg-2">
		         
	        </div>
	        <div class="form-group col-md-3">
		   		<label class="control-label">Order #:</label><br />
		   		<input type="text" ng-model="order_number_to_get" designed_fo placeholder="Order #"  class="form-control"> 
			</div>    
			<div class="form-group col-md-3">
				<label class="control-label"></label><br />
		        <button type="button" class="btn btn-primary" ng-click="Process3()"> PROCESS</button>
	        </div>
         </div>
	
    <!--Edit Popup Window End-->
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/SOS_4.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
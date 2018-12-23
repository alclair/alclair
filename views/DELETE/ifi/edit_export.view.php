<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<div id="main-container" class="container" ng-controller="ifi_Edit_Ship">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
    <form role="form">
	    <div class="container">
        	<div class="row">
            	<div class="col-md-12">
                	<div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    	<b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/alclair/qc_list">Shipping Page</a> - New Shipment</b>
                	</div>
            	</div>
                <div class="form-group col-md-3">
	                <label class="control-label" style="font-size: large;color: #007FFF">Stored Ship To:</label><br />                    
                    <select class='form-control' ng-model='ship_to_id' ng-options="ship_to.id as ship_to.title for ship_to in shipToList" ng-blur="LoadAddress(ship_to_id);">
						<option value="">Choose a stored ship to</option>
					</select>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label" style="font-size: large;color: #007FFF">Create New Ship To:</label><br />   
					<input type="text" ng-model="ship.new_ship_to" placeholder="Enter title if saving ship to"  class="form-control"> 
                </div>
        	</div>
        	<div class="row">
        		<div class="form-group col-md-3">
                    <label class="control-label">Company:</label><br />
					<input type="text" ng-model="ship.company" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Name:</label><br />
					<input type="text" ng-model="ship.name" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Address 1:</label><br />
					<input type="text" ng-model="ship.address_1" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Address 2:</label><br />
					<input type="text" ng-model="ship.address_2" placeholder=""  class="form-control"> 
                </div>
        	</div>
			<div class="row">
        		<div class="form-group col-md-3">
                    <label class="control-label">City/Town:</label><br />
					<input type="text" ng-model="ship.city" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">State/Province/County:</label><br />
					<input type="text" ng-model="ship.state" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Zip Code:</label><br />
					<input type="text" ng-model="ship.zip_code" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Country:</label><br />
					<input type="text" ng-model="ship.country" placeholder=""  class="form-control"> 
                </div>
        	</div>
			<div class="row">
				<div class="form-group col-md-3">
					<label class="control-label">Carrier:</label><br />
					<select class='form-control' ng-model='ship.carrier_id' ng-options="carrier.id as carrier.name for carrier in carriersList">
						<option value="">Choose a carrier</option>
					</select>
				</div>	
				<div class="form-group col-md-3">
                    <label class="control-label">Tracking #:</label><br />
					<input type="text" ng-model="ship.tracking" placeholder=""  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
					<label class="control-label">Warehouse:</label><br />
					<select class='form-control' ng-model='ship.warehouse_id' ng-options="warehouse.id as warehouse.name for warehouse in warehousesList">
						<option value="">Choose a warehouse</option>
					</select>
				</div>	
				<div class="form-group col-md-3">
					<label class="control-label">Order Type:</label><br />
					<select class='form-control' ng-model='ship.ordertype_id' ng-options="ordertype.id as ordertype.name for ordertype in orderTypesList">
						<option value="">Choose an order type</option>
					</select>
				</div>	
			</div>
			<div class="row">
				<div class="form-group col-md-3">
                    <label class="control-label">PO #:</label><br />
					<input type="text" ng-model="ship.po_number" placeholder=""  class="form-control"> 
					<br/>
					<p><span ng-if="ship.company">{{ship.company}}<br/></span>
					<span ng-if="ship.name">{{ship.name}}<br/></span>
					<span ng-if="ship.address_1">{{ship.address_1}}<br/></span>
					<span ng-if="ship.address_2">{{ship.address_2}}<br/></span>
					<span ng-if="ship.city">{{ship.city}},</span> <span ng-if="ship.state">{{ship.state}} </span><span ng-if="ship.zip_code">{{ship.zip_code}}</span>
					<br/><span ng-if="ship.country">{{ship.country}}</span>
					</p>
                </div>
               <div class="form-group col-md-3">
					<label class="control-label">Shipping cost:</label><br />
						<div class="left-inner-addon">
							<span>$</span>
							<input type="text" ng-model="ship.shipping_cost" placeholder="" value="" class="form-control"  >
						</div>
				</div>
               	<div class="form-group col-md-6">
					<label class="control-label">Notes:</label><br />
					<textarea type="text" name="notes" ng-model="ship.notes" value="" class='form-control' rows='5'></textarea>
            	</div>
        	</div>
        
	    </div>        
	    <div class="row">
	        <div class="form-group col-md-3">
		       	<div class="text-left">
					<button  style="font-weight: 600; border-radius: 4px;" type="button" class="btn btn-primary" ng-click="update($event)"> 
                       	<i class="fa fa-envelope-o"></i> &nbsp; UPDATE                        
					</button>
	        	</div>
			</div>
       	</div>
        <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Serial Number</th>
					<th style="text-align:center;">Product</th>
					<th style="text-align:center;">Status</th>	
					<th style="text-align:center;">Log ID</th>
                    <!--<th style="text-align:center;">Options</th>-->
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='sn in snList'>
					<td  style="text-align:center;" data-title="Date">{{sn.serial_number}}</td>
					<td  style="text-align:center;" data-title="Product">{{sn.category_name}} {{sn.product_name}}</td>
					<td  style="text-align:center;" data-title="Type">{{sn.status}}</td>
					<td  style="text-align:center;" data-title="Log ID">{{sn.log_id}}</td>					
                    <!--<td data-title="Options">
	                    <div style="text-align:center;" >  
							<a class="glyphicon glyphicon-check" style="cursor: pointer;" title="Edit Traveler" href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}"></a>
		                    &nbsp;&nbsp;<button type="button" class="btn btn-primary btn-xs" ng-click="PDF(order.order_id);">Traveler</button>
	                        &nbsp;&nbsp;<a class="glyphicon glyphicon-trash" style="cursor: pointer;" title="Delete ticket" ng-click="deleteForm(qc_form.id);"></a>
						</div>
                    </td>-->
				</tr>
			</tbody>
		</table>
        	
        	
    </form>
    
    
   <!-- <form name="usersForm" ng-submit="save()">
		<div ng-repeat="user in users">
			<div class="row">
				<div class="form-group col-md-2">
                    <label style="font-size: large;color: #007FFF" class="control-label">Discount (%):</label><br />
					<input style="text-align:center" type="number" ng-model="user.discount" placeholder=""  class="form-control"> 
                </div>
                <div ng-if="user.discount >= 0" class="form-group col-md-6">
					<label style="font-size: large;color: #007FFF" class="control-label">Serial Numbers:</label><br />
					<textarea type="text" name="notes" ng-model="user.serial_numbers" value="" class='form-control' rows='5'></textarea>
            	</div>				
            </div>
      		</div>
	  			<button style="font-weight: 600" type="button" class="btn btn-success" ng-click="newUser($event)">Add Serial Numbers</button>
	  			<button style="font-weight: 600" type="button" class="btn btn-danger" ng-click="removeUser($event)">Go Back</button>

	  		<br /><br />
			<div class="row">
	        	<div class="form-group col-md-3">
		        	<div class="text-left">
						<button  style="font-weight: 600" type="button" class="btn btn-primary" ng-disabled="!users[0].serial_numbers"  ng-click="save($event)"> 
                        	<i class="fa fa-envelope-o"></i> &nbsp; SUBMIT                           
						</button>
		        	</div>
				</div>
        	</div>
	  	</form>-->

 <?php
 	} 
 ?>	 
        
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />
<div id="main-container" class="container" ng-controller="QR_Code_Scanner">
<script type="text/javascript">
  var cart = 1;
</script>
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
                    <b style="font-size: 40px;color:blue">Order Received</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Barcode:</label><br />
					<input type="text" id="start" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus"> <!--ng-blur="LoadOrderInfo(qrcode.barcode);"-->
					<br/><br/>
					 <div class = "form-group col-md-9 text-center">
					 	<span class="input-group-btn">
							<button class="btn btn-success js-new pull-right btn-lg" style="font-weight: 600; border-radius: 4px;"  ng-click="Accept('order_received');">
								<span class="fa fa-envelope-o"></span> &nbsp; ACCEPT
								
							</button>
						</span>
		        	</div>
                </div>
                <div class="form-group col-md-8">
					<label style="font-size: large" class="control-label">Notes:</label><br />
					<textarea type="text" name="notes" ng-model="qrcode.notes" value="" class='form-control' rows='6'></textarea>
                </div>          
            </div>
            <div class="row" ng-if="qrcode.order_id">
                <div class="form-group col-md-3">
                    <label class="control-label" style="font-size: large;color: #007FFF">Order ID: <span  style="font-size: 24px;color: #000000"> {{qrcode.order_id}}</span></label>
					<!--<input type="text" ng-model="qrcode.order_id" placeholder="Barcode"  class="form-control">-->	
                </div>
            </div>
			<div class="row" ng-if="qrcode.designed_for" >
                 <div class="form-group col-md-6">
                    <label class="control-label" style="font-size: large;color: #007FFF">Designed For: <span  style="font-size: 24px;color: #000000"> {{qrcode.designed_for}}</span></label>
					<!--<input type="text" ng-model="qrcode.order_id" placeholder="Barcode"  class="form-control">-->	
                </div>      
                <div class="form-group col-md-6">
                    <label class="control-label" style="font-size: large;color: #007FFF; margin-top:-10px">Days in Cart: <span  style="font-size: 34px;color: red"> {{days}}</span></label>
					<!--<input type="text" ng-model="qrcode.order_id" placeholder="Barcode"  class="form-control">-->	
                </div>      
            </div>
        </div>
        <br />
                    
        <!--<div class="row">
	        <div class="form-group col-md-2">
		        <div class="text-left">
					<span class="input-group-btn">
						<button class="btn btn-success js-new pull-right" ng-click="Accept();">
							<span class="fa fa-envelope-o"></span> &nbsp; ACCEPT
						</button>
					</span>
		        </div>
			</div>-->
        <br />

        
           </form>
           
           <table>		
			<thead>
				<tr>
					<th style="text-align:center;">Designed For</th>
					<th style="text-align:center;">Order Status</th>
					<th style="text-align:center;">Order ID</th>
					<th style="text-align:center;">Order Date</th>	
					<th style="text-align:center;">Model</th>
					<th style="text-align:center;">Impressions Received</th>
					<th style="text-align:center;">Printed</th>
					<th style="text-align:center;">Check Highrise</th>
				</tr>
			</thead>	
			<tbody>
				<tr ng-repeat='order in OrdersList'>
					<td  ng-if="order.designed_for==' ' && order.billing_name==order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.billing_name}}</a></td>
					<td  ng-if="order.designed_for==' ' && order.billing_name!=order.shipping_name" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">Billing -{{order.billing_name}} / Shipping-{{order.shipping_name}}</a></td>
					<td  ng-if="order.designed_for!=' '" style="text-align:center;" data-title="Designed For"><a href="<?=$rootScope['RootUrl']?>/alclair/edit_traveler/{{order.id}}">{{order.designed_for}} </a></td>
					
					<td  style="text-align:center;" data-title="Order Status">{{order.status_of_order}}</td>
					
					<td  style="text-align:center;" data-title="Order ID">{{order.order_id}}</td>
					<td  style="text-align:center;" data-title="Order Date">{{order.date_to_post}}</td>
					<td  style="text-align:center;" data-title="Model">{{order.model}}</td>
					
					<td  ng-if="!order.received_date" style="text-align:center;" data-title="Impressions Received">NOT RECEIVED</td>
					<td  ng-if="order.received_date" style="text-align:center;" data-title="Impressions Received">{{order.received_date_to_post}}</td>
					
					<td  ng-if="order.printed" style="text-align:center;" data-title="Model">Printed</td>
					<td  ng-if="!order.printed" style="text-align:center;" data-title="Model">Not Printed</td>
					
					<td  ng-if="order.printed" style="text-align:center;" data-title="Check Highrise">
						<input type="checkbox" ng-model="order.highrise" ng-true-value="1" ng-false-value="0" ng-checked="1" ng-disabled="true">
					</td>
					<td  ng-if="!order.printed" style="text-align:center;" data-title="Check Highrise">
						<input type="checkbox" ng-model="order.highrise"  ng-true-value="1" ng-false-value="0">
					</td>
					
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
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair"  || $rootScope["SWDCustomer"] == "ifi") {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairManufacturingCtrl_AC.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="QR_Code_Scanner">
<script type="text/javascript">
  var cart = 12;
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
                    <b style="font-size: 40px;color:blue">Done </b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Barcode:</label><br />
					<input type="text"  id="start" ng-model="qrcode.barcode" placeholder="Barcode"  class="form-control" autofocus="autofocus"> 
					<br/><br/>
					 <div class = "form-group col-md-9 text-center">
					 	<span class="input-group-btn">
							<button class="btn btn-success js-new pull-right  btn-lg" style="font-weight: 600; border-radius: 4px;"  ng-click="Accept('done');">
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
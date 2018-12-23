<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="iFi">
	
    <!-- Main Container Starts -->

	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" || $rootScope["SWDCustomer"] == "ifi" ) {
	?>
    <form role="form">
	    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div style="border-bottom: 1px solid rgba(144, 128, 144, 0.4); padding-bottom: 15px; margin-bottom: 25px;">
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">QC Form</a> - New Form</b>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="control-label">Name:</label><br />
					<input type="text" ng-model="qc_form.customer_name" placeholder="Enter customer's name"  class="form-control"> 
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Order #:</label><br />
					<input type="number" ng-model="qc_form.order_id" placeholder="Enter customer's order #"  class="form-control"> 
                </div>            
                <div class="form-group col-md-3">
	                 <label class="control-label">Monitor:</label><br />                    
                    <select class='form-control' ng-model='qc_form.monitor_id' ng-options="IEM.id as IEM.name for IEM in monitorList">
						<option value="">Select a monitor</option>
					</select>
                </div>
				<div class="form-group col-md-3">
	                 <label class="control-label">Build Type:</label><br />                    
                    <select class='form-control' ng-model='qc_form.build_type_id' ng-options="buildType.id as buildType.type for buildType in buildTypeList">
						<option value="">Select a build type</option>
					</select>
                </div>

            </div>
        </div>
        <br />
                    
        <div class="row">
	        <div class="form-group col-md-6">
				<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
					<textarea type="text" name="notes" ng-model="qc_form.notes" value="" class='form-control' rows='3'></textarea>
            </div>
			<div class="form-group col-md-3">
                <label class="control-label" style="font-size: large;color: #007FFF">FREQUENCY RESPONSE</label><br />
                <div class="form-group col-md-6">
                    <input type="file" ng-file-select="onFileSelect($files)" ngf-fix-orientation="true" multiple onclick="this.value = null" />
                </div>
                <label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		        <div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="SaveData()">
                        <i class="fa fa-save"></i> &nbsp; SAVE QC FORM                            
                    </button>
		        </div>
            </div>
	        <!--<div class="form-group col-md-3">
		        <label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		        <div class="text-center">
					<button style="font-weight: 600" type="button" class="btn btn-primary" ng-click="SaveData('SAVE')">
                        <i class="fa fa-save"></i> &nbsp; SAVE QC FORM                            
                    </button>
		        </div>
			</div>-->
        </div>
        <br />
        <br />
        <div ng-if="Ready2Ship=='NO'" class="row">
	        <div class="form-group col-md-12">
		        <div class="text-left">
					<button  style="font-weight: 600" type="button" class="btn btn-success" ng-click="readyToShip()">
                        <i class="fa fa-envelope-o"></i> &nbsp; READY TO SHIP                           
                    </button>
		        </div>
			</div>
        </div>
        <br />

        
        <!--SHIPPING-->
        <div ng-if="Ready2Ship=='YES'"  class="row">
	        <div ng-if="qc_form.artwork_required==1 && qc_form.artwork_none==0" class="form-group col-md-4">
	            <div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF">ARTWORK</label><br />
		 		</div>
		 		<label><input type="checkbox" ng-model="qc_form.artwork_added" ng-true-value="1" ng-false-value="0"> &nbsp; ARTWORK ADDED</label><br />
       			<label ng-if="!qc_form.artwork_none"><input type="checkbox" ng-model="qc_form.artwork_placement" ng-true-value="1" ng-false-value="0"> &nbsp; CORRECT PLACEMENT</label><br />
				<label ng-if="!qc_form.artwork_none"><input type="checkbox" ng-model="qc_form.artwork_hq" ng-true-value="1" ng-false-value="0"> &nbsp; HQ PRINT</label><br />
         	</div>

            <div class="form-group col-md-3">
		 		<div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF">SHIPPING</label><br />
		 		</div>
		 		<label><input type="checkbox" ng-model="qc_form.shipping_cable" ng-true-value="1" ng-false-value="0">     &nbsp; CABLE</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_card" ng-true-value="1" ng-false-value="0"> &nbsp; CARD</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_additional" ng-true-value="1" ng-false-value="0"> &nbsp; ADDITIONAL PURCHASES</label><br />
            </div>
                
            <div class="form-group col-md-3">
	            <div class="text-left">
	         		<label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		 		</div>
        		<label><input type="checkbox" ng-model="qc_form.shipping_tools" ng-true-value="1" ng-false-value="0"> &nbsp; TOOLS</label><br />
				<label><input type="checkbox" ng-model="qc_form.shipping_case" ng-true-value="1" ng-false-value="0"> &nbsp; CASE WITH LABEL</label><br />
		 	</div>
        </div>
        <div  ng-if="Ready2Ship=='YES'" class="row">
	        <div class="form-group col-md-4">
		        <div class="text-left">
					<button style="font-weight: 600" type="button" class="btn btn-success" ng-click="SaveData('SHIP')">
                        <i class="fa fa-plane"></i> &nbsp; SHIP PRODUCT                            
                    </button>
		        </div>
			</div>
        </div>
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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ifiCtrl.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
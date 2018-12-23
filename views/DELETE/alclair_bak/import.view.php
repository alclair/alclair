<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="importWooCommerce">
	
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
                    	<b style="font-size: 20px;color: #007FFF">Import File From WooCommerce</b>
                	</div>
            	</div>
        	</div>
        <br />
                    
        <div class="row">
			<div class="form-group col-md-2">
                <label class="control-label" style="font-size: large;color: #007FFF">Choose the file</label><br />
                <div class="form-group col-md-6">
                    <input type="file" ng-file-select="onFileSelect($files,'csv')" accept=".csv" ngf-fix-orientation="true" multiple onclick="this.value = null" />
                </div>
                <label class="control-label" style="font-size: large;color: #007FFF"></label><br />
		        <div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="UploadData()">
                        <i class="fa fa-save"></i> &nbsp; UPLOAD                            
                    </button>
		        </div>
			</div>
        </div>
		<!--<div class="row">
			<div class="form-group col-md-2">
		        <div class="text-center">
					<button style="font-weight: 600; margin-right: 50px" type="button" class="btn btn-primary" ng-click="PDF()">
                        <i class="fa fa-save"></i> &nbsp; PDF                          
                    </button>
		        </div>
			</div>
        </div>-->
            <!--<div class="form-group col-md-6">
				<label class="control-label" style="font-size: large;color: #007FFF">NOTES</label><br />
					<textarea type="text" name="notes" ng-model="qc_form.notes" value="" class='form-control' rows='3'></textarea>
            </div>-->
        </div>
        <br />
        <br />
       


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
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/alclairCtrl.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
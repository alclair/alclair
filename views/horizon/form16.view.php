<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<br />
<div id="main-container" class="container" ng-controller="ImportFile">	
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
                    	<b style="font-size: 20px;color: #007FFF">Import File For SWD Submittal</b>
                	</div>
            	</div>
        	</div>
			<br />
			
			 <div class="row">
				 <div class="form-group col-md-2">
					 <label class="control-label">Month:</label><br />
					 <input type="text" ng-model="month" placeholder=""  class="form-control"> 
				</div>
				 <div class="form-group col-md-2">
					 <label class="control-label">Year:</label><br />
					 <input type="text" ng-model="year" placeholder=""  class="form-control"> 
				</div>
			 </div>
                    
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
        	
<br /><br />
		</div> <!-- CONTAINER -->
			
	</form>
	
	<span style="font-size:20px" ng-if="TotalRows!=null">THERE ARE {{TotalRows}} TOTAL ENTRIES </span>
	<br/>
	<br/>
	<table style="position: absolute; left: 0; ">
	<tbody>
		
		<tr ng-repeat='order in Print2Screen'>					
			<td  style="text-align:center;font-size: 12px" data-title="Order Status">{{order.screen}}</td>
		</tr>
	</tbody>
	</table>

	<?php 		
		
		echo $scope["Print2Screen"];	
	?>	 	

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
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/Horizon.js"></script>
    <?php  } 	?>	

<?php
//include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
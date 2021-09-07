<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>
<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>
<br />

<div id="main-container" class="container" ng-controller="Daily_Build_Rate">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>  

    <body>
        <div style="text-align:center;">
	        <h1 style="color:rgba(47, 18, 232, 0.5);font-size:80px; font-weight: bold; padding-bottom:40px">Glowforge Instructions</h1>    
        </div>
        
        <div style="text-align:left">
	        <h2>1. Navigate to the Glowforge application (<a href="https://app.glowforge.com/">app.glowforge.com</a>) <br> 
	        &nbsp;&nbsp;&nbsp;&nbsp; Use <span style="color: blue; font-weight: bold">tyler@alclair.com </span> for the email and <span style="color: blue; font-weight: bold">1Alclair!</span> for the password</h2>
        </div>    
        <div style="text-align:left">
	        <h2>2. Use the calipers to measure the height from the bottom of the earphone to where the <br>&nbsp;&nbsp;&nbsp;&nbsp;
		        name will be engraved. <br><br> 
		        &nbsp;&nbsp;&nbsp;&nbsp; 
		        <span style="color: black; font-weight: bold">
		        	Enter the number 
					<input type="text" style="text-align:center;" id="IEMhieght" name="IEMheight" size="4"> in inches.
				</span>
	        </h2>
        </div>   
        <div style="text-align:left">
	        <h2>3. Enter the height of the base the earphone will sit on. <br><br> 
		        &nbsp;&nbsp;&nbsp;&nbsp; 
		        <span style="color: black; font-weight: bold">
		        	Enter the number 
					<input type="text" style="text-align:center;" id="BASEheight" name="BASEheight" size="4"> in inches.
				</span>
	        </h2>
        </div>    
         <div style="text-align:left">
	        <h2>4. Press the button to calculate the 
		        <span style="font-weight:bold;color:red">
		        	Material Thickness
		        </span> 
		        to enter for an 
		        <span style="font-weight:bold;color:red"><br>&nbsp;&nbsp;&nbsp;&nbsp;
		        	UNCERTIFIED MATERIAL
		        </span> 
		        with the Glowforge. <br><br> 
	        </h2>
        </div>    
        <div class="row">
	        <div class="col-sm-1">
	        </div>
	        <div class="col-sm-4">
	        	<button style="border-radius: 4px; border-color: blue" class="btn btn-primary" ng-click="LoadData2($event)">
					<span style="font-weight:bold;font-size:20px"> 
	        			CALCULATE
					</span>
				</button>
	        </div>
 
     </body>

    <?php
	    } 
	?>

	</div>
	
</div>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>
<!-- -->
	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/Glowforge.js"></script>
    <?php  } 	?>	

<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
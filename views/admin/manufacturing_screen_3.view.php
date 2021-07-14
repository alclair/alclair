<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/tableresponsive.css"/>

<!-- PAGE REFRESH EVERY 5 MINUTES -->
<meta http-equiv="refresh" content="600" >

<div ng-controller="Manufacturing_Screen_3">

        
	<?php
		// FORM FOR DELOPMENT PAGE
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>       
 <div class="row">
 	<h1 style="font-weight:bold;font-size: 80px; color:blue" align=center> 
	 	What Customers Are Saying
	</h1>
	<br/>
	<br/>
	
	<h1 style="font-weight:bold;font-size: 70px;margin-left:40px">
		<span style="font-weight:bold;font-size: 100px;"> " </span>
			{{comment}}
		<span style="font-weight:bold;font-size: 100px;"> " </span>
		<br/>
		<br/>
		<span style="margin-left:200px;font-style: italic;"> 
			{{after_comment}} 
		</span>
	</h1>
 </div>
    <?php
	    } 
	?>

	</div>
	
</div>


	<?php
		if ( $rootScope["SWDCustomer"] == "dev" || $rootScope["SWDCustomer"] == "alclair" ) {
	?>
			<script src="<?=$rootScope["RootUrl"]?>/includes/app/ManufacturingScreenCtrl.js"></script>
    <?php  } 	?>	<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
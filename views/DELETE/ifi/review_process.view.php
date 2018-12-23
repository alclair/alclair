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
                    <b style="font-size: 20px;"><a href="<?=$rootScope['RootUrl']?>/ticket/index">Reviews</a> - Procedure</b>
                </div>
            </div>
            
			<b style="font-size: 18px;">1) After reviewer has agreed to the reviewer policy and you've gotten their shipping info, fill out a shipping request form (usually to Jared):</b>
            <br/>
            <a style="font-size:16px;" href="https://docs.google.com/forms/d/e/1FAIpQLSf2F1irSQgCY5QnMGLkrrFUMG_sy8ZCY4NlXpdSAGwDcH-A7A/viewform
">Review Policy</a>
			<br/><br/>

			<b style="font-size: 18px;">2) Jared will get the request approved and send you back a completed shipping form. It's then your job to copy over the information and fill out the Review Unit Sent Form:</b>
			<br/>
			<a style="font-size:16px;" href="https://docs.google.com/a/ifi-audio.com/forms/d/e/1FAIpQLSf2F1irSQgCY5QnMGLkrrFUMG_sy8ZCY4NlXpdSAGwDcH-A7A/viewform">Shipping Request</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a style="font-size:16px;" href="https://docs.google.com/a/ifi-audio.com/forms/d/e/1FAIpQLSfnUnnVShAqAzDPi8dSF-mFdfvqrQd8E4J2TdNolRyA59QknA/viewform">Review Unit Sent Form</a>

			<br/><br/>
			
			<b style="font-size: 18px;">3) At this time, there are also 2 possible spread sheets to fill out. If the reviewer is new, add him to our reviewer database:</b>
			<br/>
			<a style="font-size:16px;" href="https://docs.google.com/a/ifi-audio.com/spreadsheets/d/1pcZCqFhN_qTeJywULyM1_wkViKcJSv6vdQgAKgul4Ik/edit?usp=sheets_home&ths=true">iFi Reviewer Database</a>
			<br/><br/>

			<b style="font-size: 18px;">4) Whether the reviewer is new or not, you'll also need to add their info. to the "Who's Reviewed What" sheet. If they're old reviewer, their name should already be on there, so you just need to check off the unit you had just sent to them. If they're new, add their name and check off the units they've reviewed.</b>
			<br/>
			<a style="font-size:16px;" href="https://docs.google.com/a/ifi-audio.com/spreadsheets/d/1vksc5Xv0c1vOiz5zWDd_YFUl9TAxRV5jsGFx4BSL-nY/edit?usp=sheets_home&ths=true">Who has reviewed what!</a>
			<br/><br/>
            
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
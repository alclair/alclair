<?php if($rootScope["SWDCustomer"] == "lng") {

		header("Location: ".$rootScope["RootUrl"]."/plots/index");
	//exit;
	
	} elseif($rootScope["SWDCustomer"] == "trd" || $rootScope["SWDCustomer"] == "wwl") {
	
		header("Location: ".$rootScope["RootUrl"]."/ticket/index");
		
	} elseif($rootScope["SWDCustomer"] == "alclair") {
		
		if($_SESSION["UserName"] == 'Scott') {
			header("Location: ".$rootScope["RootUrl"]."/alclair/dashboard");
		}
		elseif($_SESSION["UserName"] == 'Dylan' ) {
			header("Location: ".$rootScope["RootUrl"]."/alclair/repair_form");
		} 
		elseif($_SESSION["UserName"] == 'Andy' || $_SESSION["UserName"] == 'admin' ) {
			header("Location: ".$rootScope["RootUrl"]."/alclair/orders");
		}
		else {
			header("Location: ".$rootScope["RootUrl"]."/alclair/orders");
		}
	} elseif($rootScope["SWDCustomer"] == "ifi") {
		header("Location: ".$rootScope["RootUrl"]."/ifi/shipping_request");
		
	} elseif($rootScope["SWDCustomer"] == "dev" ) {	
		
		header("Location: ".$rootScope["RootUrl"]."/ticket/index");
		
	} else { ?>

<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>



<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp.css">
<script src="<?=$rootScope["RootUrl"]?>/js/d3.min.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/d3.tip.v0.6.3.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/demo3.js"></script>




<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <div class="container">
                <!-- BEGIN PAGE TITLE -->
                <?php if($rootScope["SWDCustomer"] != "lng") { ?>
                <div class="page-title">
                    <h1>Dashboard
                                <small>dashboard & statistics</small>
                    </h1>
                </div>
                <?php } ?>
                <!-- END PAGE TITLE -->
           
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
               <div class="row">
				<?php
				include_once $rootScope["RootPath"]."views/reports/shared/well_monthly_by_water.view.php";
                ?>
			   </div>

               <div class="row">
				<?php
				include_once $rootScope["RootPath"]."views/reports/shared/well_monthly_by_delivery_method.view.php";
                ?>
			   </div>

			   <div class="row">
					<div class="col-md-6">
					<?php
						include_once $rootScope["RootPath"]."views/reports/shared/well_monthly_pie.view.php";
					?>
					</div>
					<div class="col-md-6">
					<?php
						include_once $rootScope["RootPath"]."views/reports/shared/well_monthly_pie_avg.view.php";
                    ?>
					</div>
			   </div>
                <div class="row">
                    <?php
                    include_once $rootScope["RootPath"]."views/reports/shared/daily_water_by_trucking_company.view.php";
                    ?>
                </div>
                <div class="row">
                    <?php
                    include_once $rootScope["RootPath"]."views/reports/shared/daily_water_by_oil_company.view.php";
                    ?>
                </div>
                <div class="row">
                    <?php
                    include_once $rootScope["RootPath"]."views/reports/shared/welllog_daily_oil_and_tank.view.php";
                    ?>
                </div>                
                <div class="row">
                    <?php
                    include_once $rootScope["RootPath"]."views/reports/shared/welllog_daily_injection.view.php";
                ?>
                </div>
                                            
            </div>
        </div>
        <!-- END PAGE CONTENT BODY -->
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->

</div>
<?php } ?>
<!-- END CONTAINER -->

<script src="<?=$rootScope["RootUrl"]?>/includes/app/homeCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>

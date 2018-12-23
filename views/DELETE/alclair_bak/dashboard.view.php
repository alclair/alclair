<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>



<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp.css">
<script src="<?=$rootScope["RootUrl"]?>/js/d3.min.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/d3.tip.v0.6.3.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/demo3_alclair.js"></script>




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
                                <!--<small>dashboard & statistics</small>-->
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
				include_once $rootScope["RootPath"]."views/reports/alclair/first_pass_yield.view.php";
                ?>
               </div>
               <div class="row">
                <?php
				//include_once $rootScope["RootPath"]."views/reports/alclair/initial_pass_qc.view.php";
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
<!-- END CONTAINER -->

<script src="<?=$rootScope["RootUrl"]?>/includes/app/homeCtrl.js"></script>
<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
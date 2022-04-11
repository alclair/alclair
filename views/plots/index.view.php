<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
//$surveyId=1;
?>

<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp.css">
<!-- Load c3.css -->
<link href="<?=$rootScope["RootUrl"]?>/css/c3.css" rel="stylesheet">
<!-- Load d3.js and c3.js -->
<script src="<?=$rootScope["RootUrl"]?>/js/d3.v3.min.js" charset="utf-8"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/	c3.min.js"></script>

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
                <div class="page-title">
                    <h1>Dashboard
                                <small>dashboard & statistics</small>
                    </h1>
                </div>
                <!-- END PAGE TITLE -->
           
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE CONTENT BODY -->
        <div class="page-content">
            <div class="container">
               <div class="row">
			   		<?php
					include_once $rootScope["RootPath"]."views/reports/shared/LNG_Plots.view.php";
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

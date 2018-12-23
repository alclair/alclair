<link rel="stylesheet" href="<?=$rootScope["RootUrl"]?>/css/swdapp.css">
<script src="<?=$rootScope["RootUrl"]?>/js/d3.min.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/d3.tip.v0.6.3.js"></script>
<script src="<?=$rootScope["RootUrl"]?>/js/demo3.js"></script>

<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
if(!empty($rootScope["Id"]))
{
    include_once $rootScope['RootPath']."views/reports/shared/".$rootScope["Id"].".view.php";
}
?>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
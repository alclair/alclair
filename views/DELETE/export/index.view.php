<?php
include_once $rootScope["RootPath"]."includes/header.inc.php";
if(!empty($rootScope["Id"]))
{
?>

<div style="margin-top:20px;">
    <?php
    include_once $rootScope['RootPath']."views/export/shared/".$rootScope["Id"].".view.php";
    ?>
</div>

<?php    
}
?>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
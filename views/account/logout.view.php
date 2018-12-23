<?php

include_once $rootScope["RootPath"]."includes/header.inc.php";
?>
<br /><br />
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success">You have logged out successfully. <a href="<?=$rootScope["RootUrl"]?>/account/login" class="btn btn-primary">Click here to login</a></div>

    </div>
</div>


<?php
include_once $rootScope["RootPath"]."includes/footer.inc.php";
?>
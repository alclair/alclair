<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}

//Scott was here
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

?>

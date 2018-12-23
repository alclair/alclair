<?php
if(empty($_SESSION["UserId"]))
{
	//echo $rootScope["RootUrl"]."/account/login";
    header("Location: ".$rootScope["RootUrl"]."/account/login");
    return;
}
include_once GetViewFile();
?>
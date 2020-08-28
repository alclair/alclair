<?php
if(empty($_SESSION["UserId"]))
{
    //header("Location: ".$rootScope["RootUrl"]."/account/login");
    //return;
    
    $_SESSION["UserId"]=9999;
    $_SESSION["UserName"]='Football';
    $_SESSION["IsAdmin"]=1;
    $_SESSION["IsManager"]=1;
    $_SESSION["Email"]='tfolsom@assetvision.com';
	$response['code'] = 'success';
}
include_once GetViewFile();
?>
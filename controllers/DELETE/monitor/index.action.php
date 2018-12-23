<?php
	// THIS IS THE MONITORING FOLDER
if(empty($_SESSION["UserId"]))
{
	header("Location: {$rootScope["RootUrl"]}/account/login");
	return;
}

include_once GetViewFile();
?>

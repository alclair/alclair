<?php
if( empty($_SESSION["UserId"]) || (empty($_SESSION["IsAdmin"])&&empty($_SESSION["IsManager"])) )
{
	header("Location: {$rootScope["RootUrl"]}/account/not_authorized");
	return;
}

include_once GetViewFile();
?>
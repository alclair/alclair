<?php
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
	header("Location: {$rootScope["RootUrl"]}/account/not_authorized");
}
include_once GetViewFile();
?>
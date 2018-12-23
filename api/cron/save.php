<?php
include_once "../../config.inc.php";

try
{	
	$response = array();
	$response["code"] = "success";
	$response["message"] = "";
	$query="select * from settings";
	$stmt=pdo_query($pdo,$query,null);
	$row=pdo_fetch_array($stmt);
	if(empty($row[0]))
	{
		$query="insert into settings (daily_emails, weekly_emails, monthly_emails,daily_disposal_total) values (:daily_emails, :weekly_emails, :monthly_emails, :daily_disposal_total)";
		$params=array(":daily_emails"=>$_REQUEST["daily_emails"],":weekly_emails"=>$_REQUEST["weekly_emails"],":monthly_emails"=>$_REQUEST["monthly_emails"], ":daily_disposal_total"=>$_REQUEST["daily_disposal_total"]);
		pdo_query($pdo,$query,$params);
	}
	else
	{
		$query="update settings set daily_emails=:daily_emails, weekly_emails=:weekly_emails, monthly_emails=:monthly_emails, daily_disposal_total=:daily_disposal_total where id=1";
		$params=array(":daily_emails"=>$_REQUEST["daily_emails"],":weekly_emails"=>$_REQUEST["weekly_emails"],":monthly_emails"=>$_REQUEST["monthly_emails"], ":daily_disposal_total"=>$_REQUEST["daily_disposal_total"]);
		pdo_query($pdo,$query,$params);
	}
		
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>